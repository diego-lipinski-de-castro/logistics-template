import 'package:delivery/controllers/home.dart';
import 'package:delivery/repositories/delivery.dart';
import 'package:delivery/services/auth.dart';
import 'package:delivery/services/http.dart';
import 'package:get/get.dart';

class HomeBinding extends Bindings {
  @override
  void dependencies() {
    Get.put<DeliveryRepository>(
      DeliveryRepository(
        httpProvider: Get.find<HttpService>(),
      ),
    );

    Get.put<HomeController>(
      HomeController(
        authService: Get.find<AuthService>(),
        deliveryRepository: Get.find<DeliveryRepository>(),
      ),
      permanent: true,
    );
  }
}
