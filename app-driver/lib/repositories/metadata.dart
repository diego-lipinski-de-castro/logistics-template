import 'package:delivery/main.dart';
import 'package:delivery/models/driver.dart';
import 'package:delivery/services/http.dart';
import 'package:delivery/utils/utils.dart';

class MetadataRepository {
  final HttpService httpProvider;

  MetadataRepository({required this.httpProvider});

  Future<MetadataStatuses?> updateStatus() async {
    try {
      final response = await httpProvider.put(
        '/api/driver/metadata/status',
        null,
      );

      if (!response.isOk) {
        Utils.displayHttpError(response);

        return null;
      }

      return MetadataStatuses.values.byName(response.body['status']);
    } catch (e, stack) {
      Utils.report(e, stack, 'MetadataRepository.updateStatus');

      return null;
    }
  }

  Future<bool> updateLocation(
    double latitude,
    double longitude,
    dynamic batteryLevel,
  ) async {
    try {
      // await QueueService.process();

      httpProvider.timeout = Duration(seconds: 10);

      final response = await httpProvider.put(
        '/api/driver/metadata/location',
        {
          'coordinates': [latitude, longitude],
          'battery_level': batteryLevel,
        },
      );

      // if (response.status.connectionError) {
      //   QueueService.add(QueueItem(
      //     id: DateTime.now().millisecondsSinceEpoch.toString(),
      //     path: '/api/driver/metadata/location',
      //     method: 'put',
      //     data: {
      //       'coordinates': [latitude, longitude],
      //       'battery_level': batteryLevel,
      //     },
      //   ));

      //   return false;
      // }

      if (!response.isOk) {
        Utils.displayHttpError(response);

        return false;
      }

      return true;
    } catch (e, stack) {
      Utils.report(e, stack, 'MetadataRepository.updateLocation');

      return false;
    }
  }

  Future<bool> updateDevice(dynamic systemInfo) async {
    try {
      final response = await httpProvider.put(
        '/api/driver/metadata/device',
        {
          'device': systemInfo,
          'version': info.version,
          'build': info.buildNumber,
        },
      );

      if (!response.isOk) {
        Utils.displayHttpError(response);

        return false;
      }

      return true;
    } catch (e, stack) {
      Utils.report(e, stack, 'MetadataRepository.updateDevice');

      return false;
    }
  }
}
