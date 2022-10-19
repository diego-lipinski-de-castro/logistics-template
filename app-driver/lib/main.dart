import 'package:delivery/repositories/profile.dart';
import 'package:delivery/repositories/metadata.dart';
import 'package:delivery/services/auth.dart';
import 'package:delivery/services/http.dart';
import 'package:delivery/services/queue.dart';
import 'package:delivery/services/storage.dart';
import 'package:delivery/utils/pages.dart';
import 'package:delivery/utils/routes.dart';
import 'package:delivery/utils/strings.dart';
import 'package:firebase_analytics/firebase_analytics.dart';
import 'package:firebase_core/firebase_core.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:package_info/package_info.dart';

late PackageInfo info;

_initApp() async {
  await Firebase.initializeApp();

  await Get.putAsync(() => StorageService().init());

  Get.put(HttpService());

  Get.put(
    QueueService(
      httpProvider: Get.find<HttpService>(),
    ),
  );

  Get.put(
    ProfileRepository(
      httpProvider: Get.find<HttpService>(),
    ),
  );

  Get.put(
    MetadataRepository(
      httpProvider: Get.find<HttpService>(),
    ),
  );

  Get.put(
    AuthService(
      profileRepository: Get.find<ProfileRepository>(),
      metadataRepository: Get.find<MetadataRepository>(),
    ),
  );

  runApp(MyApp());
}

Future<void> main() async {
  WidgetsFlutterBinding.ensureInitialized();

  info = await PackageInfo.fromPlatform();

  _initApp();
}

class MyApp extends StatefulWidget {
  @override
  _MyAppState createState() => _MyAppState();
}

class _MyAppState extends State<MyApp> {
  @override
  Widget build(BuildContext context) {
    return GetMaterialApp(
      enableLog: true,
      popGesture: false,
      title: Strings.appName,
      navigatorObservers: [
        FirebaseAnalyticsObserver(
          analytics: FirebaseAnalytics.instance,
        ),
      ],
      initialRoute: Routes.splash,
      unknownRoute: AppPages.notFound,
      getPages: AppPages.pages,
      defaultTransition: Transition.fade,
      themeMode: ThemeMode.light,
      theme: ThemeData(
        visualDensity: VisualDensity.adaptivePlatformDensity,
        fontFamily: "SourceSansPro",
      ),
    );
  }
}
