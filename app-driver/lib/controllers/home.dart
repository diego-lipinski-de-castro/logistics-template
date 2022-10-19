import 'dart:convert';
import 'dart:io';
import 'dart:isolate';
import 'dart:ui';

import 'package:android_device_info/android_device_info.dart';
import 'package:background_locator/background_locator.dart';
import 'package:background_locator/location_dto.dart';
import 'package:background_locator/settings/android_settings.dart'
    as BackgroundLocatorAndroidSettings;
import 'package:background_locator/settings/locator_settings.dart'
    as LocatorSettings;
import 'package:collection/collection.dart' show IterableExtension;
import 'dart:async';
import 'package:delivery/models/delivery.dart';
import 'package:delivery/models/driver.dart';
import 'package:delivery/repositories/delivery.dart';
import 'package:delivery/services/auth.dart';
import 'package:delivery/services/location/location_callback_handler.dart';
import 'package:delivery/services/location/location_service_repository.dart';
import 'package:delivery/services/storage.dart';
import 'package:delivery/utils/colors.dart';
import 'package:delivery/utils/config.dart';
import 'package:delivery/utils/routes.dart';
import 'package:delivery/utils/strings.dart';
import 'package:delivery/utils/utils.dart';
import 'package:flutter/material.dart';
import 'package:flutter_pusher/pusher.dart';
import 'package:geolocator/geolocator.dart';
// import 'package:foreground_service/foreground_service.dart';
// import 'package:geolocator/geolocator.dart';
import 'package:get/get.dart';
import 'package:assets_audio_player/assets_audio_player.dart';

class HomeController extends FullLifeCycleController {
  final scaffoldKey = GlobalKey<ScaffoldState>();

  final AuthService authService;
  final DeliveryRepository deliveryRepository;

  Timer? _timer;

  final _connected = RxBool(true);
  bool get connected => _connected.value;

  final _pusherConnected = Rx<String?>(null);
  String? get pusherConnected {
    switch (_pusherConnected.value?.toUpperCase()) {
      case "CONNECTED":
        return "CONECTADO";
      case "CONNECTING":
        return "CONECTANDO...";
      case "RECONNECTING":
        return "RECONECTANDO...";
      case "DISCONNECTED":
        return "DESCONECTADO";
      default:
        return null;
    }
  }

  Color? get pusherConnectionColor {
    switch (_pusherConnected.value?.toUpperCase()) {
      case "CONNECTED":
        return CustomColors.green;
      case "CONNECTING":
        return CustomColors.green;
      case "RECONNECTING":
        return Colors.yellow[600];
      case "DISCONNECTED":
        return CustomColors.red;
      default:
        return Colors.black;
    }
  }

  RxBool _isPlaying = RxBool(false);
  bool get isPlaying => _isPlaying.value;

  ReceivePort port = ReceivePort();
  bool isRunning = false;

  // StreamSubscription? _portSubscription;

  final _availableDeliveries = Rx<List<Delivery>>([]);
  List<Delivery> get availableDeliveries =>
      _availableDeliveries.value.toSet().toList();

  final _driverDeliveries = Rx<List<Delivery>>([]);
  List<Delivery> get driverDeliveries =>
      _driverDeliveries.value.toSet().toList();

  List<Delivery> get driverAndAvailableDeliveries {
    return (driverDeliveries + availableDeliveries)
        .sortedBy<DateTime>((d) => d.createdAt);
  }

  Channel? _channel;
  Channel? _driverChannel;

  String? get _channelName {
    if (!authService.authenticated) return null;

    if (authService.driver!.banned ||
        !authService.driver!.registered ||
        authService.driver!.status != DriverStatuses.APPROVED) {
      return null;
    }

    return 'deliveries.${authService.driver!.cityId}';
  }

  String? get _driverChannelName {
    if (!authService.authenticated) return null;

    if (authService.driver!.banned ||
        !authService.driver!.registered ||
        authService.driver!.status != DriverStatuses.APPROVED) {
      return null;
    }

    return 'driver.${authService.driver!.pubId}';
  }

  String get _pendingDeliveryEventName => 'pending';
  String get _removeDeliveryEventName => 'remove';

  AssetsAudioPlayer _player = AssetsAudioPlayer.newPlayer();

  HomeController({
    required this.authService,
    required this.deliveryRepository,
  });

  @override
  void onInit() {
    super.onInit();

    if (null == authService.driver) return;

    if (authService.driver!.banned ||
        !authService.driver!.registered ||
        authService.driver!.status != DriverStatuses.APPROVED) return;

    _init();
  }

