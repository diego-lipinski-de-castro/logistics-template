import 'package:delivery/controllers/permission.dart';
import 'package:delivery/utils/colors.dart';
import 'package:delivery/utils/strings.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:url_launcher/url_launcher_string.dart';

class PermissionPage extends GetView<PermissionController> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SingleChildScrollView(
        padding: EdgeInsets.all(25),
        child: Stack(
          children: [
            Column(
              children: [
                SafeArea(child: SizedBox()),
                SizedBox(
                  height: 25,
                ),
                Image.asset(
                  'assets/img/logo-app.png',
                  width: 180,
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
                Padding(
                  padding: EdgeInsets.zero,
                  child: Image.asset(
                    'assets/img/localizacao.png',
                    width: 150,
                    height: 150,
                  ),
                ),
                SizedBox(
                  height: 50,
                ),
                Padding(
                  padding: EdgeInsets.symmetric(horizontal: 25),
                  child: Text(
                    'Permitir acesso à localização',
                    textAlign: TextAlign.center,
                    style: TextStyle(
                      fontSize: 20,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),
                SizedBox(
                  height: 15,
                ),
                Padding(
                  padding: EdgeInsets.symmetric(horizontal: 25),
                  child: Text(
                    'Este aplicativo coleta dados de localização em segundo plano para verificar localização atual do entregador, disponibilizar entregas, e rastrear percursos, mesmo quando o aplicativo está fechado ou não está em uso.',
                    textAlign: TextAlign.center,
                    style: TextStyle(
                      fontSize: 18,
                    ),
                  ),
                ),
                SizedBox(
                  height: 15,
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
                      'Termos de uso e Política de privacidade',
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
                  height: 50,
                ),
                Container(
                  margin: EdgeInsets.symmetric(horizontal: 25),
                  child: FlatButton(
                    onPressed: controller.handle,
                    minWidth: double.infinity,
                    shape: StadiumBorder(),
                    height: 50,
                    color: CustomColors.orange,
                    disabledColor: CustomColors.orange.withOpacity(0.5),
                    child: Text(
                      'Permitir',
                      style: TextStyle(
                        color: CustomColors.white,
                        fontWeight: FontWeight.bold,
                        fontSize: 18,
                      ),
                    ),
                  ),
                ),
                SizedBox(
                  height: 15,
                ),
                Container(
                  margin: EdgeInsets.symmetric(horizontal: 25),
                  child: FlatButton(
                    onPressed: controller.ignore,
                    minWidth: double.infinity,
                    shape: StadiumBorder(),
                    height: 50,
                    color: Colors.transparent,
                    child: Text(
                      'Pular',
                      style: TextStyle(
                        color: CustomColors.orange,
                        fontStyle: FontStyle.italic,
                        fontWeight: FontWeight.w300,
                        fontSize: 18,
                      ),
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
