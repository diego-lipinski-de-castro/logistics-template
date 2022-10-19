import 'package:delivery/models/args/login_confirm.dart';
import 'package:delivery/repositories/login.dart';
import 'package:delivery/services/auth.dart';
import 'package:delivery/services/storage.dart';
import 'package:delivery/utils/config.dart';
import 'package:delivery/utils/routes.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';

class LoginConfirmController extends GetxController {
  final AuthService authService;
  final LoginRepository loginRepository;

  final GlobalKey<FormState> formKey = GlobalKey<FormState>();
  final TextEditingController codeField = TextEditingController();

  String get _smsCode => codeField.text.trim();

  final _loading = RxBool(false);
  bool get loading => _loading.value;

  late String _phoneNumber;
  String get phoneNumber => _phoneNumber;

  LoginConfirmController({
    required this.authService,
    required this.loginRepository,
  });

  @override
  void onInit() {
    super.onInit();

    _phoneNumber = (Get.arguments as LoginConfirmArgs).phoneNumber;
  }

  void submit() async {
    if (loading) return;

    if (formKey.currentState!.validate()) {
      _loading.value = true;
      Get.focusScope!.unfocus();

      String? accessToken = await loginRepository.checkCode(
        phoneNumber,
        _smsCode,
      );

      if (null == accessToken) {
        _loading.value = false;
      } else {
        await StorageService.box.write(Config.ACCESS_TOKEN, accessToken);
        await authService.setDriver();

        if (!authService.driver!.registered) {
          Get.offAllNamed(Routes.registerFinish);
        } else {
          Get.offAllNamed(Routes.home);
        }
      }
    }
  }
}
