enum DeliveryStatuses {
  WAITING,
  PENDING,
  ACCEPTED,
  COLLECTING,
  DELIVERING,
  CONFIRMED,
  COMPLETED,
  RETURNING,
  CANCELED,
  NOT_DELIVERED,
}

enum ReceiptConfirmation {
  DISABLED,
  OPTIONAL,
  REQUIRED_PARTIAL,
  REQUIRED,
}

class Delivery {
  Delivery({
    required this.id,
    required this.pubId,
    required this.cid,
    required this.status,
    required this.userId,
    required this.driverId,
    required this.rad,
    required this.time,
    required this.paid,
    required this.returnFeePaid,
    required this.receiptConfirmation,
    required this.publicText,
    required this.customerName,
    required this.customerPhone,
    required this.createdAt,
    required this.updatedAt,
    required this.formattedStatus,
    required this.formattedPaid,
    required this.formattedTotalPaid,
    required this.formattedReturnFeePaid,
    required this.formattedCreatedAt,
    required this.formattedUpdatedAt,
    required this.user,
    required this.steps,
    required this.additionalInfo,
    required this.lastmile,
    required this.scheduledAt,
    required this.formattedScheduledAt,
  });

  final int id;
  final String pubId;
  final String cid;
  DeliveryStatuses status;
  final int userId;
  final int? driverId;
  final int rad;
  final int time;
  final int paid;
  final int? returnFeePaid;
  final ReceiptConfirmation receiptConfirmation;
  final String? publicText;
  final String? customerName;
  final String? customerPhone;
  final DateTime createdAt;
  final DateTime updatedAt;
  final String formattedStatus;
  final String formattedPaid;
  final String formattedTotalPaid;
  final String? formattedReturnFeePaid;
  final String formattedCreatedAt;
  final String formattedUpdatedAt;
  final User user;
  final List<Step> steps;
  final String? additionalInfo;
  final bool lastmile;
  final DateTime? scheduledAt;
  final String? formattedScheduledAt;

  factory Delivery.fromMap(Map<String, dynamic> json) => Delivery(
        id: json["id"] == null ? null : json["id"],
        pubId: json["pub_id"] == null ? null : json["pub_id"],
        cid: json["cid"] == null ? null : json["cid"],
        status: DeliveryStatuses.values.byName(json['status']),
        userId: json["user_id"] == null ? null : json["user_id"],
        driverId: json["driver_id"],
        rad: json["rad"] == null ? null : json["rad"],
        time: json["time"] == null ? null : json["time"],
        paid: json["paid"] == null ? null : json["paid"],
        returnFeePaid:
            json["return_fee_paid"] == null ? null : json["return_fee_paid"],
        receiptConfirmation:
            ReceiptConfirmation.values.byName(json['receipt_confirmation']),
        publicText: json["public_text"],
        customerName: json["customer_name"],
        customerPhone: json["customer_phone"],
        createdAt: DateTime.parse(json["created_at"]),
        updatedAt: DateTime.parse(json["updated_at"]),
        formattedStatus:
            json["formatted_status"] == null ? null : json["formatted_status"],
        formattedPaid:
            json["formatted_paid"] == null ? null : json["formatted_paid"],
        formattedTotalPaid: json["formatted_total_paid"],
        formattedReturnFeePaid: json["formatted_return_fee_paid"] == null
            ? null
            : json["formatted_return_fee_paid"],
        formattedCreatedAt: json["formatted_created_at"] == null
            ? null
            : json["formatted_created_at"],
        formattedUpdatedAt: json["formatted_updated_at"] == null
            ? null
            : json["formatted_updated_at"],
        user: User.fromMap(json["user"]),
        steps: List<Step>.from(json["steps"].map((x) => Step.fromMap(x))),
        additionalInfo:
            json["additional_info"] == null ? null : json["additional_info"],
        lastmile: json['lastmile'],
        scheduledAt: json["scheduled_at"] == null
            ? null
            : DateTime.parse(json["scheduled_at"]),
        formattedScheduledAt: json["formatted_scheduled_at"] == null
            ? null
            : json["formatted_scheduled_at"],
      );

  Map<String, dynamic> toMap() => {
        "id": id,
        "pub_id": pubId,
        "cid": cid,
        "status": status,
        "user_id": userId,
        "driver_id": driverId == null ? null : driverId,
        "rad": rad,
        "time": time,
        "paid": paid,
        "return_fee_paid": returnFeePaid == null ? null : returnFeePaid,
        "receipt_confirmation": receiptConfirmation,
        "public_text": publicText == null ? null : publicText,
        "customer_name": customerName == null ? null : customerName,
        "customer_phone": customerPhone == null ? null : customerPhone,
        "created_at": createdAt.toIso8601String(),
        "updated_at": updatedAt.toIso8601String(),
        "formatted_status": formattedStatus,
        "formatted_paid": formattedPaid,
        "formatted_total_paid": formattedTotalPaid,
        "formatted_return_fee_paid": formattedReturnFeePaid,
        "formatted_created_at": formattedCreatedAt,
        "formatted_updated_at": formattedUpdatedAt,
        "user": user.toMap(),
        "steps": List<Step>.from(steps.map((x) => x.toMap())),
        "additional_info": additionalInfo == null ? null : additionalInfo,
      };
}

