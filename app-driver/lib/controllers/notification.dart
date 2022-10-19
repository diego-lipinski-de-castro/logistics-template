import 'package:delivery/services/storage.dart';
import 'package:delivery/utils/config.dart';
import 'package:delivery/utils/routes.dart';
import 'package:get/get.dart';
// import 'package:onesignal_flutter/onesignal_flutter.dart';

class NotificationController extends GetxController {
  void ignore() async {
    StorageService.box.write(Config.SKIP_INTRO, true);

    Get.offNamed(Routes.login);
  }

  void handle() async {
    // await OneSignal.shared.promptUserForPushNotificationPermission();

    StorageService.box.write(Config.SKIP_INTRO, true);

    Get.offNamed(Routes.login);
  }
}
