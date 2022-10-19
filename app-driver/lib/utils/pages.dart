import 'package:delivery/bindings/history.dart';
import 'package:delivery/bindings/home.dart';
import 'package:delivery/bindings/login.dart';
import 'package:delivery/bindings/login_confirm.dart';
import 'package:delivery/bindings/notification.dart';
import 'package:delivery/bindings/permission.dart';
import 'package:delivery/bindings/profile.dart';
import 'package:delivery/bindings/register.dart';
import 'package:delivery/bindings/register_confirm.dart';
import 'package:delivery/bindings/register_finish.dart';
import 'package:delivery/bindings/todays.dart';
import 'package:delivery/bindings/tracking.dart';
import 'package:delivery/pages/profile/documents.dart';
import 'package:delivery/pages/profile/personal_info.dart';
import 'package:delivery/pages/auth/register/register.dart';
import 'package:delivery/pages/notification.dart';
import 'package:delivery/pages/permission.dart';
import 'package:delivery/pages/profile.dart';
import 'package:delivery/pages/auth/register/register_confirm.dart';
import 'package:delivery/pages/auth/register/register_finish.dart';
import 'package:delivery/pages/todays.dart';
import 'package:delivery/pages/tracking.dart';
import 'package:delivery/bindings/splash.dart';
import 'package:delivery/pages/faq.dart';
import 'package:delivery/pages/history.dart';
import 'package:delivery/pages/home.dart';
import 'package:delivery/pages/auth/login/login.dart';
import 'package:delivery/pages/auth/login/login_confirm.dart';
import 'package:delivery/pages/not_found.dart';
import 'package:delivery/pages/splash.dart';
import 'package:delivery/utils/routes.dart';
import 'package:get/get.dart';

class AppPages {
  static GetPage get notFound => GetPage(
        name: Routes.notFound,
        page: () => NotFoundPage(),
      );

  static List<GetPage> get pages => [
        GetPage(
          name: Routes.splash,
          page: () => SplashPage(),
          binding: SplashBinding(),
        ),
        GetPage(
          name: Routes.login,
          page: () => LoginPage(),
          binding: LoginBinding(),
        ),
        GetPage(
          name: Routes.loginConfirm,
          page: () => LoginConfirmPage(),
          binding: LoginConfirmBinding(),
        ),
        GetPage(
          name: Routes.register,
          page: () => RegisterPage(),
          binding: RegisterBinding(),
        ),
        GetPage(
          name: Routes.registerConfirm,
          page: () => RegisterConfirmPage(),
          binding: RegisterConfirmBinding(),
        ),
        GetPage(
          name: Routes.registerFinish,
          page: () => RegisterFinishPage(),
          binding: RegisterFinishBinding(),
        ),
        GetPage(
          name: Routes.permission,
          page: () => PermissionPage(),
          binding: PermissionBinding(),
        ),
        GetPage(
          name: Routes.notification,
          page: () => NotificationPage(),
          binding: NotificationBinding(),
        ),
        GetPage(
          name: Routes.home,
          page: () => HomePage(),
          binding: HomeBinding(),
        ),
        GetPage(
          name: Routes.tracking,
          page: () => TrackingPage(),
          binding: TrackingBinding(),
        ),
        GetPage(
          name: Routes.todays,
          page: () => TodaysPage(),
          binding: TodaysBinding(),
        ),
        GetPage(
          name: Routes.history,
          page: () => HistoryPage(),
          binding: HistoryBinding(),
        ),
        GetPage(
          name: Routes.profile,
          page: () => ProfilePage(),
          binding: ProfileBinding(),
        ),
        GetPage(
          name: Routes.profilePersonalInfo,
          page: () => ProfilePersonalInfoPage(),
          binding: ProfileBinding(),
        ),
        GetPage(
          name: Routes.profileDocuments,
          page: () => ProfileDocumentsPage(),
          binding: ProfileBinding(),
        ),
        GetPage(
          name: Routes.faq,
          page: () => FaqPage(),
        ),
      ];
}
