# This is pr-sfmc package

This package provides an interface to send entities to PR sfmc instance.


## Installation

You can install the package via composer:

```bash
composer require dmgroup/pr-sfmc
```

add keys in .env file 

```
SFMC_TOKEN= configured when a touchpoint is opened

SFMC_ENTITY= configured by pernod. Defaults to ITALY
SFMC_BRAND=  configured by pernod. 

SFMC_TOUCHPOINT_NAME= configured when a touchpoint is opened

SFMC_ACTIVITY_NAME= configured when a touchpoint is opened
SFMC_ACTIVITY_TYPE= configured by pernod. case sensitive. Must be listed to be accepted otherwise it will fail the transmission
SFMC_ACTIVITY_ID= configured by pernod. case sensitive. Must be listed to be accepted otherwise it will fail the transmission

SFMC_HOST= defaults to staging at https://api.pernod-ricard.io/staging/v3

SFMC_TRANSMITTABLE_TYPE= the model of the entity you want to transmit
```

## Admittable activity ids

- Contest
- Data Migration
- E-commerce
- Enquiry
- Event
- EventBooking
- Horus
- Newsletter
- Profile Center
- Profile Center
- Program Subscription
- Promotional Campaigns
- SFMCInteractiveForm
- Survey
- Training
- Unsubscription
- Visit
- eCommerce transaction

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="pr-sfmc-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="pr-sfmc-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="pr-sfmc-views"
```

## Usage

Add `HasSfmcTransmission` trait this package provides to the entity you want to send like this

```php
<?php
namespace App\Models;

use Dmgroup\PrSfmc\HasSfmcTransmissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory, HasSfmcTransmissions;
    ...

}
```

This brings a few useful methods such as 

`sfmcTransmissions()`
returns all transmissions for a given entity

`successfullyTransmittedAt()`
returns the first successful transmission for a given entity

You can then send a record like this (least recordset admitted): 

```php
$sfmc = new PrSfmc();
$sfmc::setTransmittableId($record->id);
$sfmc::setEmail($record->email);
$transmission = $sfmc::sendData();
```
$transmission holds the completed result and provides following properties

| ---- | ---- |
| property | meaning | 
| ---- | ---- |
| endpoint | the actual endpoint called by transmission |
| response_status | http response status code |
| request_dump | the request dump of the sent entity |
| response_dump | the response dump of the sent entity |
| transmission_status | SFMC transmission status, boolean|
| sfmc_entry_id | SFMC public entry id. It is filled even if transmission is not successful|
| transmission_error_message | if transmission status is false, it holds the errormessage. Please refer to response dump for error details |
| created_at | transmission creation timestamp |


## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Vanessa](https://github.com/vanessa-actually)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
