import 'package:delivery/models/state.dart';

enum DriverStatuses { PENDING, APPROVED, REJECTED }

enum MetadataStatuses { OFFLINE, ONLINE, BUSY }

enum MetadataModes { BIKE, E_BIKE, MOTO, CAR, VAN, TRUCK }

enum MetadataBags {
  BAG_45,
  BAG_55,
  BAG_89,
  BAG,
  CAR,
  OPEN_VAN,
  CLOSED_VAN,
  TRUCK
}

class Driver {
  Driver({
    required this.id,
    required this.pubId,
    required this.cityId,
    required this.firstName,
    required this.lastName,
    required this.fullName,
    required this.email,
    required this.phone,
    required this.formattedPhone,
    required this.birthday,
    required this.cpf,
    required this.cnh,
    required this.vehicleName,
    required this.vehiclePlate,
    required this.pix,
    required this.registered,
    required this.status,
    required this.createdAt,
    required this.updatedAt,
    required this.bannedAt,
    required this.formattedStatus,
    required this.banned,
    required this.formattedCreatedAt,
    required this.formattedUpdatedAt,
    required this.metadata,
    required this.city,
    this.documents = const [],
  });

  final int id;
  final String pubId;
  final int cityId;
  final String firstName;
  final String? lastName;
  final String fullName;
  final String? email;
  final String phone;
  final String formattedPhone;
  final String? birthday;
  final String? cpf;
  final String? cnh;
  final String? vehicleName;
  final String? vehiclePlate;
  String? pix;
  bool registered;
  DriverStatuses status;
  final DateTime createdAt;
  final DateTime updatedAt;
  DateTime? bannedAt;
  final String formattedStatus;
  bool banned;
  final String formattedCreatedAt;
  final String formattedUpdatedAt;
  Metadata metadata;
  City? city;
  List<Document> documents;

  factory Driver.fromMap(Map<String, dynamic> json) => Driver(
        id: json["id"],
        pubId: json["pub_id"],
        cityId: json["city_id"],
        firstName: json["first_name"],
        lastName: json["last_name"] == null ? null : json["last_name"],
        fullName: json["full_name"],
        email: json["email"] == null ? null : json["email"],
        phone: json["phone"],
        formattedPhone: json["formatted_phone"],
        birthday: json["birthday"] == null ? null : json["birthday"],
        cpf: json["cpf"] == null ? null : json["cpf"],
        cnh: json["cnh"] == null ? null : json["cnh"],
        vehicleName: json["vehicle_name"] == null ? null : json["vehicle_name"],
        vehiclePlate:
            json["vehicle_plate"] == null ? null : json["vehicle_plate"],
        pix: json["pix"] == null ? null : json["pix"],
        registered: json["registered"],
        status: DriverStatuses.values.byName(json["status"]),
        createdAt: DateTime.parse(json["created_at"]),
        updatedAt: DateTime.parse(json["updated_at"]),
        bannedAt: json["banned_at"] == null
            ? null
            : DateTime.parse(json["banned_at"]),
        formattedStatus: json["formatted_status"],
        banned: json["banned"],
        formattedCreatedAt: json["formatted_created_at"],
        formattedUpdatedAt: json["formatted_updated_at"],
        metadata: Metadata.fromMap(json["metadata"]),
        city: json["city"] == null ? null : City.fromMap(json["city"]),
        documents: List<Document>.from(
          json["documents"].map((x) => Document.fromMap(x)),
        ),
      );

  Map<String, dynamic> toMap() => {
        "id": id,
        "pub_id": pubId,
        "city_id": cityId,
        "first_name": firstName,
        "last_name": lastName == null ? null : lastName,
        "full_name": fullName,
        "email": email == null ? null : email,
        "phone": phone,
        "formatted_phone": formattedPhone,
        "birthday": birthday == null ? null : birthday,
        "cpf": cpf == null ? null : cpf,
        "cnh": cnh == null ? null : cnh,
        "vehicle_name": vehicleName == null ? null : vehicleName,
        "vehicle_plate": vehiclePlate == null ? null : vehiclePlate,
        "pix": pix == null ? null : pix,
        "registered": registered,
        "status": status,
        "created_at": createdAt.toIso8601String(),
        "updated_at": updatedAt.toIso8601String(),
        "banned_at": bannedAt == null ? null : bannedAt,
        "formatted_status": formattedStatus,
        "banned": banned,
        "formatted_created_at": formattedCreatedAt,
        "formatted_updated_at": formattedUpdatedAt,
        "metadata": metadata.toMap(),
        "city": city == null ? null : city!.toMap(),
        "documents": List<Document>.from(documents.map((x) => x.toMap())),
      };
}

