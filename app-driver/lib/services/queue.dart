import 'dart:convert';

import 'package:delivery/services/http.dart';
import 'package:delivery/services/storage.dart';
import 'package:delivery/utils/utils.dart';
import 'package:get/get_state_manager/get_state_manager.dart';

class QueueItem {
  final String id;
  final String path;
  final String method;

  final dynamic data;

  QueueItem({
    required this.id,
    required this.path,
    required this.method,
    this.data,
  });

  factory QueueItem.fromMap(Map<String, dynamic> json) => QueueItem(
        id: json["id"],
        path: json["path"],
        method: json["method"],
        data: json["data"] != null ? json["data"] : null,
      );

  Map<String, dynamic> toMap() => {
        "id": id,
        "path": path,
        "method": method,
        "data": data != null ? data : null,
      };
}

class QueueService extends GetxService {
  static List<QueueItem> items = [];

  static late HttpService _httpProvider;

  final HttpService httpProvider;

  QueueService({required this.httpProvider}) {
    _httpProvider = httpProvider;

    _httpProvider.timeout = Duration(seconds: 10);

    removeAll();

    final List<dynamic> _queue = jsonDecode(
      StorageService.box.read('queue') ?? '[]',
    );

    items = _queue.map((e) => QueueItem.fromMap(e)).toList();
  }

  static removeAll() {
    items = [];
    _commit();
  }

  static add(QueueItem item) {
    items.add(item);
    _commit();
  }

  static remove(QueueItem item) {
    items.removeWhere((i) => i.id == item.id);
    _commit();
  }

  static _commit() {
    final _queue = items.map((e) => e.toString()).toList();

    StorageService.box.write('queue', jsonEncode(_queue));
  }

  static process() async {
    items.sort((a, b) => int.parse(a.id).compareTo(int.parse(b.id)));

    items.forEach((item) async {
      try {
        final response = await _httpProvider.request(
          '${item.path}?created_at=${item.id}',
          item.method,
          body: item.data,
        );

        if (!response.isOk) {
          return;
        }

        remove(item);
      } catch (e, stack) {
        Utils.report(e, stack, 'QueueService.process');
      }
    });
  }
}
