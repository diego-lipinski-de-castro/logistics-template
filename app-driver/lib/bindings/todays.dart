import 'package:delivery/controllers/todays.dart';
import 'package:delivery/repositories/delivery.dart';
import 'package:delivery/services/http.dart';
import 'package:get/get.dart';

class TodaysBinding extends Bindings {
  @override
  void dependencies() {
    Get.put<DeliveryRepository>(
      DeliveryRepository(
        httpProvider: Get.find<HttpService>(),
      ),
    );

    Get.put<TodaysController>(
      TodaysController(
        deliveryRepository: Get.find<DeliveryRepository>(),
      ),
    );
  }
}
