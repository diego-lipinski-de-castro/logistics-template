import 'package:delivery/controllers/permission.dart';
import 'package:get/get.dart';

class PermissionBinding extends Bindings {
  @override
  void dependencies() {
    Get.put<PermissionController>(PermissionController());
  }
}
