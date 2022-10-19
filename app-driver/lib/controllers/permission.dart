import 'package:delivery/utils/routes.dart';
import 'package:geolocator/geolocator.dart';
import 'package:get/get.dart';

class PermissionController extends GetxController {
  void ignore() async {
    Get.offNamed(Routes.notification);
  }

  void handle() async {
    bool serviceEnabled = await Geolocator.isLocationServiceEnabled();

    if (!serviceEnabled) {
      return;
    }

    LocationPermission permission = await Geolocator.checkPermission();

    bool hasPermission = permission == LocationPermission.whileInUse ||
        permission == LocationPermission.always;

    if (!hasPermission) {
      permission = await Geolocator.requestPermission();

      hasPermission = permission == LocationPermission.whileInUse ||
          permission == LocationPermission.always;

      if (!hasPermission) {
        return;
      }
    }

    Get.offNamed(Routes.notification);
  }
}
