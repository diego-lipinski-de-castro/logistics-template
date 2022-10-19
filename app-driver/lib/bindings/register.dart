import 'package:delivery/controllers/register.dart';
import 'package:delivery/repositories/register.dart';
import 'package:delivery/services/auth.dart';
import 'package:delivery/services/http.dart';
import 'package:get/get.dart';

class RegisterBinding extends Bindings {
  @override
  void dependencies() {
    Get.put<RegisterRepository>(
      RegisterRepository(
        httpProvider: Get.find<HttpService>(),
      ),
    );

    Get.put<RegisterController>(
      RegisterController(
        authService: Get.find<AuthService>(),
        registerRepository: Get.find<RegisterRepository>(),
      ),
    );
  }
}
