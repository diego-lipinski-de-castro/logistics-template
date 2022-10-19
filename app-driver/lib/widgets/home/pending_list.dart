import 'package:delivery/controllers/home.dart';
import 'package:delivery/models/delivery.dart';
import 'package:delivery/models/driver.dart';
import 'package:delivery/widgets/home/pending_list_item.dart';
import 'package:delivery/widgets/home/pending_list_item_in_progress.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:get/get_state_manager/get_state_manager.dart';
import 'pending_empty.dart';

class PendingList extends GetWidget<HomeController> {
  @override
  Widget build(BuildContext context) {
    return Obx(() {
      if (controller.driverAndAvailableDeliveries.isEmpty) {
        return PendingEmpty(
            online: controller.authService.driver!.metadata.status !=
                MetadataStatuses.OFFLINE);
      }

      return ListView.separated(
        primary: false,
        shrinkWrap: true,
        physics: NeverScrollableScrollPhysics(),
        padding: EdgeInsets.symmetric(vertical: 20),
        itemCount: controller.driverAndAvailableDeliveries.length,
        separatorBuilder: (ctx, index) {
          return SizedBox(height: 20);
        },
        itemBuilder: (ctx, index) {
          Delivery _delivery = controller.driverAndAvailableDeliveries[index];

          if (_delivery.status == DeliveryStatuses.PENDING) {
            return PendingListItem(
              delivery: _delivery,
              onDecline: (delivery) {
                controller.declineDelivery(delivery);
              },
              onAccept: (delivery) {
                controller.acceptDelivery(delivery);
              },
            );
          }

          return PendingListItemInProgress(
            delivery: _delivery,
            onTap: (delivery) {
              controller.trackDelivery(delivery);
            },
          );
        },
      );
    });
  }
}
