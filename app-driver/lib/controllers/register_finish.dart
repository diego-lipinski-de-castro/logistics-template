import 'package:delivery/repositories/register.dart';
import 'package:delivery/repositories/state.dart';
import 'package:delivery/services/auth.dart';
import 'package:delivery/services/storage.dart';
import 'package:delivery/utils/config.dart';
import 'package:delivery/utils/routes.dart';
import '../models/state.dart';
import 'package:flutter/material.dart' as Material;
import 'package:get/get.dart';

class RegisterFinishController extends GetxController {
  final AuthService authService;
  final RegisterRepository registerRepository;
  final StateRepository stateRepository;

  final Material.GlobalKey<Material.FormState> formKey =
      Material.GlobalKey<Material.FormState>();

  final Material.TextEditingController phoneField =
      Material.TextEditingController();
  final Material.TextEditingController firstName =
      Material.TextEditingController();
  final Material.TextEditingController lastName =
      Material.TextEditingController();
  final Material.TextEditingController birthdayField =
      Material.TextEditingController();
  final Material.TextEditingController cpfField =
      Material.TextEditingController();
  final Material.TextEditingController cnhField =
      Material.TextEditingController();

  final _states = Rx<List<State>>([]);
  List<State> get states => _states.value;

  List<City> get cities {
    if (null == selectedState) return [];

    return states.firstWhere((el) => el.id.toString() == selectedState).cities;
  }

  final _selectedState = Rx<String?>(null);
  String? get selectedState => _selectedState.value;

  final _selectedCity = Rx<String?>(null);
  String? get selectedCity => _selectedCity.value;

  final _loading = RxBool(false);
  bool get loading => _loading.value;

  final _loadingStates = RxBool(false);
  bool get loadingStates => _loadingStates.value;

  final _accepted = RxBool(false);
  bool get accepted => _accepted.value;

  RegisterFinishController({
    required this.authService,
    required this.registerRepository,
    required this.stateRepository,
  });

  @override
  void onInit() {
    super.onInit();

    phoneField.text = authService.driver!.phone;

    if (null == Get.arguments) {
      _getStates();
      return;
    }

    _states.value = (Get.arguments as List<State>);
  }

  toggleAccept() {
    _accepted.value = !accepted;
  }

  setCity(String? cityId) {
    _selectedCity.value = cityId;
  }

  setState(String? stateId) {
    _selectedState.value = stateId;
  }

  _getStates() async {
    _loadingStates.value = true;
    _states.value = await stateRepository.index();
    _loadingStates.value = false;
  }

  logout() async {
    await StorageService.box.remove(Config.ACCESS_TOKEN);
    authService.setDriver();

    Get.offAllNamed(Routes.login);
  }

  submit() async {
    if (loading) return;

    if (!accepted) {
      Get.rawSnackbar(
        message: 'Você precisa aceitar os termos de uso e privacidade.',
      );

      return;
    }

    if (formKey.currentState!.validate()) {
      _loading.value = true;

      final FormData data = FormData({
        "city_id": selectedCity,
        "first_name": firstName.text,
        "last_name": lastName.text,
        "birthday": birthdayField.text,
        "cpf": cpfField.text,
        "cnh": cnhField.text,
      });

      Get.dialog(
        Material.AlertDialog(
          title: Material.Text('Aguarde...'),
          content: Material.Text(
            'Esse processo pode levar até 2 minutos.',
          ),
        ),
      );

      bool registered = await registerRepository.finish(data);

      if (!registered) {
        Get.back();
        _loading.value = false;
      } else {
        await authService.setDriver();

        Get.toNamed(Routes.home);
      }
    } else {
      Get.rawSnackbar(
        title: 'Preencha todos os campos!',
        message: 'Verifique que nenhuma informação esteja em branco.',
      );
    }
  }
}