class Step {
  Step({
    required this.id,
    required this.deliveryId,
    required this.location,
    required this.streetNumber,
    required this.streetName,
    required this.district,
    required this.city,
    required this.state,
    required this.prevId,
    required this.nextId,
    required this.status,
    required this.createdAt,
    required this.updatedAt,
    required this.formattedAddress,
  });

  final int id;
  final int deliveryId;
  final Location location;
  final String? streetNumber;
  final String? streetName;
  final String? district;
  final String? city;
  final String? state;
  final int? prevId;
  final int? nextId;
  final String status;
  final DateTime createdAt;
  final DateTime updatedAt;
  final String formattedAddress;

  factory Step.fromMap(Map<String, dynamic> json) => Step(
      id: json["id"] == null ? null : json["id"],
      deliveryId: json["delivery_id"] == null ? null : json["delivery_id"],
      location: Location.fromMap(json["location"]),
      streetNumber:
          json["street_number"] == null ? null : json["street_number"],
      streetName: json["street_name"] == null ? null : json["street_name"],
      district: json["district"] == null ? null : json["district"],
      city: json["city"] == null ? null : json["city"],
      state: json["state"] == null ? null : json["state"],
      prevId: json["prev_id"] == null ? null : json["prev_id"],
      nextId: json["next_id"] == null ? null : json["next_id"],
      status: json["status"] == null ? null : json["status"],
      createdAt: DateTime.parse(json["created_at"]),
      updatedAt: DateTime.parse(json["updated_at"]),
      formattedAddress:
          json["formatted_address"] == null ? null : json["formatted_address"]);

  Map<String, dynamic> toMap() => {
        "id": id,
        "delivery_id": deliveryId,
        "location": location.toMap(),
        "street_number": streetNumber == null ? null : streetNumber,
        "street_name": streetName == null ? null : streetName,
        "district": district == null ? null : district,
        "city": city == null ? null : city,
        "state": state == null ? null : state,
        "prev_id": prevId == null ? null : prevId,
        "next_id": nextId == null ? null : nextId,
        "status": status,
        "created_at": createdAt.toIso8601String(),
        "updated_at": updatedAt.toIso8601String(),
        "formatted_address": formattedAddress,
      };
}

class Location {
  Location({
    required this.type,
    required this.coordinates,
  });

  final String type;
  final List<double> coordinates;

  factory Location.fromMap(Map<String, dynamic> json) => Location(
        type: json["type"] == null ? null : json["type"],
        coordinates: List<double>.from(
          json["coordinates"].map((x) => x.toDouble()),
        ),
      );

  Map<String, dynamic> toMap() => {
        "type": type,
        "coordinates": List<dynamic>.from(coordinates.map((x) => x)),
      };
}

class User {
  User({
    required this.id,
    required this.pubId,
    required this.name,
    required this.phone,
    required this.info,
    required this.cityId,
    required this.receiptConfirmation,
    required this.deliveryConstraint,
    required this.returnFeePaid,
    required this.createdAt,
    required this.updatedAt,
    required this.formattedReturnFeePaid,
    required this.formattedCreatedAt,
    required this.formattedUpdatedAt,
  });

  final int id;
  final String pubId;
  final String name;
  final dynamic phone;
  final dynamic info;
  final int cityId;
  final ReceiptConfirmation receiptConfirmation;
  final String deliveryConstraint;
  final int returnFeePaid;
  final DateTime createdAt;
  final DateTime updatedAt;
  final String formattedReturnFeePaid;
  final String formattedCreatedAt;
  final String formattedUpdatedAt;

  factory User.fromMap(Map<String, dynamic> json) => User(
        id: json["id"] == null ? null : json["id"],
        pubId: json["pub_id"] == null ? null : json["pub_id"],
        name: json["name"] == null ? null : json["name"],
        phone: json["phone"],
        info: json["info"],
        cityId: json["city_id"] == null ? null : json["city_id"],
        receiptConfirmation:
            ReceiptConfirmation.values.byName(json['receipt_confirmation']),
        deliveryConstraint: json["delivery_constraint"] == null
            ? null
            : json["delivery_constraint"],
        returnFeePaid: json["return_fee_paid"],
        createdAt: DateTime.parse(json["created_at"]),
        updatedAt: DateTime.parse(json["updated_at"]),
        formattedReturnFeePaid: json["formatted_return_fee_paid"],
        formattedCreatedAt: json["formatted_created_at"] == null
            ? null
            : json["formatted_created_at"],
        formattedUpdatedAt: json["formatted_updated_at"] == null
            ? null
            : json["formatted_updated_at"],
      );

  Map<String, dynamic> toMap() => {
        "id": id,
        "pub_id": pubId,
        "name": name,
        "phone": phone,
        "info": info,
        "city_id": cityId,
        "receipt_confirmation": receiptConfirmation,
        "delivery_constraint": deliveryConstraint,
        "return_fee_paid": returnFeePaid,
        "created_at": createdAt.toIso8601String(),
        "updated_at": updatedAt.toIso8601String(),
        "formatted_return_fee_paid": formattedReturnFeePaid,
        "formatted_created_at": formattedCreatedAt,
        "formatted_updated_at": formattedUpdatedAt,
      };
}
