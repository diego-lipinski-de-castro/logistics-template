import 'package:flutter/foundation.dart';

class Config {
  static const ENABLE_ERROR_REPORTING = kReleaseMode;
  static const ENABLE_ERROR_LOGGING = kDebugMode;

  static const PRODUCTION_URL = 'https://entregas.speedapp.com.br';
  static const LOCAL_URL = 'http://entregas.speedapp.test';
  static const LOCAL_URL_PROXY =
      'https://8bf8-2804-14c-5fe4-8221-6440-76f-9ddd-a815.sa.ng';

  static const BASE_URL = LOCAL_URL;

  static const STORAGE_PREFIX = 'speedapp_logistica';
  static const ACCESS_TOKEN = 'access_token';
  static const SKIP_INTRO = 'skip_intro';
  static const SKIP_ONLINE_DIALOG = 'skip_online_dialog';
  static const SKIP_START_DIALOG = 'skip_start_dialog';

  // a1bdd087a957c93e2d4f|cf64c5a1c686424a8810
  static const PUSHER_APP_KEY = 'a1bdd087a957c93e2d4f';
  static const PUSHER_APP_CLUSTER = 'sa1';
  static const PUSHER_ENABLE_LOGGING = kDebugMode;

  static const SENTRY_DN =
      'https://b56395b7857c4225b45c7129fa2f52f2@o374461.ingest.sentry.io/5633042';

  static const ONESIGNAL_APP_ID = '45371636-ba72-45ea-accf-77175ddfe2c7';
  static const ONESIGNAL_ENABLE_LOGGING = kDebugMode;
}
