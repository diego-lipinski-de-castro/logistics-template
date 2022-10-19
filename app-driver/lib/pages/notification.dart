import 'package:delivery/controllers/notification.dart';
import 'package:delivery/utils/colors.dart';
import 'package:delivery/utils/strings.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';

class NotificationPage extends GetView<NotificationController> {
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
                    'assets/img/notificacoes.png',
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
                    'Permitir notificações',
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
                    'Habilite as notificações para garantir que você irá receber um alerta quando tiver novas entregas disponíveis.',
                    textAlign: TextAlign.center,
                    style: TextStyle(
                      fontSize: 18,
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
