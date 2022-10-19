import 'package:delivery/controllers/home.dart';
import 'package:delivery/models/driver.dart';
import 'package:delivery/utils/colors.dart';
import 'package:delivery/utils/strings.dart';
import 'package:delivery/widgets/drawer.dart';
import 'package:delivery/widgets/home/blocked.dart';
import 'package:delivery/widgets/home/not_approved.dart';
import 'package:delivery/widgets/home/pending_list.dart';
import 'package:delivery/widgets/home/soon.dart';
import 'package:flutter/material.dart';
import 'package:get/get_state_manager/get_state_manager.dart';

class HomePage extends GetView<HomeController> {
  @override
  Widget build(BuildContext context) {
    return WillPopScope(
      onWillPop: () async => false,
      child: Scaffold(
        bottomSheet: Obx(() {
          if (!controller.connected) {
            return Container(
              width: double.infinity,
              padding: EdgeInsets.symmetric(vertical: 15),
              color: Colors.red,
              child: Text(
                'Sem conexão com a internet',
                textAlign: TextAlign.center,
                style: TextStyle(color: Colors.white),
              ),
            );
          }

          return SizedBox();
        }),
        key: controller.scaffoldKey,
        backgroundColor: Color(0xFFF7F7F7),
        appBar: AppBar(
          backgroundColor: CustomColors.white,
          title: Text(
            Strings.homeTitle,
            style: TextStyle(color: CustomColors.orange),
          ),
          centerTitle: true,
          automaticallyImplyLeading: false,
          leading: IconButton(
            icon: Icon(Icons.menu),
            color: Color(0xFF707070),
            onPressed: () {
              controller.scaffoldKey.currentState!.openDrawer();
            },
          ),
          actions: [
            Obx(() {
              if (controller.isPlaying) {
                return IconButton(
                  icon: Icon(Icons.volume_off),
                  color: Colors.red,
                  onPressed: controller.stop,
                );
              }

              return SizedBox();
            }),
          ],
        ),
        drawer: CustomDrawer(),
        body: Obx(() {
          if (!controller.authService.authenticated) {
            return SizedBox();
          }

          if (!controller.authService.driver!.city!.enabled) {
            return Soon();
          }

          if (controller.authService.driver!.banned ||
              controller.authService.driver!.status ==
                  DriverStatuses.REJECTED) {
            return Blocked();
          }

          if (controller.authService.driver!.status == DriverStatuses.PENDING) {
            return NotApproved();
          }

          return Container(
            height: MediaQuery.of(context).size.height - kToolbarHeight,
            child: Stack(
              children: [
                Padding(
                  padding: EdgeInsets.only(bottom: 30),
                  child: RefreshIndicator(
                    onRefresh: controller.refresh,
                    child: SingleChildScrollView(
                      physics: AlwaysScrollableScrollPhysics(),
                      child: PendingList(),
                    ),
                  ),
                ),
                Obx(() {
                  if (!controller.authService.authenticated ||
                      controller.authService.driver!.metadata.status ==
                          MetadataStatuses.OFFLINE ||
                      null == controller.pusherConnected) {
                    return SizedBox();
                  }

                  return Align(
                    alignment: Alignment.bottomCenter,
                    child: GestureDetector(
                      onTap: () {
                        if (controller.pusherConnected != 'DESCONECTADO')
                          return;
                        controller.retrySubscribeToDeliveries();
                      },
                      child: Container(
                        width: MediaQuery.of(context).size.width,
                        height: 30 + MediaQuery.of(context).viewPadding.bottom,
                        color: controller.pusherConnectionColor,
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Row(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                Text(
                                  controller.pusherConnected!,
                                  style: TextStyle(
                                    color: Colors.white,
                                  ),
                                ),
                                if (controller.pusherConnected ==
                                        'CONECTANDO...' ||
                                    controller.pusherConnected ==
                                        'RECONECTANDO...') ...[
                                  SizedBox(width: 15),
                                  SizedBox(
                                    width: 10,
                                    height: 10,
                                    child: CircularProgressIndicator(
                                      strokeWidth: 1,
                                      valueColor:
                                          AlwaysStoppedAnimation(Colors.white),
                                    ),
                                  ),
                                ],
                                if (controller.pusherConnected ==
                                    'DESCONECTADO') ...[
                                  SizedBox(width: 15),
                                  Text(
                                    'CLIQUE PARA RECONECTAR',
                                    style: TextStyle(
                                      color: Colors.white,
                                      fontWeight: FontWeight.bold,
                                    ),
                                  ),
                                ],
                              ],
                            ),
                            SizedBox(
                              height: MediaQuery.of(context).viewPadding.bottom,
                            ),
                          ],
                        ),
                      ),
                    ),
                  );
                }),
              ],
            ),
          );
        }),
      ),
    );
  }
}
