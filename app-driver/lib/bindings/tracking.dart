import 'package:delivery/controllers/tracking.dart';
import 'package:delivery/repositories/delivery.dart';
import 'package:delivery/services/http.dart';
import 'package:get/get.dart';

class TrackingBinding extends Bindings {
  @override
  void dependencies() {
    Get.put<DeliveryRepository>(
      DeliveryRepository(
        httpProvider: Get.find<HttpService>(),
      ),
    );

    Get.put<TrackingController>(
      TrackingController(
        deliveryRepository: Get.find<DeliveryRepository>(),
      ),
    );
  }
}
