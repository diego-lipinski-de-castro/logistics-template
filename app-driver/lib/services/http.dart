import 'package:delivery/services/storage.dart';
import 'package:delivery/utils/config.dart';
import 'package:get/get_connect/connect.dart';
import 'package:get/get_connect/http/src/request/request.dart';

class HttpService extends GetConnect {
  @override
  void onInit() {
    super.onInit();

    httpClient.baseUrl = Config.BASE_URL;
    httpClient.followRedirects = false;
    httpClient.timeout = Duration(seconds: 20);
    httpClient.defaultContentType = 'application/json; charset=utf-8';
    httpClient.maxAuthRetries = 0;

    httpClient.addRequestModifier((Request request) {
      request.headers['Accept'] = 'application/json; charset=utf-8';

      String? accessToken = StorageService.box.read(Config.ACCESS_TOKEN);

      if (null != accessToken) {
        if (Config.ENABLE_ERROR_LOGGING) {
          print('accessToken $accessToken');
        }

        request.headers['Authorization'] = 'Bearer $accessToken';
      }

      return request;
    });
  }
}
