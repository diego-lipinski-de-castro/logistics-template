import 'package:delivery/controllers/splash.dart';
import 'package:delivery/utils/colors.dart';
import 'package:flutter/material.dart';
import 'package:get/get_state_manager/get_state_manager.dart';

class SplashPage extends GetView<SplashController> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        decoration: BoxDecoration(color: CustomColors.orange),
        child: Center(),
      ),
    );
  }
}
