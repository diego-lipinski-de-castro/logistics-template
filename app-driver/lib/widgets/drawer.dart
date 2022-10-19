import 'package:delivery/controllers/home.dart';
import 'package:delivery/main.dart';
import 'package:delivery/models/driver.dart';
import 'package:delivery/utils/colors.dart';
import 'package:delivery/utils/routes.dart';
import 'package:delivery/utils/strings.dart';
import 'package:delivery/utils/utils.dart';
import 'package:delivery/widgets/switch.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';

class CustomDrawer extends GetWidget<HomeController> {
  @override
  Widget build(BuildContext context) {
    return Drawer(
      child: SingleChildScrollView(
        child: Container(
          child: Column(
            children: [
              SafeArea(child: SizedBox()),
              Container(
                width: double.infinity,
                padding: EdgeInsets.only(
                  top: 20,
                  left: 20,
                ),
                decoration: BoxDecoration(
                  color: Color(0xFFEAEAEA),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    if (controller.authService.authenticated) ...[
                      Text(
                        controller.authService.driver!.fullName,
                        style: TextStyle(
                          color: CustomColors.orange,
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      Text(
                        controller.authService.driver!.formattedPhone,
                        style: TextStyle(
                          color: Color(0xFF7C7C7C),
                          fontSize: 14,
                        ),
                      ),
                      SizedBox(height: 25),
                      Container(
                        width: double.infinity,
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.end,
                          children: [
                            InkWell(
                              onTap: controller.logout,
                              child: Padding(
                                padding: EdgeInsets.symmetric(
                                  vertical: 10,
                                  horizontal: 20,
                                ),
                                child: Text(
                                  'Sair',
                                  style: TextStyle(
                                    color: Color(0xFF7C7C7C),
                                    fontSize: 14,
                                  ),
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ],
                ),
              ),
              ListTile(
                leading: Icon(Icons.local_shipping),
                title: Transform(
                  transform: Matrix4.translationValues(-16, 0, 0),
                  child: Text(
                    Strings.homeTitle,
                    style: TextStyle(
                      fontSize: 16,
                    ),
                  ),
                ),
                onTap: () {
                  if (Get.currentRoute == Routes.home) {
                    controller.scaffoldKey.currentState!.openEndDrawer();
                  } else {
                    Get.toNamed(Routes.home);
                  }
                },
              ),
              ListTile(
                leading: Icon(Icons.history),
                title: Transform(
                  transform: Matrix4.translationValues(-16, 0, 0),
                  child: Text(
                    Strings.todayTitle,
                    style: TextStyle(
                      fontSize: 16,
                    ),
                  ),
                ),
                onTap: () {
                  Get.toNamed(Routes.todays);
                },
              ),
              ListTile(
                leading: Icon(Icons.history),
                title: Transform(
                  transform: Matrix4.translationValues(-16, 0, 0),
                  child: Text(
                    Strings.historyTitle,
                    style: TextStyle(
                      fontSize: 16,
                    ),
                  ),
                ),
                onTap: () {
                  Get.toNamed(Routes.history);
                },
              ),
              ListTile(
                leading: Icon(Icons.account_circle),
                title: Transform(
                  transform: Matrix4.translationValues(-16, 0, 0),
                  child: Text(
                    Strings.profileTitle,
                    style: TextStyle(
                      fontSize: 16,
                    ),
                  ),
                ),
                onTap: () {
                  Get.toNamed(Routes.profile);
                },
              ),
              ListTile(
                leading: Icon(Icons.notifications),
                title: Transform(
                  transform: Matrix4.translationValues(-16, 0, 0),
                  child: Text(
                    'Ajuda e suporte',
                    style: TextStyle(
                      fontSize: 16,
                    ),
                  ),
                ),
                onTap: Utils.openWhatsapp,
              ),
              SizedBox(height: 50),
              Container(
                child: Column(
                  children: [
                    Text(
                      'Disponibilidade para entregas',
                      style: TextStyle(
                        color: CustomColors.darkGrey,
                      ),
                    ),
                    SizedBox(height: 15),
                    Obx(() {
                      if (controller.authService.driver == null) {
                        return SizedBox();
                      }

                      return CustomSwitch(
                        value: controller.authService.driver!.metadata.status !=
                            MetadataStatuses.OFFLINE,
                        onTap: controller.toggleStatus,
                        enabled: controller.authService.driver!.city!.enabled,
                      );
                    }),
                  ],
                ),
              ),
              SizedBox(height: 50),
              ListTile(
                title: Text(
                  '${Strings.appName} | v${info.version}',
                  style: TextStyle(
                    color: CustomColors.darkGrey,
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
