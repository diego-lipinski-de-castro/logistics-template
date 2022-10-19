import 'dart:io';
import 'package:delivery/models/settings.dart';
import 'package:delivery/utils/config.dart';
import 'package:get/get.dart';
import 'package:url_launcher/url_launcher_string.dart';

class Utils {
  static Future<void> openWhatsapp() async {
    await launchUrlString(
      'https://api.whatsapp.com/send?phone=${Settings.whatsappNumber}',
    );
  }

  static Future<bool> checkConnection() async {
    try {
      final result = await InternetAddress.lookup('example.com').timeout(
        Duration(seconds: 6),
      );

      if (result.isNotEmpty && result[0].rawAddress.isNotEmpty) {
        return true;
      } else {
        return false;
      }
    } on SocketException catch (_) {
      return false;
    }
  }

  static log([List<dynamic> args = const []]) {
    for (dynamic args in args) {
      print(args);
    }
  }

  static report(dynamic error, dynamic stack, [dynamic hint]) {
    if (Config.ENABLE_ERROR_LOGGING) {
      print(hint);
      print(error);
    }
  }

  static displayHttpError(Response response) {
    if (null == response.statusCode || response.statusCode! >= 500) return;

    if (null == response.body) return;

    try {
      Map data = response.body;

      print(data);

      String? message = data['message'];

      if (null == message || message.length == 0) return;

      if (data.containsKey('message')) {
        Get.rawSnackbar(
          title: 'Oops.',
          message: data['message'] ?? '-',
        );
      }
    } catch (e, stack) {
      report(e, stack, 'displayHttpError: ${response.bodyString}');
    }
  }
}