  Future<void> _init() async {
    try {
      _player.open(
        Audio('assets/audio/buzz.mp3'),
        autoStart: false,
        showNotification: false,
        volume: 1.0,
        respectSilentMode: false,
        loopMode: LoopMode.single,
        playInBackground: PlayInBackground.enabled,
        forceOpen: true,
      );

      _player.isPlaying.listen((playing) {
        _isPlaying.value = playing;
      });

      _initPusher();

      _timer = Timer.periodic(
        Duration(seconds: 30),
        (timer) async {
          _connected.value = await Utils.checkConnection();
        },
      );

      _setup();

      if (Platform.isAndroid) {
        final systemInfo = await AndroidDeviceInfo().getSystemInfo();
        authService.updateDevice(systemInfo);
      }
    } catch (e, stack) {
      Utils.report(e, stack, 'HomeController._init');
    }
  }

  _initPusher() async {
    try {
      await Pusher.init(
        Config.PUSHER_APP_KEY,
        PusherOptions(
          cluster: Config.PUSHER_APP_CLUSTER,
          // auth: PusherAuth('${Config.BASE_URL}/broadcasting/auth'),
        ),
        enableLogging: Config.PUSHER_ENABLE_LOGGING,
      );
    } catch (e, stack) {
      Utils.report(e, stack, 'HomeController._initPusher');
    }
  }

  Future<void> refresh() async {
    _periodicUpdate();
    stop();
  }

  Future<void> _periodicUpdate() async {
    List<Delivery> driverDeliveries = await deliveryRepository.index();

    _driverDeliveries.update((list) {
      list!.clear();
      list.addAll(driverDeliveries);
    });

    if (null == _pusherConnected.value ||
        _pusherConnected.value != 'DISCONNECTED') return;
    retrySubscribeToDeliveries();
  }

  Future<void> _setup() async {
    if (authService.driver!.metadata.status != MetadataStatuses.OFFLINE) {
      bool hasPermission = await _requestLocationPermission();
      if (!hasPermission) return;

      await _setupLocator();
      await _subscribeToPosition();
      await _subscribeToDeliveries();
      await _periodicUpdate();
    }
  }

  Future<void> logout() async {
    if (authService.driver!.metadata.status == MetadataStatuses.BUSY) {
      Get.rawSnackbar(
        title: 'Você não sair enquanto estiver ocupado.',
        message: 'Termine as entregas para sair.',
      );

      return;
    }

    if (authService.driver!.metadata.status == MetadataStatuses.ONLINE) {
      Get.rawSnackbar(
        title: 'Você não sair enquanto estiver online.',
        message: 'Fique offline para sair.',
      );

      return;
    }

    await StorageService.box.remove(Config.ACCESS_TOKEN);
    authService.setDriver();

    Get.offAllNamed(Routes.login);

    _timer?.cancel();
    _unsubscribeToPosition();

    try {
      if (null != _driverChannel) {
        await _driverChannel!.unbind(_pendingDeliveryEventName);
      }

      if (null != _channel) {
        await _channel!.unbind(_pendingDeliveryEventName);
        await _channel!.unbind(_removeDeliveryEventName);
      }

      if (null != _driverChannelName) {
        await Pusher.unsubscribe(_driverChannelName!);
      }

      _driverChannel = null;

      if (null != _channelName) {
        await Pusher.unsubscribe(_channelName!);
      }

      _channel = null;

      await Pusher.disconnect();
    } catch (e, stack) {
      Utils.report(e, stack, 'HomeController.logout');
    }
  }

  void updateLocation(LocationDto? location) async {
    dynamic batteryLevel;

    if (Platform.isAndroid) {
      final batteryInfo = await AndroidDeviceInfo().getBatteryInfo();

      if (batteryInfo.containsKey('batteryPercentage')) {
        batteryLevel = batteryInfo['batteryPercentage'];
      }
    }

    if (null != location) {
      authService.updateLocation(
        location.latitude,
        location.longitude,
        batteryLevel,
      );
    }
  }

  Future<bool> _requestLocationPermission() async {
    bool serviceEnabled = await Geolocator.isLocationServiceEnabled();

    if (!serviceEnabled) {
      Get.rawSnackbar(
        title: 'Os servicos de localização estão desabilitados nesse aparelho.',
        message:
            'Para realizar entregas, é necessário habilitar os serviços de localização.',
      );

      return false;
    }

    LocationPermission permission = await Geolocator.checkPermission();

    bool hasPermission = permission == LocationPermission.whileInUse ||
        permission == LocationPermission.always;

    if (!hasPermission) {
      await Get.dialog(
        AlertDialog(
          title: Text(Strings.localizationDialogTitle),
          content: Text(Strings.localizationDialogContent),
          actions: [
            FlatButton(
              onPressed: () {
                Get.back();
              },
              child: Text(
                Strings.localizationDialogButton,
                style: TextStyle(color: CustomColors.orange),
              ),
            ),
          ],
        ),
      );

      permission = await Geolocator.requestPermission();

      hasPermission = permission == LocationPermission.whileInUse ||
          permission == LocationPermission.always;

      if (!hasPermission) {
        Get.rawSnackbar(
          title: 'Sem permissão de acesso à localização.',
          message: 'Clique aqui para permitir a localização.',
          onTap: (snack) {
            Geolocator.openLocationSettings();
          },
        );

        return false;
      }
    }

    return true;
  }

