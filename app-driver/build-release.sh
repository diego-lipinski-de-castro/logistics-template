#!/bin/bash 

flutter clean
flutter pub get

# BASE_URL="https://painel.speedapp.com.br"

# flutter build apk --release --dart-define=BASE_URL=$BASE_URL
# flutter build appbundle --release --dart-define=BASE_URL=$BASE_URL
# flutter build ios --release --dart-define=BASE_URL=$BASE_URL

flutter build apk --release --no-sound-null-safety
flutter build appbundle --release --no-sound-null-safety
