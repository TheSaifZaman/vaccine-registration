<?php

namespace Modules\Registration\Services;


use App\Exceptions\HandledException;
use App\Mail\ScheduleNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Modules\Registration\Enums\RegistrationStatusEnum;
use Modules\Registration\Models\Registration;
use Modules\VaccineCenter\Models\VaccineCenter;

class RegistrationService
{
    /**
     * @param $validatedRequestData
     * @param $registration
     * @param $modelReturnResponseHelper
     * @return mixed|Registration
     * @throws HandledException
     */
    public function storeService($validatedRequestData, $registration, $modelReturnResponseHelper): mixed
    {
        $validatedRequestData['status'] = RegistrationStatusEnum::NOT_SCHEDULED->value;
        $newRegistration = $registration->storeData($validatedRequestData, $modelReturnResponseHelper);
        if (!($newRegistration instanceof Registration)) {
            return $newRegistration;
        }
        $this->scheduleVaccination($newRegistration);
        return $newRegistration;
    }

    /**
     * @param Registration $registration
     * @return void
     * @throws HandledException
     */
    private function scheduleVaccination(Registration $registration): void
    {
        $center = $registration->vaccineCenter;
        $date = Carbon::tomorrow();

        while (true) {
            if ($this->isHoliday($date)) {
                $date = $date->addDay();
                continue;
            }

            if ($this->isSeatAvailableOnSpecificDate($center, $date)) {
                $registration->update(
                    [
                        'scheduled_date' => $date->toDateString(),
                        'status' => RegistrationStatusEnum::SCHEDULED->value,
                    ]
                );
                $this->scheduleNotification($registration, $date);
                break;
            } else {
                $date = $date->addDay();
            }
        }
    }

    /**
     * @param VaccineCenter $center
     * @param $date
     * @return bool
     */
    private function isSeatAvailableOnSpecificDate(VaccineCenter $center, $date): bool
    {
        $scheduledCount = Registration::where('vaccine_center_id', $center->id)
            ->where('scheduled_date', $date->toDateString())
            ->count();

        return $scheduledCount <= $center->daily_limit;
    }

    /**
     * @param $date
     * @return bool
     * @throws HandledException
     */
    private function isHoliday($date): bool
    {
        $holidays = loadConfigData('settings.holidays');
        return in_array($date->format('l'), $holidays);
    }

    /**
     * @param $registration
     * @param $date
     * @return void
     */
    private function scheduleNotification($registration, $date): void
    {
        Mail::to($registration->email)->later(
            $date->copy()->subDay()->setTime(21, 0),
            new ScheduleNotification($registration)
        );
    }

    /**
     * @param $nid
     * @return string
     */
    public function processSearch($nid): string
    {
        $data = $this->fetchStatus($nid);
        $message = "Your current registration status is: {$data['status']}.\r";
        isset($data['date']) && $message .= " Scheduled date is: {$data['date']}.";
        return $message;
    }

    /**
     * @param string $nid
     * @return array
     */
    private function fetchStatus(string $nid): array
    {
        $registration = Registration::where('nid', $nid)->first();
        $scheduledDate = $registration->scheduled_date;
        if (!$scheduledDate) {
            return ['status' => $registration->status];
        }
        $today = Carbon::today();
        if ($today->lte(Carbon::parse($scheduledDate))) {
            return ['status' => $registration->status, 'date' => $registration->scheduled_date];
        }
        $registration->update(['status' => RegistrationStatusEnum::VACCINATED->value]);
        return ['status' => RegistrationStatusEnum::VACCINATED->value];
    }
}
