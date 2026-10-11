<?php
namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class AnalyticsSettingSeeder extends Seeder
{
    public function run(): void
    {
        if (Setting::get('analytics.ga_id') === null) {
            Setting::set('analytics.ga_id', '');
            $this->command->info('Created analytics.ga_id (empty)');
        } else {
            $this->command->info('analytics.ga_id already exists — skipped');
        }
    }
}