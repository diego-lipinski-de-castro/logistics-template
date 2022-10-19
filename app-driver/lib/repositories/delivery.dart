import 'package:delivery/models/delivery.dart';
import 'package:delivery/services/http.dart';
import 'package:delivery/utils/utils.dart';
import 'package:get/get_connect.dart';

class DeliveryRepository {
  final HttpService httpProvider;

  DeliveryRepository({required this.httpProvider});

  Future<List<Delivery>> index() async {
    try {
      final response = await httpProvider.get(
        '/api/driver/deliveries',
      );

      if (!response.isOk) {
        Utils.displayHttpError(response);
        return [];
      }

      return List.from(response.body['data'])
          .map((e) => Delivery.fromMap(e))
          .toList();
    } catch (e, stack) {
      Utils.report(e, stack, 'DeliveryRepository.index');

      return [];
    }
  }

  Future<List<Delivery>> today([int page = 1]) async {
    try {
      final response = await httpProvider.get(
        '/api/driver/deliveries/today',
        query: {
          'orderBy': 'created_at',
          'sortedBy': 'desc',
        },
      );

      if (!response.isOk) {
        Utils.displayHttpError(response);
        return [];
      }

      return List.from(response.body['data'])
          .map((e) => Delivery.fromMap(e))
          .toList();
    } catch (e, stack) {
      Utils.report(e, stack, 'DeliveryRepository.today');

      return [];
    }
  }

  Future<List<Delivery>> history([int page = 1]) async {
    try {
      final response = await httpProvider.get(
        '/api/driver/deliveries/history',
        query: {
          'orderBy': 'created_at',
          'sortedBy': 'desc',
          'page': '$page',
        },
      );

      if (!response.isOk) {
        Utils.displayHttpError(response);
        return [];
      }

      return List.from(response.body['data'])
          .map((e) => Delivery.fromMap(e))
          .toList();
    } catch (e, stack) {
      Utils.report(e, stack, 'DeliveryRepository.history');

      return [];
    }
  }

  Future<Delivery?> show(Delivery delivery) async {
    try {
      final response = await httpProvider.get(
        '/api/driver/deliveries/${delivery.id}',
      );

      if (!response.isOk) {
        Utils.displayHttpError(response);
        return null;
      }

      return Delivery.fromMap(response.body['delivery']);
    } catch (e, stack) {
      Utils.report(e, stack, 'DeliveryRepository.show');

      return null;
    }
  }

  Future<Delivery?> accept(Delivery delivery) async {
    try {
      final response = await httpProvider.post(
        '/api/driver/deliveries/${delivery.id}/accept',
        null,
      );

      if (!response.isOk) {
        Utils.displayHttpError(response);
        return null;
      }

      return Delivery.fromMap(response.body['delivery']);
    } catch (e, stack) {
      Utils.report(e, stack, 'DeliveryRepository.accept');

      return null;
    }
  }

  Future<Delivery?> collect(Delivery delivery) async {
    try {
      final response = await httpProvider.post(
        '/api/driver/deliveries/${delivery.id}/collect',
        null,
      );

      if (!response.isOk) {
        Utils.displayHttpError(response);
        return null;
      }

      return Delivery.fromMap(response.body['delivery']);
    } catch (e, stack) {
      Utils.report(e, stack, 'DeliveryRepository.collect');

      return null;
    }
  }

  Future<Delivery?> deliver(Delivery delivery) async {
    try {
      final response = await httpProvider.post(
        '/api/driver/deliveries/${delivery.id}/deliver',
        null,
      );

      if (!response.isOk) {
        Utils.displayHttpError(response);
        return null;
      }

      return Delivery.fromMap(response.body['delivery']);
    } catch (e, stack) {
      Utils.report(e, stack, 'DeliveryRepository.collect');

      return null;
    }
  }

  Future<Delivery?> confirm(Delivery delivery, [FormData? data]) async {
    try {
      final response = await httpProvider.post(
        '/api/driver/deliveries/${delivery.id}/confirm',
        data,
      );

      if (!response.isOk) {
        Utils.displayHttpError(response);
        return null;
      }

      return Delivery.fromMap(response.body['delivery']);
    } catch (e, stack) {
      Utils.report(e, stack, 'DeliveryRepository.collect');

      return null;
    }
  }

  Future<Delivery?> complete(Delivery delivery) async {
    try {
      final response = await httpProvider.post(
        '/api/driver/deliveries/${delivery.id}/complete',
        null,
      );

      if (!response.isOk) {
        Utils.displayHttpError(response);
        return null;
      }

      return Delivery.fromMap(response.body['delivery']);
    } catch (e, stack) {
      Utils.report(e, stack, 'DeliveryRepository.complete');

      return null;
    }
  }

  Future<Delivery?> receive(deliveryId) async {
    try {
      final response = await httpProvider.post(
        '/api/driver/deliveries/$deliveryId/receive',
        null,
      );

      Utils.log([
        'DeliveryRepository.received',
        response.body,
      ]);

      return Delivery.fromMap(response.body['delivery']);
    } catch (e, stack) {
      Utils.report(e, stack, 'DeliveryRepository.received');

      return null;
    }
  }

  Future<void> refuse(deliveryId) async {
    try {
      await httpProvider.post(
        '/api/driver/deliveries/$deliveryId/refuse',
        null,
      );
    } catch (e, stack) {
      Utils.report(e, stack, 'DeliveryRepository.refused');
    }
  }
}
