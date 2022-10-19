import 'package:delivery/services/http.dart';
import 'package:delivery/utils/utils.dart';
import 'package:get/get.dart';

class RegisterRepository {
  final HttpService httpProvider;

  RegisterRepository({required this.httpProvider});

  Future<String?> requestCode(String phoneNumber) async {
    try {
      final response = await httpProvider.post(
        '/api/driver/register/request-code',
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
      Utils.report(e, stack, 'RegisterRepository.requestCode');

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
        '/api/driver/register/check-code',
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
      Utils.report(e, stack, 'RegisterRepository.checkCode');

      Get.rawSnackbar(
        title: 'Oops.',
        message: 'Não foi possível verificar o número inserido.',
      );

      return null;
    }
  }

  Future<bool> finish(FormData data) async {
    try {
      httpProvider.timeout = Duration(seconds: 120);

      final response = await httpProvider.post(
        '/api/driver/register/finish',
        data,
      );

      if (!response.isOk) {
        // TODO: check 422 errors messages
        Utils.displayHttpError(response);

        return false;
      }

      return true;
    } catch (e, stack) {
      Utils.report(e, stack, 'RegisterRepository.finish');

      Get.rawSnackbar(
        title: 'Oops.',
        message: 'Não foi possível verificar o número inserido.',
      );

      return false;
    }
  }
}
