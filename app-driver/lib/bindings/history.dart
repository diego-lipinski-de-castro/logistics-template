import 'package:delivery/controllers/history.dart';
import 'package:delivery/repositories/delivery.dart';
import 'package:delivery/services/http.dart';
import 'package:get/get.dart';

class HistoryBinding extends Bindings {
  @override
  void dependencies() {
    Get.put<DeliveryRepository>(
      DeliveryRepository(
        httpProvider: Get.find<HttpService>(),
      ),
    );

    Get.put<HistoryController>(
      HistoryController(
        deliveryRepository: Get.find<DeliveryRepository>(),
      ),
    );
  }
}
