import 'dart:async';
import 'dart:isolate';
import 'dart:ui';

import 'package:background_locator/location_dto.dart';

class LocationServiceRepository {
  static LocationServiceRepository _instance = LocationServiceRepository._();

  LocationServiceRepository._();

  factory LocationServiceRepository() {
    return _instance;
  }

  static const String isolateName = 'LocatorIsolate';

  Future<void> init(Map<dynamic, dynamic> params) async {
    // print("***********Init callback handler");
    await setLogLabel("start");
    final SendPort? send = IsolateNameServer.lookupPortByName(isolateName);
    send?.send(null);
  }

  Future<void> dispose() async {
    // print("***********Dispose callback handler");
    await setLogLabel("end");
    final SendPort? send = IsolateNameServer.lookupPortByName(isolateName);
    send?.send(null);
  }

  Future<void> callback(LocationDto locationDto) async {
    // print('location in dart: ${locationDto.toString()}');
    final SendPort? send = IsolateNameServer.lookupPortByName(isolateName);
    send?.send(locationDto);
  }

  static Future<void> setLogLabel(String label) async {}

  static Future<void> setLogPosition(int count, LocationDto data) async {}
}
