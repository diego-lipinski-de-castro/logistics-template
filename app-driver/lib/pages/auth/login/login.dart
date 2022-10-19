import 'package:brasil_fields/brasil_fields.dart';
import 'package:delivery/controllers/login.dart';
import 'package:delivery/utils/colors.dart';
import 'package:delivery/utils/routes.dart';
import 'package:delivery/utils/strings.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_spinkit/flutter_spinkit.dart';
import 'package:get/get.dart';
import 'package:url_launcher/url_launcher_string.dart';

class LoginPage extends GetView<LoginController> {
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
            Column(
              children: [
                SafeArea(child: SizedBox()),
                SizedBox(
                  height: 25,
                ),
                Padding(
                  padding: EdgeInsets.symmetric(horizontal: 25),
                  child: Text(
                    Strings.loginTitle,
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
                    Strings.loginSubtitle,
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
                      Padding(
                        padding: EdgeInsets.symmetric(
                          vertical: 0,
                          horizontal: 0,
                        ),
                        child: Image.asset(
                          'assets/img/logo-app.png',
                          width: 180,
                        ),
                      ),
                      Text(
                        Strings.appSubtitle,
                        style: TextStyle(
                          color: CustomColors.orange,
                          fontStyle: FontStyle.italic,
                          fontSize: 18,
                        ),
                      ),
                      SizedBox(
                        height: 25,
                      ),
                      Form(
                        key: controller.formKey,
                        child: TextFormField(
                          controller: controller.phoneField,
                          onEditingComplete: controller.submit,
                          validator: (value) {
                            if (value!.isEmpty) {
                              return 'Insira um número de telefone';
                            }

                            return null;
                          },
                          keyboardType: TextInputType.number,
                          inputFormatters: <TextInputFormatter>[
                            FilteringTextInputFormatter.digitsOnly,
                            TelefoneInputFormatter(),
                          ],
                          decoration: InputDecoration(
                            labelText: 'Número de telefone',
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
                                  Strings.loginSubmit,
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
                SizedBox(
                  height: 50,
                ),
                Container(
                  margin: EdgeInsets.symmetric(horizontal: 20),
                  child: FlatButton(
                    onPressed: () {
                      Get.toNamed(Routes.register);
                    },
                    minWidth: double.infinity,
                    shape: StadiumBorder(),
                    height: 50,
                    color: CustomColors.green,
                    child: Text(
                      Strings.loginRegister,
                      style: TextStyle(
                        color: CustomColors.white,
                        fontWeight: FontWeight.bold,
                        fontStyle: FontStyle.italic,
                        fontSize: 18,
                      ),
                    ),
                  ),
                ),
                SizedBox(
                  height: 50,
                ),
                Padding(
                  padding: EdgeInsets.symmetric(horizontal: 25),
                  child: Text(
                    Strings.loginHelp,
                    style: TextStyle(
                      color: CustomColors.black.withOpacity(0.5),
                      fontSize: 16,
                    ),
                    textAlign: TextAlign.center,
                  ),
                ),
                SizedBox(
                  height: 50,
                ),
                GestureDetector(
                  onTap: () async {
                    await launchUrlString(
                      'https://speedapp.com.br/contrato-de-prestacao-de-servicos-speedapp-para-entregadores/',
                    );
                  },
                  child: Container(
                    color: Colors.transparent,
                    child: Text(
                      Strings.loginTerms,
                      style: TextStyle(
                        color: CustomColors.black.withOpacity(0.75),
                        fontSize: 14,
                        decoration: TextDecoration.underline,
                      ),
                      textAlign: TextAlign.center,
                    ),
                  ),
                ),
                SizedBox(
                  height: 25,
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
