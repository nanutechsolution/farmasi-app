<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('general.app_name', 'Farmasi App');
        $this->migrator->add('general.app_address', 'Jl. Contoh No. 123');
        $this->migrator->add('general.app_phone', '081234567890');
    }
};
