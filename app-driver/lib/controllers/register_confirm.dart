import 'package:delivery/models/args/register_confirm.dart';
import 'package:delivery/models/state.dart' as StateModel;
import 'package:delivery/repositories/register.dart';
import 'package:delivery/repositories/state.dart';
import 'package:delivery/services/auth.dart';
import 'package:delivery/services/storage.dart';
import 'package:delivery/utils/config.dart';
import 'package:delivery/utils/routes.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';

class RegisterConfirmController extends GetxController {
  final GlobalKey<FormState> formKey = GlobalKey<FormState>();
  final TextEditingController codeField = TextEditingController();

  final AuthService authService;
  final RegisterRepository registerRepository;
  final StateRepository stateRepository;

  String get _smsCode => codeField.text.trim();

  final _loading = RxBool(false);
  bool get loading => _loading.value;

  late String _phoneNumber;
  String get phoneNumber => _phoneNumber;

  RegisterConfirmController({
    required this.authService,
    required this.registerRepository,
    required this.stateRepository,
  });

  @override
  void onInit() {
    super.onInit();

    _phoneNumber = (Get.arguments as RegisterConfirmArgs).phoneNumber;
  }

  void submit() async {
    if (loading) return;

    if (formKey.currentState!.validate()) {
      _loading.value = true;
      Get.focusScope!.unfocus();

      String? accessToken = await registerRepository.checkCode(
        _phoneNumber,
        _smsCode,
      );

      if (null == accessToken) {
        _loading.value = false;
      } else {
        await StorageService.box.write(Config.ACCESS_TOKEN, accessToken);
        await authService.setDriver();

        List<StateModel.State> states = await stateRepository.index();

        Get.offAllNamed(
          Routes.registerFinish,
          arguments: states,
        );
      }
    }
  }
}
