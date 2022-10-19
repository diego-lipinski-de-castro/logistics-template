#!/bin/bash 

flutter clean
flutter pub get

# BASE_URL="https://dev.painel.speedapp.com.br"

# flutter build apk --debug --dart-define=BASE_URL=$BASE_URL
# flutter build appbundle --debug --dart-define=BASE_URL=$BASE_URL
# flutter build ios --debug --dart-define=BASE_URL=$BASE_URL

# sed -i -e "s/PRODUCTION_URL;/STAGING_URL;/g" lib/utils/config.dart

flutter build apk --debug --no-sound-null-safety
flutter build appbundle --debug --no-sound-null-safety
