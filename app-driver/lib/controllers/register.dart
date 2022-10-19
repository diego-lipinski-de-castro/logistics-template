import 'package:delivery/models/args/register_confirm.dart';
import 'package:delivery/repositories/register.dart';
import 'package:delivery/services/auth.dart';
import 'package:delivery/utils/routes.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';

class RegisterController extends GetxController {
  final AuthService authService;

  final RegisterRepository registerRepository;

  final GlobalKey<FormState> formKey = GlobalKey<FormState>();
  final TextEditingController phoneField = TextEditingController();

  String get _phoneNumber => '+55 ' + phoneField.text.trim();

  final _loading = RxBool(false);
  bool get loading => _loading.value;

  RegisterController({
    required this.authService,
    required this.registerRepository,
  });

  void submit() async {
    if (loading) return;

    if (formKey.currentState!.validate()) {
      _loading.value = true;
      Get.focusScope!.unfocus();

      String? phoneNumber = await registerRepository.requestCode(_phoneNumber);

      _loading.value = false;

      if (null == phoneNumber) {
        return;
      }

      await Get.toNamed(
        Routes.registerConfirm,
        arguments: RegisterConfirmArgs(phoneNumber),
      );
    }
  }
}
