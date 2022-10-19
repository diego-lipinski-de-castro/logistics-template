import 'dart:typed_data';
import 'package:delivery/models/delivery.dart'
    show Delivery, Step, DeliveryStatuses, ReceiptConfirmation;
import 'package:delivery/models/settings.dart';
import 'package:delivery/repositories/delivery.dart';
import 'package:delivery/utils/colors.dart';
import 'package:flutter/material.dart' as Material;
import 'package:flutter/services.dart';
import 'package:get/get.dart';
import 'package:image_picker/image_picker.dart';
import 'package:map_launcher/map_launcher.dart';
import 'package:url_launcher/url_launcher.dart';

class TrackingController extends GetxController {
  final DeliveryRepository deliveryRepository;

  final Material.GlobalKey<Material.FormState> formKey =
      Material.GlobalKey<Material.FormState>();
  final Material.TextEditingController nameField =
      Material.TextEditingController();
  final Material.TextEditingController docField =
      Material.TextEditingController();

  final Rx<XFile?> _photo = Rx<XFile?>(null);
  XFile? get photo => _photo.value;

  Future<Uint8List?> get photoPreview async {
    if (null == photo) return null;

    return await photo?.readAsBytes();
  }

  late Rx<Delivery> _delivery;
  Delivery get delivery => _delivery.value;

  final _loading = RxBool(true);
  bool get loading => _loading.value;

  TrackingController({required this.deliveryRepository}) {
    _delivery = Rx<Delivery>(Get.arguments as Delivery);
  }

  @override
  void onInit() {
    super.onInit();

    _init();
  }

  Future<void> _init() async {
    Delivery? res = await deliveryRepository.show(delivery);

    if (null == res) {
      Get.back();
      return;
    }

    _delivery.value = res;
    _delivery.refresh();

    _loading.value = false;
  }

  Future<void> collect() async {
    _loading.value = true;

    Delivery? res = await deliveryRepository.collect(delivery);

    if (null == res) return;

    _delivery.value = res;
    _delivery.refresh();

    _loading.value = false;
  }

  Future<void> deliver() async {
    _loading.value = true;

    Delivery? res = await deliveryRepository.deliver(delivery);

    if (null == res) return;

    _delivery.value = res;
    _delivery.refresh();

    _loading.value = false;
  }

  Future<void> confirm() async {
    FormData data = FormData({});

    if (delivery.receiptConfirmation != ReceiptConfirmation.DISABLED) {
      Get.focusScope!.unfocus();

      if (delivery.receiptConfirmation != ReceiptConfirmation.OPTIONAL) {
        if (!formKey.currentState!.validate()) return;
      }

      if (delivery.receiptConfirmation == ReceiptConfirmation.REQUIRED) {
        if (null == photo) {
          await Get.dialog(
            Material.AlertDialog(
              title: Material.Text('Confirmação obrigatória!'),
              content: Material.Text(
                'Adicionar uma foto é obrigatório para confirmar essa entrega',
              ),
              actions: [
                Material.FlatButton(
                  onPressed: () {
                    Get.back();
                  },
                  child: Material.Text(
                    'Voltar',
                    style: Material.TextStyle(color: CustomColors.black),
                  ),
                ),
                Material.FlatButton(
                  onPressed: () {
                    Get.back();
                    addImage();
                  },
                  child: Material.Text(
                    'Adicionar foto',
                    style: Material.TextStyle(color: CustomColors.black),
                  ),
                ),
              ],
            ),
          );

          return;
        }
      }

      data.fields.add(MapEntry('customer_name', nameField.text));
      data.fields.add(MapEntry('customer_document', docField.text));

      if (null != photo) {
        data.files.add(MapEntry(
          'picture',
          MultipartFile(
            photo!.path,
            filename: photo!.name,
          ),
        ));
      }
    }

    _loading.value = true;

    Delivery? res = await deliveryRepository.confirm(delivery, data);

    if (null == res) {
      _loading.value = false;

      return;
    }

    _delivery.value = res;
    _delivery.refresh();

    _loading.value = false;

    if (_delivery.value.status == DeliveryStatuses.COMPLETED) {
      Get.back(result: delivery);
    }
  }

  Future<void> complete() async {
    _loading.value = true;

    Delivery? res = await deliveryRepository.complete(delivery);

    if (null == res) return;

    _delivery.value = res;
    _delivery.refresh();

    _loading.value = false;

    Get.back(result: delivery);
  }

  Future<void> copyStepAddress(Step step) async {
    Clipboard.setData(
      ClipboardData(
        text:
            '${step.streetName}, ${step.streetNumber} - ${step.district}, ${step.city} - ${step.state}',
      ),
    );

    Get.rawSnackbar(
      title: 'Endereço copiado!',
      message: 'Abra seu app de mapa e cole na barra de pesquisa',
    );
  }

  Future<void> openMapsToStep(Step step) async {
    final availableMaps = await MapLauncher.installedMaps;

    await availableMaps.first.showDirections(
      destination: Coords(
        step.location.coordinates[1],
        step.location.coordinates[0],
      ),
      // directionsMode: DirectionsMode.bicycling,
    );
  }

  Future<void> openPhoneDialogToCustomer() async {
    if (null == delivery.customerPhone) return;

    await launch('tel:${delivery.customerPhone}');
  }

  Future<void> copyCustomerPhone() async {
    if (null == delivery.customerPhone) return;

    Clipboard.setData(ClipboardData(text: delivery.customerPhone));

    Get.rawSnackbar(
      message: 'Número de telefone copiado!',
    );
  }

  Future<void> openWhatsapp([String? message]) async {
    if (message == null) {
      await launch(
        'https://api.whatsapp.com/send?phone=${Settings.whatsappNumber}',
      );
    } else {
      String text = Uri.encodeFull(message);

      await launch(
        'https://api.whatsapp.com/send?phone=${Settings.whatsappNumber}&text=$text',
      );
    }
  }

  addImage() async {
    final ImagePicker _picker = ImagePicker();

    _photo.value = await _picker.pickImage(source: ImageSource.camera);
  }

  removeImage() {
    _photo.value = null;
  }
}
