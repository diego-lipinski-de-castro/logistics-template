import 'package:delivery/services/auth.dart';
import 'package:delivery/services/storage.dart';
import 'package:delivery/utils/colors.dart';
import 'package:delivery/utils/config.dart';
import 'package:delivery/utils/routes.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';
// import 'package:onesignal_flutter/onesignal_flutter.dart';

class SplashController extends GetxController {
  final AuthService authService;

  SplashController({required this.authService});

  @override
  onInit() async {
    super.onInit();

    // OneSignal.shared.setNotificationWillShowInForegroundHandler((
    //   OSNotificationReceivedEvent event,
    // ) {
    //   event.complete(event.notification);
    // });

    // OneSignal.shared.setNotificationOpenedHandler((
    //   OSNotificationOpenedResult result,
    // ) {});
  }

  @override
  void onReady() async {
    super.onReady();

    bool skipDialog = StorageService.box.hasData(Config.SKIP_START_DIALOG);

    if (!skipDialog) {
      await Get.dialog(
        AlertDialog(
          title: Text('Localização'),
          content: Text(
            'Este aplicativo coleta dados de localização em segundo plano para verificar localização atual do entregador, disponibilizar entregas, e rastrear percursos, mesmo quando o aplicativo está fechado ou não está em uso.',
          ),
          actions: [
            FlatButton(
              onPressed: () {
                StorageService.box.write(Config.SKIP_START_DIALOG, true);
                Get.back();
              },
              child: Text(
                'Entendi',
                style: TextStyle(color: CustomColors.orange),
              ),
            ),
          ],
        ),
      );
    }

    await authService.setDriver();

    Future.delayed(Duration(milliseconds: 1500), () {
      if (authService.authenticated) {
        if (!authService.driver!.registered) {
          Get.offNamed(Routes.registerFinish);
        } else {
          Get.offNamed(Routes.home);
        }
      } else {
        bool skipIntro = StorageService.box.hasData(Config.SKIP_INTRO);

        if (skipIntro) {
          Get.offNamed(Routes.login);
        } else {
          Get.offNamed(Routes.permission);
        }
      }
    });
  }
}
