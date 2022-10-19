import 'package:delivery/controllers/profile.dart';
import 'package:delivery/utils/colors.dart';
import 'package:delivery/utils/strings.dart';
import 'package:delivery/widgets/drawer.dart';
import 'package:delivery/widgets/upload.dart';
import 'package:flutter/material.dart';
import 'package:flutter_spinkit/flutter_spinkit.dart';
import 'package:get/get.dart';

class ProfileDocumentsPage extends GetView<ProfileController> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: CustomColors.white,
        leading: BackButton(
          color: CustomColors.orange,
        ),
        title: Text(
          Strings.profileDocumentsTitle,
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
              Obx(() {
                return UploadWidget(
                  key: UniqueKey(),
                  submitLabel: 'Enviar foto de perfil',
                  label: 'Foto perfil',
                  file: controller.getFile('profile-picture'),
                  document: controller.getDocumentByFolder('profile-picture'),
                  onFileSelected: (file) {
                    controller.addFile('profile-picture', file);
                  },
                  onRemove: () {
                    controller.removeFile('profile-picture');
                  },
                );
              }),
              SizedBox(height: 15),
              Obx(() {
                return UploadWidget(
                  key: UniqueKey(),
                  submitLabel: 'Enviar RG - frente',
                  label: 'Foto RG - frente',
                  file: controller.getFile('rg-front'),
                  document: controller.getDocumentByFolder('rg-front'),
                  onFileSelected: (file) {
                    controller.addFile('rg-front', file);
                  },
                  onRemove: () {
                    controller.removeFile('rg-front');
                  },
                );
              }),
              SizedBox(height: 15),
              Obx(() {
                return UploadWidget(
                  key: UniqueKey(),
                  submitLabel: 'Enviar RG - verso',
                  label: 'Foto RG - verso',
                  file: controller.getFile('rg-back'),
                  document: controller.getDocumentByFolder('rg-back'),
                  onFileSelected: (file) {
                    controller.addFile('rg-back', file);
                  },
                  onRemove: () {
                    controller.removeFile('rg-back');
                  },
                );
              }),
              SizedBox(height: 15),
              Obx(() {
                return UploadWidget(
                  key: UniqueKey(),
                  submitLabel: 'Enviar CNH - frente',
                  label: 'Foto CNH - frente',
                  file: controller.getFile('cnh-front'),
                  document: controller.getDocumentByFolder('cnh-front'),
                  onFileSelected: (file) {
                    controller.addFile('cnh-front', file);
                  },
                  onRemove: () {
                    controller.removeFile('cnh-front');
                  },
                );
              }),
              SizedBox(height: 15),
              Obx(() {
                return UploadWidget(
                  key: UniqueKey(),
                  submitLabel: 'Enviar CNH - verso',
                  label: 'Foto CNH - verso',
                  file: controller.getFile('cnh-back'),
                  document: controller.getDocumentByFolder('cnh-back'),
                  onFileSelected: (file) {
                    controller.addFile('cnh-back', file);
                  },
                  onRemove: () {
                    controller.removeFile('cnh-back');
                  },
                );
              }),
              SizedBox(height: 15),
              Obx(() {
                return UploadWidget(
                  key: UniqueKey(),
                  submitLabel: 'Enviar documento veículo',
                  label: 'Foto documento veículo',
                  file: controller.getFile('vehicle-doc'),
                  document: controller.getDocumentByFolder('vehicle-doc'),
                  onFileSelected: (file) {
                    controller.addFile('vehicle-doc', file);
                  },
                  onRemove: () {
                    controller.removeFile('vehicle-doc');
                  },
                );
              }),
              SizedBox(height: 30),
              Obx(() {
                return FlatButton(
                  onPressed:
                      controller.loading ? null : controller.submitDocuments,
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
        ),
      ),
    );
  }
}