  Future<void> toggleStatus() async {
    if (authService.driver!.metadata.status == MetadataStatuses.BUSY) {
      Get.rawSnackbar(
        title: 'Você não pode alterar seu status enquanto estiver ocupado.',
        message: 'Para maiores dúvidas entre em contato com o suporte.',
      );

      return;
    }

    bool skipDialog = StorageService.box.hasData(Config.SKIP_ONLINE_DIALOG);

    if (!skipDialog) {
      await Get.dialog(
        AlertDialog(
          title: Text(Strings.localizationDialogTitle),
          content: Text(Strings.localizationDialogContent),
          actions: [
            FlatButton(
              onPressed: () {
                StorageService.box.write(Config.SKIP_ONLINE_DIALOG, true);
                Get.back();
              },
              child: Text(
                Strings.localizationDialogButton,
                style: TextStyle(color: CustomColors.orange),
              ),
            ),
          ],
        ),
      );
    }

    if (authService.driver!.metadata.status == MetadataStatuses.OFFLINE) {
      bool hasPermission = await _requestLocationPermission();

      if (!hasPermission) return;
    }

    await authService.updateStatus();

    if (authService.driver!.metadata.status == MetadataStatuses.ONLINE) {
      await _setupLocator();
      await _subscribeToPosition();
      await _subscribeToDeliveries();
    }

    if (authService.driver!.metadata.status == MetadataStatuses.OFFLINE) {
      _unsubscribeToPosition();

      if (null != _driverChannelName) {
        await Pusher.unsubscribe(_driverChannelName!);
      }

      _driverChannel = null;

      if (null != _channelName) {
        await Pusher.unsubscribe(_channelName!);
      }

      _channel = null;

      await Pusher.disconnect();
    }
  }

  Future<void> _setupLocator() async {
    if (isRunning) return;

    try {
      if (IsolateNameServer.lookupPortByName(
              LocationServiceRepository.isolateName) !=
          null) {
        IsolateNameServer.removePortNameMapping(
          LocationServiceRepository.isolateName,
        );
      }

      IsolateNameServer.registerPortWithName(
        port.sendPort,
        LocationServiceRepository.isolateName,
      );

      // _portSubscription?.cancel();

      // port.close();

      port.listen(
        (dynamic data) async {
          LocationDto? location = data;
          updateLocation(location);
        },
      );

      await BackgroundLocator.initialize();
      isRunning = await BackgroundLocator.isServiceRunning();
    } catch (e, stack) {
      Utils.report(e, stack, 'HomeController._setupLocator');
    }
  }

  Future<void> _subscribeToPosition() async {
    if (!isRunning) {
      await _startLocator();
    }

    isRunning = await BackgroundLocator.isServiceRunning();
  }

  Future<void> _unsubscribeToPosition() async {
    if (isRunning) {
      await BackgroundLocator.unRegisterLocationUpdate();
      isRunning = await BackgroundLocator.isServiceRunning();
    }
  }

  Future<void> _startLocator() async {
    return await BackgroundLocator.registerLocationUpdate(
      LocationCallbackHandler.callback,
      initCallback: LocationCallbackHandler.initCallback,
      initDataCallback: {},
      disposeCallback: LocationCallbackHandler.disposeCallback,
      autoStop: false,
      androidSettings: BackgroundLocatorAndroidSettings.AndroidSettings(
        wakeLockTime: 60 * 24,
        accuracy: LocatorSettings.LocationAccuracy.NAVIGATION,
        interval: 10,
        distanceFilter: 0,
        client: BackgroundLocatorAndroidSettings.LocationClient.google,
        androidNotificationSettings:
            BackgroundLocatorAndroidSettings.AndroidNotificationSettings(
          notificationChannelName: 'SpeedApp Delivery Location tracking',
          notificationTitle: 'SpeedApp Entregador - Disponível',
          notificationMsg: 'SpeedApp Entregador - Disponível',
          notificationBigMsg: '',
          notificationTapCallback: LocationCallbackHandler.notificationCallback,
          notificationIcon: '@drawable/ic_notification',
          notificationIconColor: CustomColors.orange,
        ),
      ),
    );
  }

