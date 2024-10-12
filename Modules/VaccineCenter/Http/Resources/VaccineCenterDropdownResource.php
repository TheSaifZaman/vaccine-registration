<?php

namespace Modules\VaccineCenter\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VaccineCenterDropdownResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
