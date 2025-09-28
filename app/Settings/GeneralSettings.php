<?php

namespace App\Settings;
use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $app_name;
    public string $app_address;
    public string $app_phone;
    public ?string $app_logo;
    public ?float $office_latitude;
    public ?float $office_longitude;

    public int $max_clock_in_distance;

    public static function group(): string
    {
        return 'general';
    }
}
