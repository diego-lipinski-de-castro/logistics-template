import 'package:brasil_fields/brasil_fields.dart';
import 'package:delivery/controllers/register_finish.dart';
import 'package:delivery/utils/colors.dart';
import 'package:delivery/utils/strings.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:get/get.dart';
import 'package:url_launcher/url_launcher.dart';
import 'package:flutter_spinkit/flutter_spinkit.dart';

class RegisterFinishPage extends GetView<RegisterFinishController> {
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
                  onPressed: controller.logout,
                  color: CustomColors.white,
                ),
              ),
            ),
            Form(
              key: controller.formKey,
              child: Column(
                children: [
                  SafeArea(child: SizedBox()),
                  SizedBox(
                    height: 25,
                  ),
                  Padding(
                    padding: EdgeInsets.symmetric(horizontal: 25),
                    child: Text(
                      Strings.registerFinalTitle,
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
                      Strings.registerFinalSubtitle,
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
                  Obx(() {
                    if (controller.loadingStates) {
                      return Padding(
                        padding: const EdgeInsets.only(top: 25.0),
                        child: Center(
                          child: CircularProgressIndicator(
                            strokeWidth: 1,
                            valueColor: AlwaysStoppedAnimation(Colors.white),
                          ),
                        ),
                      );
                    }

                    return Container(
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
                          TextFormField(
                            readOnly: true,
                            initialValue: controller.phoneField.text,
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
                              errorStyle: TextStyle(
                                color: Colors.red,
                                fontSize: 16,
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
                              disabledBorder: OutlineInputBorder(
                                borderSide: BorderSide(
                                  color: CustomColors.black.withOpacity(0.5),
                                ),
                              ),
                            ),
                          ),
                          SizedBox(
                            height: 15,
                          ),
                          Obx(() {
                            if (null == controller.states) {
                              return SizedBox();
                            }

                            return DropdownButtonFormField<String>(
                              validator: (String? value) {
                                if (null == value) {
                                  return 'Campo obrigatório';
                                }

                                return null;
                              },
                              value: controller.selectedState,
                              onChanged: (String? value) {
                                controller.setState(value);
                              },
                              items: controller.states.map((value) {
                                return DropdownMenuItem<String>(
                                  value: value.id.toString(),
                                  child: Text(value.name),
                                );
                              }).toList(),
                              decoration: InputDecoration(
                                labelText: 'Estado',
                                labelStyle: TextStyle(
                                  color: CustomColors.black.withOpacity(0.5),
                                  fontSize: 18,
                                ),
                                errorStyle: TextStyle(
                                  color: Colors.red,
                                  fontSize: 16,
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
                            );
                          }),
                          SizedBox(
                            height: 15,
                          ),
                          Obx(() {
                            if (null == controller.cities) {
                              return SizedBox();
                            }

                            return DropdownButtonFormField<String>(
                              validator: (String? value) {
                                if (null == value) {
                                  return 'Campo obrigatório';
                                }

                                return null;
                              },
                              value: controller.selectedCity,
                              onChanged: (String? value) {
                                controller.setCity(value);
                              },
                              items: controller.cities.map((value) {
                                return DropdownMenuItem<String>(
                                  value: value.id.toString(),
                                  child: Text(value.name),
                                );
                              }).toList(),
                              decoration: InputDecoration(
                                labelText: 'Cidade',
                                labelStyle: TextStyle(
                                  color: CustomColors.black.withOpacity(0.5),
                                  fontSize: 18,
                                ),
                                errorStyle: TextStyle(
                                  color: Colors.red,
                                  fontSize: 16,
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
                            );
                          }),
                          SizedBox(
                            height: 15,
                          ),
                          TextFormField(
                            controller: controller.firstName,
                            validator: (String? value) {
                              if (value!.isEmpty) {
                                return 'Campo obrigatório';
                              }

                              return null;
                            },
                            decoration: InputDecoration(
                              labelText: 'Primeiro nome',
                              labelStyle: TextStyle(
                                color: CustomColors.black.withOpacity(0.5),
                                fontSize: 18,
                              ),
                              errorStyle: TextStyle(
                                color: Colors.red,
                                fontSize: 16,
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
                          SizedBox(
                            height: 15,
                          ),
                          TextFormField(
                            controller: controller.lastName,
                            validator: (String? value) {
                              if (value!.isEmpty) {
                                return 'Campo obrigatório';
                              }

                              return null;
                            },
                            decoration: InputDecoration(
                              labelText: 'Último nome',
                              labelStyle: TextStyle(
                                color: CustomColors.black.withOpacity(0.5),
                                fontSize: 18,
                              ),
                              errorStyle: TextStyle(
                                color: Colors.red,
                                fontSize: 16,
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
                          SizedBox(
                            height: 15,
                          ),
                          TextFormField(
                            controller: controller.birthdayField,
                            keyboardType: TextInputType.number,
                            inputFormatters: <TextInputFormatter>[
                              FilteringTextInputFormatter.digitsOnly,
                              DataInputFormatter(),
                            ],
                            validator: (String? value) {
                              if (value!.isEmpty) {
                                return 'Campo obrigatório';
                              }

                              return null;
                            },
                            decoration: InputDecoration(
                              labelText: 'Data de nascimento',
                              labelStyle: TextStyle(
                                color: CustomColors.black.withOpacity(0.5),
                                fontSize: 18,
                              ),
                              errorStyle: TextStyle(
                                color: Colors.red,
                                fontSize: 16,
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
                          SizedBox(
                            height: 15,
                          ),
                          TextFormField(
                            controller: controller.cpfField,
                            keyboardType: TextInputType.number,
                            inputFormatters: <TextInputFormatter>[
                              FilteringTextInputFormatter.digitsOnly,
                              CpfInputFormatter(),
                            ],
                            validator: (String? value) {
                              if (value!.isEmpty) {
                                return 'Campo obrigatório';
                              }

                              return null;
                            },
                            decoration: InputDecoration(
                              labelText: 'CPF',
                              labelStyle: TextStyle(
                                color: CustomColors.black.withOpacity(0.5),
                                fontSize: 18,
                              ),
                              errorStyle: TextStyle(
                                color: Colors.red,
                                fontSize: 16,
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
                          SizedBox(
                            height: 15,
                          ),
                          TextFormField(
                            controller: controller.cnhField,
                            keyboardType: TextInputType.number,
                            inputFormatters: <TextInputFormatter>[
                              FilteringTextInputFormatter.digitsOnly,
                            ],
                            decoration: InputDecoration(
                              labelText: 'CNH (opcional)',
                              labelStyle: TextStyle(
                                color: CustomColors.black.withOpacity(0.5),
                                fontSize: 18,
                              ),
                              errorStyle: TextStyle(
                                color: Colors.red,
                                fontSize: 16,
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
                          SizedBox(
                            height: 25,
                          ),
                          Obx(() {
                            return Row(
                              children: [
                                Checkbox(
                                  activeColor: CustomColors.orange,
                                  value: controller.accepted,
                                  onChanged: (v) {
                                    controller.toggleAccept();
                                  },
                                ),
                                Flexible(
                                  child: GestureDetector(
                                    onTap: () async {
                                      await launch(
                                        'https://speedapp.com.br/contrato-de-prestacao-de-servicos-speedapp-para-entregadores/',
                                      );
                                    },
                                    child: Text(
                                      'Li e aceito os Termos de uso e Política de privacidade',
                                      style: TextStyle(
                                        color: CustomColors.black
                                            .withOpacity(0.75),
                                        fontSize: 14,
                                        decoration: TextDecoration.underline,
                                      ),
                                    ),
                                  ),
                                ),
                              ],
                            );
                          }),
                          SizedBox(
                            height: 25,
                          ),
                          Obx(() {
                            return FlatButton(
                              onPressed:
                                  controller.loading ? null : controller.submit,
                              minWidth: double.infinity,
                              shape: StadiumBorder(),
                              height: 50,
                              color: CustomColors.orange,
                              disabledColor:
                                  CustomColors.orange.withOpacity(0.5),
                              child: controller.loading
                                  ? SpinKitThreeBounce(
                                      color: Colors.white,
                                      size: 15,
                                    )
                                  : Text(
                                      Strings.registerFinalSubmit,
                                      style: TextStyle(
                                        color: CustomColors.white,
                                        fontWeight: FontWeight.bold,
                                        fontSize: 18,
                                      ),
                                    ),
                            );
                          }),
                        ],
                      ),
                    );
                  }),
                  SizedBox(
                    height: 25,
                  ),
                  SafeArea(child: SizedBox()),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
