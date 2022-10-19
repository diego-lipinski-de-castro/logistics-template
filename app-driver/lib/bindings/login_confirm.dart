import 'package:delivery/controllers/login_confirm.dart';
import 'package:delivery/repositories/login.dart';
import 'package:delivery/services/auth.dart';
import 'package:delivery/services/http.dart';
import 'package:get/get.dart';

class LoginConfirmBinding extends Bindings {
  @override
  void dependencies() {
    Get.put<LoginRepository>(
      LoginRepository(
        httpProvider: Get.find<HttpService>(),
      ),
    );

    Get.put<LoginConfirmController>(
      LoginConfirmController(
        authService: Get.find<AuthService>(),
        loginRepository: Get.find<LoginRepository>(),
      ),
    );
  }
}
