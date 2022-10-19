import 'package:delivery/controllers/todays.dart';
import 'package:delivery/utils/colors.dart';
import 'package:delivery/utils/strings.dart';
import 'package:delivery/widgets/history/empty.dart';
import 'package:delivery/widgets/history/list_item.dart';
import 'package:flutter/material.dart';
import 'package:flutter_spinkit/flutter_spinkit.dart';
import 'package:get/get.dart';

class TodaysPage extends GetView<TodaysController> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: CustomColors.white,
        leading: BackButton(
          color: CustomColors.orange,
        ),
        title: Text(
          Strings.todayTitle,
          style: TextStyle(color: CustomColors.orange),
        ),
        centerTitle: true,
      ),
      body: Obx(() {
        if (controller.loading) {
          return Center(
            child: SpinKitThreeBounce(
              color: CustomColors.orange,
              size: 15,
            ),
          );
        }

        if (controller.deliveries.isEmpty) {
          return HistoryEmpty();
        }

        return SingleChildScrollView(
          controller: controller.scrollController,
          child: Column(
            children: [
              ListView.separated(
                shrinkWrap: true,
                physics: NeverScrollableScrollPhysics(),
                padding: EdgeInsets.symmetric(vertical: 20),
                itemCount: controller.deliveries.length,
                separatorBuilder: (ctx, index) {
                  return SizedBox(height: 20);
                },
                itemBuilder: (ctx, index) {
                  return HistoryListItem(
                    delivery: controller.deliveries[index],
                  );
                },
              ),
              if (controller.loadingMore) ...[
                Center(
                  child: Padding(
                    padding: EdgeInsets.only(
                      bottom: Get.mediaQuery.viewPadding.bottom + 20,
                    ),
                    child: Center(
                      child: SpinKitThreeBounce(
                        color: CustomColors.orange,
                        size: 15,
                      ),
                    ),
                  ),
                ),
              ],
            ],
          ),
        );
      }),
    );
  }
}