class Metadata {
  Metadata({
    required this.driverId,
    required this.cloud,
    required this.status,
    required this.location,
    required this.mode,
    required this.bag,
    required this.createdAt,
    required this.updatedAt,
  });

  final int driverId;
  final bool cloud;
  MetadataStatuses status;
  Location? location;
  MetadataModes? mode;
  MetadataBags? bag;
  final DateTime createdAt;
  final DateTime updatedAt;

  factory Metadata.fromMap(Map<String, dynamic> json) => Metadata(
        driverId: json["driver_id"],
        cloud: json["cloud"],
        status: MetadataStatuses.values.byName(json["status"]),
        location: json["location"] == null
            ? null
            : Location.fromMap(json["location"]),
        mode: json["mode"] == null
            ? null
            : MetadataModes.values.byName(json["mode"]),
        bag: json["bag"] == null
            ? null
            : MetadataBags.values.byName(json["bag"]),
        createdAt: DateTime.parse(json["created_at"]),
        updatedAt: DateTime.parse(json["updated_at"]),
      );

  Map<String, dynamic> toMap() => {
        "driver_id": driverId,
        "cloud": cloud,
        "status": status,
        "location": location == null ? null : location!.toMap(),
        "mode": mode == null ? null : mode,
        "bag": bag == null ? null : bag,
        "created_at": createdAt.toIso8601String(),
        "updated_at": updatedAt.toIso8601String(),
      };
}

class Location {
  Location({
    required this.coordinates,
  });

  List<double> coordinates;

  double get latitude => coordinates[0];
  double get longitude => coordinates[1];

  factory Location.fromMap(Map<String, dynamic> json) => Location(
        coordinates: List<double>.from(json["coordinates"].map((x) => x)),
      );

  Map<String, dynamic> toMap() => {
        "coordinates": List<dynamic>.from(coordinates.map((x) => x)),
      };
}

class Document {
  Document({
    required this.id,
    required this.driverId,
    required this.folder,
    required this.name,
    required this.type,
    required this.path,
    required this.url,
    this.fullUrl,
    required this.createdAt,
    required this.updatedAt,
    required this.mime,
    required this.mimey,
    required this.formattedCreatedAt,
    required this.formattedUpdatedAt,
  });

  final String id;
  final int driverId;
  final String folder;
  final String name;
  final String type;
  final String path;
  final String url;
  final String? fullUrl;
  final DateTime createdAt;
  final DateTime updatedAt;
  final String mime;
  final String mimey;
  final String formattedCreatedAt;
  final String formattedUpdatedAt;

  factory Document.fromMap(Map<String, dynamic> json) => Document(
        id: json["id"],
        driverId: json["driver_id"],
        folder: json["folder"],
        name: json["name"],
        type: json["type"],
        path: json["path"],
        url: json["url"],
        fullUrl: json["full_url"] == null ? null : json["full_url"],
        createdAt: DateTime.parse(json["created_at"]),
        updatedAt: DateTime.parse(json["updated_at"]),
        mime: json["mime"],
        mimey: json["mimey"],
        formattedCreatedAt: json["formatted_created_at"],
        formattedUpdatedAt: json["formatted_updated_at"],
      );

  Map<String, dynamic> toMap() => {
        "id": id,
        "driver_id": driverId,
        "folder": folder,
        "name": name,
        "type": type,
        "path": path,
        "url": url,
        "full_url": fullUrl == null ? null : fullUrl,
        "created_at": createdAt.toIso8601String(),
        "updated_at": updatedAt.toIso8601String(),
        "mime": mime,
        "mimey": mimey,
        "formatted_created_at": formattedCreatedAt,
        "formatted_updated_at": formattedUpdatedAt,
      };
}