  Future<void> _subscribeToDeliveries() async {
    try {
      if (null != _driverChannel) {
        await _driverChannel!.unbind(_pendingDeliveryEventName);
      }

      if (null != _driverChannelName) {
        await Pusher.unsubscribe(_driverChannelName!);
      }

      _driverChannel = null;

      if (null != _channel) {
        await _channel!.unbind(_pendingDeliveryEventName);
        await _channel!.unbind(_removeDeliveryEventName);
      }

      if (null != _channelName) {
        await Pusher.unsubscribe(_channelName!);
      }

      _channel = null;

      await Pusher.disconnect();

      await Pusher.connect(
        onConnectionStateChange: (state) async {
          _pusherConnected.value = state.currentState;
        },
        onError: (e) {
          Utils.report(e, null, {
            'code': e.code,
            'message': e.message,
            'exception': e.exception,
          });
        },
      );

      if (null != _driverChannelName) {
        _driverChannel = await Pusher.subscribe(_driverChannelName!);
        _driverChannel!.bind(
          _pendingDeliveryEventName,
          _pendingDeliveryEventHandler,
        );
      }

      if (null != _channelName) {
        _channel = await Pusher.subscribe(_channelName!);
        _channel!.bind(
          _pendingDeliveryEventName,
          _pendingDeliveryEventHandler,
        );
        _channel!.bind(
          _removeDeliveryEventName,
          _removeDeliveryEventHandler,
        );
      }
    } catch (e, stack) {
      Utils.report(e, stack, 'HomeController._subscribeToDeliveries');
    }
  }

  void _pendingDeliveryEventHandler(Event event) async {
    try {
      final deliveryId = jsonDecode(event.data)['delivery_id'];

      Delivery? delivery = await deliveryRepository.receive(deliveryId);

      if (null == delivery) return;

      if (delivery.driverId == null) {
        _availableDeliveries.update((list) {
          list!.add(delivery);
        });
      } else {
        _driverDeliveries.update((list) {
          list!.add(delivery);
        });
      }

      _player.play();
    } catch (e, stack) {
      Utils.report(e, stack, 'HomeController._pendingDeliveryEventHandler');
    }
  }

  void _removeDeliveryEventHandler(Event event) {
    try {
      final deliveryId = jsonDecode(event.data)['delivery_id'];

      _availableDeliveries.update((list) {
        list!.removeWhere((d) => d.id == deliveryId);
      });

      _driverDeliveries.update((list) {
        list!.removeWhere((d) => d.id == deliveryId);
      });

      stop();
    } catch (e, stack) {
      Utils.report(e, stack, 'HomeController._removeDeliveryEventHandler');
    }
  }

  void declineDelivery(Delivery delivery) {
    _availableDeliveries.update((list) {
      list!.removeWhere((d) => d.id == delivery.id);
    });

    _driverDeliveries.update((list) {
      list!.removeWhere((d) => d.id == delivery.id);
    });

    stop();

    deliveryRepository.refuse(delivery.id);
  }

  Future<void> acceptDelivery(Delivery delivery) async {
    stop();

    Delivery? result = await deliveryRepository.accept(delivery);

    if (null == result) return;

    authService.setDriver();

    _availableDeliveries.update((list) {
      list!.removeWhere((d) => d.id == delivery.id);
    });

    if (result.driverId != authService.driver!.id) {
      Get.rawSnackbar(
        title: 'Não foi possível aceitar a entrega.',
        message: 'Essa entrega já foi aceita por outro entregador',
      );

      return;
    }

    _driverDeliveries.update((list) {
      list!.add(result);
    });

    trackDelivery(result);
    _periodicUpdate();
  }

  Future<void> finishDelivery(Delivery delivery) async {
    authService.setDriver();

    _driverDeliveries.update((list) {
      list!.removeWhere((d) => d.id == delivery.id);
    });
  }

  Future<void> trackDelivery(Delivery delivery) async {
    Delivery? result = await Get.toNamed(
      Routes.tracking,
      arguments: delivery,
    ) as Delivery?;

    if (null != result) {
      finishDelivery(result);
    }
  }

  Future<void> stop() async {
    if (isPlaying) {
      _player.stop();
    }
  }

  void retrySubscribeToDeliveries() {
    _subscribeToDeliveries();
  }

  Future<void> refreshNotApproved() async {
    authService.setDriver();
  }

  @override
  void onResumed() {
    if (!authService.authenticated) return;

    if (authService.driver!.banned ||
        !authService.driver!.registered ||
        authService.driver!.status != DriverStatuses.APPROVED) {
      return;
    }

    if (null == _pusherConnected.value ||
        _pusherConnected.value != 'DISCONNECTED') return;

    retrySubscribeToDeliveries();
  }
}
