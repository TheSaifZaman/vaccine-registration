<?php

namespace Modules\VaccineCenter\Database\Seeders;

use App\Enums\EntityTypeEnum;
use App\Enums\LogLabelEnum;
use App\Enums\LogTypeEnum;
use App\Helpers\LogHelper;
use Exception;
use Illuminate\Database\Seeder;
use Modules\VaccineCenter\Models\VaccineCenter;

class VaccineCenterSeeder extends Seeder
{
    public function run()
    {
        try {
            $centers = loadConfigData('vaccine_center_seed');
            $centerIdentifiers = array_column($centers, 'name');
            VaccineCenter::whereNotIn('name', $centerIdentifiers)
                ->whereDoesntHave('registrations')
                ->delete();
            foreach ($centers as $center) {
                $center['entity_type'] = EntityTypeEnum::PERMANENT->name;
                VaccineCenter::updateOrCreate(
                    ['name' => $center['name']],
                    $center
                );
            }
        } catch (Exception $exception) {
            LogHelper::factory()
                ->setExceptionOrMessage($exception)
                ->setLabel(LogLabelEnum::CREATE->value)
                ->setLogType(LogTypeEnum::Error)
                ->log();
        }
    }
}

