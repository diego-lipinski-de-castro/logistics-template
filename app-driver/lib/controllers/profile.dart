import 'dart:io';
import 'package:delivery/models/driver.dart';
import 'package:delivery/repositories/profile.dart';
import 'package:delivery/services/auth.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';

class ProfileController extends GetxController {
  final AuthService authService;
  final ProfileRepository profileRepository;

  final _files = Rx<List<MapEntry<String, File>>>([]);
  List<MapEntry<String, File>> get files => _files.value;

  final _loading = RxBool(false);
  bool get loading => _loading.value;

  ProfileController({
    required this.authService,
    required this.profileRepository,
  });

  Document? getDocumentByFolder(folder) {
    return authService.driver!.documents
        .firstWhereOrNull((document) => document.folder == folder);
  }

  File? getFile(key) {
    return files.firstWhereOrNull((entry) => entry.key == key)?.value;
  }

  void addFile(key, file) {
    _files.value.add(MapEntry(key, file));

    _files.refresh();
  }

  void removeFile(key) {
    _files.value.removeWhere((entry) => entry.key == key);

    _files.refresh();
  }

  void submitDocuments() async {
    if (loading) return;

    _loading.value = true;

    final FormData data = FormData({});

    files.forEach((file) {
      data.files.add(
        MapEntry(
          file.key,
          MultipartFile(
            file.value,
            filename: file.value.path.split('/').last,
          ),
        ),
      );
    });

    Get.dialog(
      AlertDialog(
        title: Text('Aguarde...'),
        content: Text(
          'Esse processo pode levar até 2 minutos.',
        ),
      ),
    );

    await profileRepository.storeDocuments(data);

    Get.back();

    _loading.value = false;
  }
}
