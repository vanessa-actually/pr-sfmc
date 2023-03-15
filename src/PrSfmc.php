<?php

namespace Dmgroup\PrSfmc;

use Carbon\Carbon;
use Dmgroup\PrSfmc\Models\SfmcTransmission;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PrSfmc
{
    public static \stdClass $data;
    public static \stdClass $source;
    public static \stdClass $optin;
    public static \stdClass $activity;
    public static $transmissionLog;
    public static int $transmittableId;

    public function __construct()
    {
        self::$transmissionLog = Log::build([
            'driver' => 'daily',
            'path' => storage_path('logs/sfmc-transmissions.log'),
        ]);

        self::$data = new \stdClass();
        self::$source = new \stdClass();
        self::$optin = new \stdClass();
        self::$activity = new \stdClass();

        self::$data->entryType = 'consumer';
        self::$data->contactType = 'B2C';
        self::$data->email = '';
        self::$data->collectorCreatedDate = Carbon::now()->format('Y-m-d\TH:i:s\Z');

        //optionals
        self::$data->gender = '';
        self::$data->title = '';
        self::$data->firstname = '';
        self::$data->lastname = '';
        self::$data->birthday = '';
        self::$data->countryCode = 'IT';
        self::$data->languageCode = 'it';
        self::$data->jobTitle = '';
        self::$data->nps = 1;

        self::$source->prEntity = config('pr-sfmc.prEntity','ITALY');
        self::$source->touchPointName = config('pr-sfmc.touchPointName','ITALY_Beat_Form_Event');
        self::$source->touchPointType = "Webform";
        self::$source->touchPointDescription = "";

        self::$activity->activityId = config('pr-sfmc.activityId');
        self::$activity->activityName = config('pr-sfmc.activityName');
        self::$activity->activityType = config('pr-sfmc.activityType');

        self::$data->source = [
            self::$source,
        ];
        self::$data->activities = [
            self::$activity,
        ];

    }
    public static function sendData(): ?SfmcTransmission
    {
        if (empty(self::$transmittableId)) {
            self::$transmissionLog->error('no transmittable id specified');
            return null;
        }

        $result = null;
        $transmission = null;

        switch (self::$data->entryType) {
            case 'consumer':
                $transmission = self::sendConsumerData();
                break;
            default:
                self::$transmissionLog->error('no data type specified');
                return null;
        }
        return $transmission;
    }

    public static function sendConsumerData():  ?SfmcTransmission
    {
        $url = config('pr-sfmc.api.host').'/consumer/data/entry';

        $result = Http::withBody(json_encode(self::$data), 'application/json')
            ->withHeaders(['api_key'=> config('pr-sfmc.api.token','uu78jv3tt5ma5mdsabnsnhvv')])
            ->post($url);

        $transmission = new SfmcTransmission();
        $transmission->endpoint = $url;
        $transmission->response_status = $result->status();
        $transmission->request_dump = json_encode(self::$data);
        $transmission->response_dump = $result->body();
        $transmission->transmittable_type = config('pr-sfmc.transmittable_type');
        $transmission->transmittable_id = self::$transmittableId;

        $responseBody = json_decode($result->body(),true);

        if ($responseBody) {
            if (array_key_exists('x-rejection-errors',$responseBody)) {
                $transmission->transmission_status = false;
                $transmission->transmission_error_message = $responseBody['x-rejection-errors']['message'];
            } else {
                $transmission->transmission_status = true;
            }
            $transmission->sfmc_entry_id = $responseBody['entry_public_id'];
        }

        if ($transmission->save()) {
            return $transmission;
        } else {
            return null;
        }
    }

    public static function setEntryType(string $entryType): ?string
    {
        $allowedEntryTypes = [
            'consumer',
        ];

        if (!in_array($entryType,$allowedEntryTypes)) {
            self::$transmissionLog->error('entity type not permitted or not implemented, yet');
            return null;
        }
        return self::$data->entryType = $entryType;
    }

    public static function setCreatedAt(Carbon $createdAt): string
    {
        return self::$data->collectorCreatedDate = $createdAt->format('Y-m-d\TH:i:s\Z');

    }

    public static function setEmail(string $email): string
    {
        return self::$data->email = $email;
    }

    public static function setGender(string $gender): string
    {
        return self::$data->gender = $gender;
    }
    public static function setTitle(string $title): string
    {
        return self::$data->title = $title;
    }
    public static function setFirstname(string $firstName): string
    {
        return self::$data->firstname = $firstName;
    }
    public static function setLastname(string $lastname): string
    {
        return self::$data->lastname = $lastname;
    }
    public static function setBirthday(Carbon $birthday): string
    {
        return self::$data->birthday = $birthday->format('Y-M-D');
    }

    public static function setJobTitle(string $jobTitle): string
    {
        return self::$data->jobTitle = $jobTitle;
    }

    public static function setTransmittableId($transmittableId)
    {
        return self::$transmittableId = $transmittableId;
    }

    public static function setContactType($contactType)
    {
        $allowedContactTypes = [
            'B2C',
            'B2B',
            'B2E',
            'HNW',
            'SHA',
        ];

        if (!in_array($contactType,$allowedContactTypes)) {
            self::$transmissionLog->error('contact type not permitted');
            return null;
        }
        return self::$data->contactType = $contactType;
    }

    public static function addPhoneNumber(string $phoneNumber, string $phoneType): \stdClass
    {
        $phone = new \stdClass();
        $phone->type = $phoneType;
        $phone->value = $phoneNumber;

        self::$data->phone[] = $phone;
        return $phone;
    }

    public static function addConsent(bool $accepted, string $consentText): \stdClass
    {
        $consent = new \stdClass();
        $consent->brand = config('pr-sfmc.brand');
        $consent->optInStatus = (int)$accepted;
        $consent->legalConsent = $consentText;

        self::$data->optIns[] = $consent;
        return $consent;
    }

    public static function getBodyJson(): string
    {
        return json_encode(self::$data);
    }


}
