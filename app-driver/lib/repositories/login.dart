import 'package:delivery/services/http.dart';
import 'package:delivery/utils/utils.dart';
import 'package:get/get.dart';

class LoginRepository {
  final HttpService httpProvider;

  LoginRepository({required this.httpProvider});

  Future<String?> requestCode(String phoneNumber) async {
    try {
      final response = await httpProvider.post(
        '/api/driver/login/request-code',
        {
          'phone_number': phoneNumber,
        },
      );

      if (!response.isOk) {
        Utils.displayHttpError(response);
        return null;
      }

      return response.body['phone_number'];
    } catch (e, stack) {
      Utils.report(e, stack, 'LoginRepository.requestCode');

      Get.rawSnackbar(
        title: 'Oops.',
        message: 'Não foi possível verificar o número inserido.',
      );

      return null;
    }
  }

  Future<String?> checkCode(String phoneNumber, String code) async {
    try {
      final response = await httpProvider.post(
        '/api/driver/login/check-code',
        {
          'phone_number': phoneNumber,
          'code': code,
        },
      );

      if (!response.isOk) {
        Utils.displayHttpError(response);
        return null;
      }

      return response.body['access_token'];
    } catch (e, stack) {
      Utils.report(e, stack, 'LoginRepository.checkCode');

      Get.rawSnackbar(
        title: 'Oops.',
        message: 'Não foi possível verificar o número inserido.',
      );

      return null;
    }
  }
}
