import 'package:delivery/models/args/login_confirm.dart';
import 'package:delivery/repositories/login.dart';
import 'package:delivery/services/auth.dart';
import 'package:delivery/utils/routes.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';

class LoginController extends GetxController {
  final AuthService authService;
  final LoginRepository loginRepository;

  final GlobalKey<FormState> formKey = GlobalKey<FormState>();
  final TextEditingController phoneField = TextEditingController();

  String get _phoneNumber => '+55 ' + phoneField.text.trim();

  final _loading = RxBool(false);
  bool get loading => _loading.value;

  LoginController({
    required this.authService,
    required this.loginRepository,
  });

  void submit() async {
    if (loading) return;

    if (formKey.currentState!.validate()) {
      _loading.value = true;
      Get.focusScope!.unfocus();

      String? phoneNumber = await loginRepository.requestCode(_phoneNumber);

      _loading.value = false;

      if (null == phoneNumber) {
        return;
      }

      await Get.toNamed(
        Routes.loginConfirm,
        arguments: LoginConfirmArgs(phoneNumber),
      );
    }
  }
}
