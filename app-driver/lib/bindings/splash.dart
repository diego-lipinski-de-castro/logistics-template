import 'package:delivery/controllers/splash.dart';
import 'package:delivery/services/auth.dart';
import 'package:get/get.dart';

class SplashBinding extends Bindings {
  @override
  void dependencies() {
    Get.put<SplashController>(
      SplashController(
        authService: Get.find<AuthService>(),
      ),
    );
  }
}
