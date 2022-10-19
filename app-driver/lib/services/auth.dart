import 'package:delivery/models/driver.dart';
import 'package:delivery/repositories/profile.dart';
import 'package:delivery/repositories/metadata.dart';
import 'package:get/get.dart';
// import 'package:onesignal_flutter/onesignal_flutter.dart';

class AuthService extends GetxService {
  final ProfileRepository profileRepository;
  final MetadataRepository metadataRepository;

  AuthService({
    required this.profileRepository,
    required this.metadataRepository,
  });

  final _driver = Rx<Driver?>(null);
  Driver? get driver => _driver.value;

  bool get authenticated => null != driver;

  @override
  void onInit() {
    super.onInit();

    setDriver();
  }

  Future<void> setDriver() async {
    _driver.value = await profileRepository.profile();

    // if (null == driver) {
    //   OneSignal.shared.removeExternalUserId();
    // } else {
    //   OneSignal.shared.setExternalUserId(driver!.pubId);
    // }
  }

  Future<void> updateStatus() async {
    var _initialStatus = driver!.metadata.status;

    _driver.update((driver) {
      driver!.metadata.status =
          driver.metadata.status == MetadataStatuses.OFFLINE
              ? MetadataStatuses.ONLINE
              : MetadataStatuses.OFFLINE;
    });

    MetadataStatuses? status = await metadataRepository.updateStatus();

    if (null == status) {
      _driver.update((driver) {
        driver!.metadata.status = _initialStatus;
      });
    } else {
      _driver.update((driver) {
        driver!.metadata.status = status;
      });
    }
  }

  Future<void> updateLocation(
    double latitude,
    double longitude,
    dynamic batteryLevel,
  ) async {
    metadataRepository.updateLocation(
      latitude,
      longitude,
      batteryLevel,
    );
  }

  Future<void> updateDevice(dynamic systemInfo) async {
    metadataRepository.updateDevice(systemInfo);
  }
}
