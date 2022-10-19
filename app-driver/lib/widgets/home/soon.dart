import 'package:delivery/controllers/home.dart';
import 'package:delivery/utils/colors.dart';
import 'package:delivery/utils/utils.dart';
import 'package:flutter/material.dart';
import 'package:get/get_state_manager/get_state_manager.dart';

class Soon extends GetWidget<HomeController> {
  const Soon({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: EdgeInsets.all(20),
      child: Column(
        children: [
          SizedBox(
            height: MediaQuery.of(context).size.height / 8,
          ),
          Container(
            padding: EdgeInsets.all(25),
            decoration: BoxDecoration(
              color: Colors.grey,
              borderRadius: BorderRadius.circular(100),
            ),
            child: Icon(
              Icons.location_on_outlined,
              color: CustomColors.white,
              size: 96,
            ),
          ),
          SizedBox(height: 20),
          Text(
            'Ainda não estamos na sua cidade.',
            style: TextStyle(
              color: CustomColors.darkGrey,
              fontSize: 18,
            ),
            textAlign: TextAlign.center,
          ),
          SizedBox(height: 5),
          Text(
            'Entraremos em contato com você assim que iniciarmos a operação na sua região!',
            style: TextStyle(
              color: CustomColors.darkGrey,
              fontSize: 18,
              fontWeight: FontWeight.bold,
            ),
            textAlign: TextAlign.center,
          ),
          SizedBox(height: 20),
          Text(
            'Em caso de dúvidas fale conosco',
            style: TextStyle(
              color: CustomColors.darkGrey,
              fontSize: 18,
            ),
            textAlign: TextAlign.center,
          ),
          SizedBox(height: 10),
          Container(
            width: double.infinity,
            margin: EdgeInsets.symmetric(horizontal: 20),
            child: FlatButton(
              onPressed: Utils.openWhatsapp,
              height: 50,
              color: CustomColors.green,
              shape: StadiumBorder(),
              padding: EdgeInsets.symmetric(
                vertical: 10,
                horizontal: 20,
              ),
              child: Text(
                'Entrar em contato com o suporte',
                textAlign: TextAlign.center,
                style: TextStyle(
                  color: CustomColors.white,
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}
