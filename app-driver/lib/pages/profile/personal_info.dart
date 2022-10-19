import 'package:delivery/controllers/profile.dart';
import 'package:delivery/utils/colors.dart';
import 'package:delivery/utils/strings.dart';
import 'package:delivery/widgets/drawer.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';

class ProfilePersonalInfoPage extends GetView<ProfileController> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: CustomColors.white,
        leading: BackButton(
          color: CustomColors.orange,
        ),
        title: Text(
          Strings.profilePersonalInfoTitle,
          style: TextStyle(color: CustomColors.orange),
        ),
        centerTitle: true,
      ),
      drawer: CustomDrawer(),
      body: SingleChildScrollView(
        padding: EdgeInsets.all(25),
        child: Form(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              field(
                'Primeiro nome',
                controller.authService.driver!.firstName,
              ),
              SizedBox(height: 15),
              field(
                'Último nome',
                controller.authService.driver!.lastName,
              ),
              SizedBox(height: 15),
              field(
                'Telefone',
                controller.authService.driver!.formattedPhone,
              ),
              SizedBox(height: 15),
              field(
                'E-mail',
                controller.authService.driver!.email,
              ),
              SizedBox(height: 15),
              field(
                'CPF',
                controller.authService.driver!.cpf,
              ),
              SizedBox(height: 15),
              field(
                'CNH',
                controller.authService.driver!.cnh,
              ),
              SizedBox(height: 15),
              field(
                'Data de nascimento',
                controller.authService.driver!.birthday,
              ),
              SizedBox(height: 30),
              field(
                'Pix',
                controller.authService.driver!.pix,
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget field(label, [value = '-']) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: TextStyle(
            fontSize: 16,
            color: Colors.grey,
          ),
        ),
        SizedBox(height: 2),
        Text(
          value ?? '-',
          style: TextStyle(
            fontSize: 16,
          ),
        ),
      ],
    );
  }
}
