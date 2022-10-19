import 'dart:io';
import 'dart:typed_data';

import 'package:delivery/models/driver.dart';
import 'package:delivery/services/http.dart';
import 'package:delivery/utils/config.dart';
import 'package:delivery/utils/utils.dart';
import 'package:flutter/services.dart';
import 'package:get/get.dart';
import 'package:path/path.dart';
import 'package:path_provider/path_provider.dart';

class ProfileRepository {
  final HttpService httpProvider;

  ProfileRepository({required this.httpProvider});

  Future<Driver?> profile() async {
    try {
      final response = await httpProvider.get(
        '/api/driver/profile',
      );

      if (!response.isOk) {
        Utils.displayHttpError(response);
        return null;
      }

      return Driver.fromMap(response.body['driver']);
    } catch (e, stack) {
      Utils.report(e, stack, 'ProfileRepository.profile');

      return null;
    }
  }

  Future<void> updatePix(String pix) async {
    try {
      final response = await httpProvider.post(
        '/api/driver/profile/pix',
        {
          'pix': pix,
        },
      );

      if (!response.isOk) {
        Utils.displayHttpError(response);
        return;
      }

      return;
    } catch (e, stack) {
      Utils.report(e, stack, 'ProfileRepository.updatePix');

      return;
    }
  }

  Future<bool> storeDocuments(FormData data) async {
    try {
      final response = await httpProvider.post(
        '/api/driver/profile/documents',
        data,
      );

      if (!response.isOk) {
        Utils.displayHttpError(response);
        return false;
      }

      return true;
    } catch (e, stack) {
      Utils.report(e, stack, 'ProfileRepository.storeDocuments');

      return false;
    }
  }

  Future<File?> getDocument(Document document) async {
    try {
      final path =
          '${Config.BASE_URL}/api/driver/profile/documents/${document.id}';

      final documentDirectory = await getApplicationDocumentsDirectory();

      final ByteData data = await NetworkAssetBundle(
        Uri.parse(path),
      ).load(path);

      final Int8List bytes = data.buffer.asInt8List();

      final file = File(
        join(documentDirectory.path, '${document.name}.${document.type}'),
      );

      file.writeAsBytes(bytes);

      return file;
    } catch (e, stack) {
      Utils.report(e, stack, 'ProfileRepository.getDocument');

      return null;
    }
  }
}
