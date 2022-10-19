<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Address
 *
 * @property int $user_id
 * @property mixed $position
 * @property string|null $street_number
 * @property string|null $street_name
 * @property string|null $district
 * @property string|null $city
 * @property string|null $state
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read string $formatted_address
 * @method static \Database\Factories\AddressFactory factory(...$parameters)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Address newModelQuery()
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Address newQuery()
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Address query()
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Address whereCity($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Address whereCreatedAt($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Address whereDistrict($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Address wherePosition($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Address whereState($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Address whereStreetName($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Address whereStreetNumber($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Address whereUpdatedAt($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Address whereUserId($value)
 */
	class Address extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\Admin
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\City[] $cities
 * @property-read int|null $cities_count
 * @property-read array $cities_ids
 * @property-read mixed $formatted_created_at
 * @property-read mixed $formatted_updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUpdatedAt($value)
 */
	class Admin extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\Audit
 *
 * @property int $id
 * @property string|null $user_type
 * @property int|null $user_id
 * @property string $event
 * @property string $auditable_type
 * @property int $auditable_id
 * @property array|null $old_values
 * @property array|null $new_values
 * @property string|null $url
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $tags
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $auditable
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $user
 * @method static \Illuminate\Database\Eloquent\Builder|Audit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Audit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Audit query()
 * @method static \Illuminate\Database\Eloquent\Builder|Audit whereAuditableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audit whereAuditableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audit whereEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audit whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audit whereNewValues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audit whereOldValues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audit whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audit whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audit whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audit whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audit whereUserType($value)
 */
	class Audit extends \Eloquent implements \OwenIt\Auditing\Contracts\Audit {}
}

namespace App\Models{
/**
 * App\Models\City
 *
 * @property int $id
 * @property int $state_id
 * @property string $name
 * @property mixed|null $center
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $enabled
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Admin[] $admins
 * @property-read int|null $admins_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Delivery[] $deliveries
 * @property-read int|null $deliveries_count
 * @property-read mixed $formatted_created_at
 * @property-read mixed $formatted_updated_at
 * @property-read \App\Models\State|null $state
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|City enabled()
 * @method static \Database\Factories\CityFactory factory(...$parameters)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|City newModelQuery()
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|City newQuery()
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|City ofAdmin($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|City ofState($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|City ofUser($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|City query()
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|City whereCenter($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|City whereCreatedAt($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|City whereEnabled($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|City whereId($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|City whereName($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|City whereStateId($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|City whereUpdatedAt($value)
 */
	class City extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\Delivery
 *
 * @property int $id
 * @property string $pub_id
 * @property string $cid
 * @property string $status
 * @property int|null $user_id
 * @property int|null $driver_id
 * @property int|null $rad
 * @property int|null $time
 * @property int|null $paid
 * @property int|null $charged
 * @property int|null $return_fee_charged
 * @property string|null $private_text
 * @property string|null $public_text
 * @property string|null $customer_name
 * @property string|null $customer_phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $pending_at
 * @property \Carbon\Carbon|null $accepted_at
 * @property \Carbon\Carbon|null $delivered_at
 * @property string|null $elapsed_time
 * @property string|null $additional_info
 * @property bool $is_marketplace
 * @property int|null $return_fee_paid
 * @property string $receipt_confirmation
 * @property string $charge_style
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $scheduled_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Driver|null $driver
 * @property-read mixed $formatted_accepted_at
 * @property-read string $formatted_charged
 * @property-read mixed $formatted_created_at
 * @property-read mixed $formatted_created_at_time
 * @property-read mixed $formatted_delivered_at
 * @property-read string $formatted_paid
 * @property-read mixed $formatted_pending_at
 * @property-read mixed $formatted_report_created_at
 * @property-read string|null $formatted_return_fee_charged
 * @property-read string|null $formatted_return_fee_paid
 * @property-read string|null $formatted_scheduled_at
 * @property-read string $formatted_status
 * @property-read string $formatted_total_charged
 * @property-read string $formatted_total_paid
 * @property-read mixed $formatted_updated_at
 * @property-read mixed $formatted_updated_at_time
 * @property-read bool $lastmile
 * @property-read int $total_charged
 * @property-read int $total_paid
 * @property-read \App\Models\Receipt|null $receipt
 * @property-read \App\Models\Route|null $route
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Shot[] $shots
 * @property-read int|null $shots_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Step[] $steps
 * @property-read int|null $steps_count
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\DeliveryFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery notRefused($driverId)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery ofCities(array|int $value = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery ofCity(array|int $value = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery ofDriver(?int $driverId)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery ofStatus(array|string $value = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery ofStatuses(array|string $value = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery ofUser(int $userId)
 * @method static \Illuminate\Database\Query\Builder|Delivery onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery query()
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery whereAcceptedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery whereAdditionalInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery whereChargeStyle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery whereCharged($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery whereCustomerPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery whereDeliveredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery whereElapsedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery whereIsMarketplace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery wherePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery wherePendingAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery wherePrivateText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery wherePubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery wherePublicText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery whereRad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery whereReceiptConfirmation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery whereReturnFeeCharged($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery whereReturnFeePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery whereScheduledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delivery whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Delivery withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Delivery withoutTrashed()
 */
	class Delivery extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\Developer
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|Developer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Developer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Developer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Developer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Developer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Developer whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Developer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Developer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Developer wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Developer whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Developer whereUpdatedAt($value)
 */
	class Developer extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\Document
 *
 * @property string $id
 * @property int $driver_id
 * @property string $folder
 * @property string $name
 * @property string $type
 * @property string $path
 * @property string $url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $formatted_created_at
 * @property-read mixed $formatted_updated_at
 * @property-read string|null $full_url
 * @property-read string|null $mime
 * @property-read string|null $mimey
 * @method static \Illuminate\Database\Eloquent\Builder|Document newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Document newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Document query()
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereFolder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereUrl($value)
 */
	class Document extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Driver
 *
 * @property int $id
 * @property string $pub_id
 * @property int|null $city_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $full_name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $birthday
 * @property string|null $cpf
 * @property string|null $cnh
 * @property string|null $vehicle_name
 * @property string|null $vehicle_plate
 * @property string|null $pix
 * @property bool $registered
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $banned_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\City|null $city
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Delivery[] $deliveries
 * @property-read int|null $deliveries_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Document[] $documents
 * @property-read int|null $documents_count
 * @property-read bool $banned
 * @property-read mixed $formatted_created_at
 * @property-read string|null $formatted_phone
 * @property-read string|null $formatted_status
 * @property-read mixed $formatted_updated_at
 * @property-read string $raw_phone
 * @property-read \App\Models\DriverMetadata|null $metadata
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Driver approved()
 * @method static \Database\Factories\DriverFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver idle()
 * @method static \Illuminate\Database\Eloquent\Builder|Driver newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Driver newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Driver notBanned()
 * @method static \Illuminate\Database\Eloquent\Builder|Driver ofCities(array|int $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver ofCity(array|int $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver ofStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver ofUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver query()
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereBannedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereCnh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereCpf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver wherePix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver wherePubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereRegistered($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereVehicleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereVehiclePlate($value)
 */
	class Driver extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\DriverMetadata
 *
 * @property int $driver_id
 * @property bool $cloud
 * @property string $status
 * @property mixed|null $location
 * @property string|null $mode
 * @property string|null $bag
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $battery_level
 * @property mixed|null $device
 * @property string|null $version
 * @property string|null $build
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Driver $driver
 * @property-read string|null $formatted_status
 * @method static \Database\Factories\DriverMetadataFactory factory(...$parameters)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|DriverMetadata newModelQuery()
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|DriverMetadata newQuery()
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|DriverMetadata query()
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|DriverMetadata whereBag($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|DriverMetadata whereBatteryLevel($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|DriverMetadata whereBuild($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|DriverMetadata whereCloud($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|DriverMetadata whereCreatedAt($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|DriverMetadata whereDevice($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|DriverMetadata whereDriverId($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|DriverMetadata whereLocation($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|DriverMetadata whereMode($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|DriverMetadata whereStatus($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|DriverMetadata whereUpdatedAt($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|DriverMetadata whereVersion($value)
 */
	class DriverMetadata extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\Payment
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment query()
 */
	class Payment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Receipt
 *
 * @property int $id
 * @property int $delivery_id
 * @property string|null $customer_name
 * @property string|null $customer_document
 * @property string|null $picture
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Delivery|null $delivery
 * @property-read string|null $full_url
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt query()
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereCustomerDocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereDeliveryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt wherePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereUpdatedAt($value)
 */
	class Receipt extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\Report
 *
 * @property int $id
 * @property int $user_id
 * @property string $filename
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $formatted_created_at
 * @property-read mixed $formatted_updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report ofUser(int $userId)
 * @method static \Illuminate\Database\Eloquent\Builder|Report query()
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereUserId($value)
 */
	class Report extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Route
 *
 * @property int $id
 * @property int $delivery_id
 * @property mixed $route
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Route newModelQuery()
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Route newQuery()
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Route query()
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Route whereCreatedAt($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Route whereDeliveryId($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Route whereId($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Route whereRoute($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Route whereUpdatedAt($value)
 */
	class Route extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\Shot
 *
 * @property int $id
 * @property int $delivery_id
 * @property int $driver_id
 * @property string $action
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Delivery|null $delivery
 * @property-read \App\Models\Driver $driver
 * @property-read string $formatted_action
 * @property-read mixed $formatted_created_at
 * @property-read mixed $formatted_updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Shot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shot query()
 * @method static \Illuminate\Database\Eloquent\Builder|Shot whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shot whereDeliveryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shot whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shot whereId($value)
 */
	class Shot extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\State
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\City[] $cities
 * @property-read int|null $cities_count
 * @property-read mixed $formatted_created_at
 * @property-read mixed $formatted_updated_at
 * @method static \Database\Factories\StateFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|State newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|State newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|State query()
 * @method static \Illuminate\Database\Eloquent\Builder|State whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereUpdatedAt($value)
 */
	class State extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\Step
 *
 * @property int $id
 * @property int $delivery_id
 * @property mixed|null $location
 * @property string|null $street_number
 * @property string|null $street_name
 * @property string|null $district
 * @property string|null $city
 * @property string|null $state
 * @property string|null $info
 * @property int|null $prev_id
 * @property int|null $next_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $line
 * @property int|null $distance
 * @property int|null $duration
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read string $formatted_address
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Step newModelQuery()
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Step newQuery()
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Step query()
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Step whereCity($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Step whereCreatedAt($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Step whereDeliveryId($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Step whereDistance($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Step whereDistrict($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Step whereDuration($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Step whereId($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Step whereInfo($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Step whereLine($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Step whereLocation($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Step whereNextId($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Step wherePrevId($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Step whereState($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Step whereStatus($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Step whereStreetName($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Step whereStreetNumber($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|Step whereUpdatedAt($value)
 */
	class Step extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $pub_id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password
 * @property string|null $remember_token
 * @property string|null $phone
 * @property string|null $info
 * @property int $city_id
 * @property mixed|null $area
 * @property \Illuminate\Support\Collection|array $radiuses
 * @property string $delivery_constraint
 * @property int $return_fee_charged
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $report_period
 * @property string|null $report_period_option
 * @property int $return_fee_paid
 * @property string $receipt_confirmation
 * @property string $charge_style
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $banned_at
 * @property string|null $deleted_at
 * @property \App\Models\(int|mixed|\App\Models\string)[] $charge_options
 * @property-read \App\Models\Address|null $address
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\City[] $cities
 * @property-read int|null $cities_count
 * @property-read \App\Models\City|null $city
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Delivery[] $deliveries
 * @property-read int|null $deliveries_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Driver[] $drivers
 * @property-read int|null $drivers_count
 * @property-read bool $banned
 * @property-read array $cities_ids
 * @property-read array $drivers_ids
 * @property-read mixed $formatted_created_at
 * @property-read string $formatted_return_fee_charged
 * @property-read string $formatted_return_fee_paid
 * @property-read string|null $formatted_status
 * @property-read mixed $formatted_updated_at
 * @property-read bool $lastmile
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Report[] $reports
 * @property-read int|null $reports_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User newModelQuery()
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User newQuery()
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User ofCities(array|int $value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User ofCity(array|int $value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User query()
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User whereArea($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User whereBannedAt($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User whereChargeOptions($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User whereChargeStyle($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User whereCityId($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User whereDeliveryConstraint($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User whereEmail($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User whereId($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User whereInfo($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User whereName($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User wherePassword($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User wherePhone($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User wherePubId($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User whereRadiuses($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User whereReceiptConfirmation($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User whereReportPeriod($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User whereReportPeriodOption($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User whereReturnFeeCharged($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User whereReturnFeePaid($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User whereStatus($value)
 * @method static \Ajthinking\LaravelPostgis\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

