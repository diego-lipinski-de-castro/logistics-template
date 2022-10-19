import 'package:delivery/models/state.dart';
import 'package:delivery/services/http.dart';
import 'package:delivery/utils/utils.dart';

class StateRepository {
  HttpService httpProvider;

  StateRepository({required this.httpProvider});

  Future<List<State>> index() async {
    try {
      final response = await httpProvider.get('/api/driver/states');

      return List.from(response.body['states'])
          .map((e) => State.fromMap(e))
          .toList();
    } catch (e, stack) {
      Utils.report(e, stack, 'StateRepository.index');

      return [];
    }
  }
}
