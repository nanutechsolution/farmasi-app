<?php

namespace App\Settings;
use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $app_name;
    public string $app_address;
    public string $app_phone;

    public static function group(): string
    {
        return 'general';
    }
}
