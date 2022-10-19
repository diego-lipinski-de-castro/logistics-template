import 'package:delivery/controllers/profile.dart';
import 'package:delivery/utils/colors.dart';
import 'package:delivery/utils/routes.dart';
import 'package:delivery/utils/strings.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';

class ProfilePage extends GetView<ProfileController> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: CustomColors.white,
        leading: BackButton(
          color: CustomColors.orange,
        ),
        title: Text(
          Strings.profileTitle,
          style: TextStyle(color: CustomColors.orange),
        ),
        centerTitle: true,
      ),
      body: SingleChildScrollView(
        padding: EdgeInsets.all(25),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            FlatButton(
              onPressed: () {
                Get.toNamed(Routes.profilePersonalInfo);
              },
              minWidth: double.infinity,
              shape: StadiumBorder(),
              height: 50,
              color: CustomColors.orange,
              disabledColor: CustomColors.orange.withOpacity(0.5),
              child: Text(
                Strings.profilePersonalInfoTitle,
                style: TextStyle(
                  color: CustomColors.white,
                  fontWeight: FontWeight.bold,
                  fontSize: 18,
                ),
              ),
            ),
            SizedBox(height: 15),
            FlatButton(
              onPressed: () {
                Get.toNamed(Routes.profileDocuments);
              },
              minWidth: double.infinity,
              shape: StadiumBorder(),
              height: 50,
              color: CustomColors.orange,
              disabledColor: CustomColors.orange.withOpacity(0.5),
              child: Text(
                Strings.profileDocumentsTitle,
                style: TextStyle(
                  color: CustomColors.white,
                  fontWeight: FontWeight.bold,
                  fontSize: 18,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
