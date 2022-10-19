import 'package:delivery/controllers/profile.dart';
import 'package:delivery/repositories/profile.dart';
import 'package:delivery/services/auth.dart';
import 'package:get/get.dart';

class ProfileBinding extends Bindings {
  @override
  void dependencies() {
    Get.put<ProfileController>(
      ProfileController(
        authService: Get.find<AuthService>(),
        profileRepository: Get.find<ProfileRepository>(),
      ),
    );
  }
}
