import 'package:delivery/controllers/register_confirm.dart';
import 'package:delivery/repositories/register.dart';
import 'package:delivery/repositories/state.dart';
import 'package:delivery/services/auth.dart';
import 'package:delivery/services/http.dart';
import 'package:get/get.dart';

class RegisterConfirmBinding extends Bindings {
  @override
  void dependencies() {
    Get.put<RegisterRepository>(
      RegisterRepository(
        httpProvider: Get.find<HttpService>(),
      ),
    );

    Get.put<StateRepository>(
      StateRepository(
        httpProvider: Get.find<HttpService>(),
      ),
    );

    Get.put<RegisterConfirmController>(
      RegisterConfirmController(
        authService: Get.find<AuthService>(),
        registerRepository: Get.find<RegisterRepository>(),
        stateRepository: Get.find<StateRepository>(),
      ),
    );
  }
}
