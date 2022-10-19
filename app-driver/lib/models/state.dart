class State {
  State(
      {required this.id,
      required this.name,
      required this.createdAt,
      required this.updatedAt,
      required this.cities});

  int id;
  String name;
  DateTime createdAt;
  DateTime updatedAt;
  List<City> cities;

  factory State.fromMap(Map<String, dynamic> json) => State(
        id: json["id"],
        name: json["name"],
        createdAt: DateTime.parse(json["created_at"]),
        updatedAt: DateTime.parse(json["updated_at"]),
        cities: List<City>.from(
          json["cities"].map((x) => City.fromMap(x)),
        ),
      );

  Map<String, dynamic> toMap() => {
        "id": id,
        "name": name,
        "created_at": createdAt,
        "updated_at": updatedAt,
        "cities": List<City>.from(cities.map((x) => x.toMap())),
      };
}

class City {
  City({
    required this.id,
    required this.name,
    required this.enabled,
    required this.stateId,
    required this.createdAt,
    required this.updatedAt,
  });

  int id;
  String name;
  bool enabled;
  int stateId;
  DateTime createdAt;
  DateTime updatedAt;

  factory City.fromMap(Map<String, dynamic> json) => City(
        id: json["id"],
        name: json["name"],
        enabled: json["enabled"],
        stateId: json["state_id"],
        createdAt: DateTime.parse(json["created_at"]),
        updatedAt: DateTime.parse(json["updated_at"]),
      );

  Map<String, dynamic> toMap() => {
        "id": id,
        "name": name,
        "enabled": enabled,
        "state_id": stateId,
        "created_at": createdAt,
        "updated_at": updatedAt,
      };
}
