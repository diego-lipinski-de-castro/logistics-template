import 'package:delivery/controllers/register_confirm.dart';
import 'package:delivery/utils/colors.dart';
import 'package:delivery/utils/strings.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_spinkit/flutter_spinkit.dart';
import 'package:get/get.dart';

class RegisterConfirmPage extends GetView<RegisterConfirmController> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SingleChildScrollView(
        child: Stack(
          children: [
            Container(
              color: CustomColors.orange,
              height: MediaQuery.of(context).size.height / 2.5,
            ),
            SafeArea(
              child: Padding(
                padding: EdgeInsets.only(top: 15),
                child: BackButton(
                  onPressed: () {
                    Get.back();
                  },
                  color: CustomColors.white,
                ),
              ),
            ),
            SafeArea(
              child: Padding(
                padding: EdgeInsets.only(top: 15),
                child: BackButton(
                  color: CustomColors.white,
                ),
              ),
            ),
            Column(
              children: [
                SafeArea(child: SizedBox()),
                SizedBox(
                  height: 25,
                ),
                Padding(
                  padding: EdgeInsets.symmetric(horizontal: 25),
                  child: Text(
                    Strings.registerConfirmTitle,
                    style: TextStyle(
                      color: CustomColors.white,
                      fontWeight: FontWeight.bold,
                      fontSize: 24,
                    ),
                    textAlign: TextAlign.center,
                  ),
                ),
                SizedBox(
                  height: 25,
                ),
                Padding(
                  padding: EdgeInsets.symmetric(horizontal: 25),
                  child: Text(
                    Strings.registerConfirmSubtitle,
                    style: TextStyle(
                      color: CustomColors.white,
                      fontSize: 18,
                    ),
                    textAlign: TextAlign.center,
                  ),
                ),
                SizedBox(
                  height: 25,
                ),
                Container(
                  margin: EdgeInsets.only(
                    left: 20,
                    right: 20,
                  ),
                  padding: EdgeInsets.symmetric(
                    vertical: 25,
                    horizontal: 20,
                  ),
                  decoration: BoxDecoration(
                    color: CustomColors.white,
                    borderRadius: BorderRadius.circular(10),
                    boxShadow: [
                      BoxShadow(
                        color: CustomColors.black.withOpacity(0.1),
                        offset: Offset(0, 5),
                        blurRadius: 15.0,
                        spreadRadius: 0.0,
                      ),
                    ],
                  ),
                  child: Column(
                    children: [
                      Form(
                        key: controller.formKey,
                        child: TextFormField(
                          controller: controller.codeField,
                          onEditingComplete: controller.submit,
                          validator: (value) {
                            if (value!.isEmpty) {
                              return 'Insira o código que você recebeu via SMS';
                            }

                            return null;
                          },
                          keyboardType: TextInputType.number,
                          inputFormatters: [
                            FilteringTextInputFormatter.digitsOnly,
                          ],
                          maxLength: 6,
                          decoration: InputDecoration(
                            floatingLabelBehavior: FloatingLabelBehavior.always,
                            labelText: controller.phoneNumber,
                            helperText: 'Código SMS',
                            labelStyle: TextStyle(
                              color: CustomColors.black.withOpacity(0.5),
                              fontSize: 18,
                            ),
                            border: OutlineInputBorder(),
                            enabledBorder: OutlineInputBorder(
                              borderSide: BorderSide(
                                color: CustomColors.black.withOpacity(0.5),
                              ),
                            ),
                            focusedBorder: OutlineInputBorder(
                              borderSide: BorderSide(
                                color: CustomColors.black.withOpacity(0.5),
                              ),
                            ),
                          ),
                        ),
                      ),
                      SizedBox(
                        height: 25,
                      ),
                      Obx(
                        () => FlatButton(
                          onPressed:
                              controller.loading ? null : controller.submit,
                          minWidth: double.infinity,
                          shape: StadiumBorder(),
                          height: 50,
                          color: CustomColors.orange,
                          disabledColor: CustomColors.orange.withOpacity(0.5),
                          child: controller.loading
                              ? SpinKitThreeBounce(
                                  color: Colors.white,
                                  size: 15,
                                )
                              : Text(
                                  Strings.registerConfirmSubmit,
                                  style: TextStyle(
                                    color: CustomColors.white,
                                    fontWeight: FontWeight.bold,
                                    fontSize: 18,
                                  ),
                                ),
                        ),
                      ),
                    ],
                  ),
                ),
                SafeArea(child: SizedBox()),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
