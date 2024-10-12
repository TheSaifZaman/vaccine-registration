<?php

namespace Modules\Registration\Models;

use App\Models\BaseModel;
use App\Traits\CustomActionEventTrait;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\VaccineCenter\Models\VaccineCenter;

class Registration extends BaseModel
{
    use HasFactory, HasUlids, CustomActionEventTrait;

    /**
     * @var string[]
     */
    protected $fillable = [
        'nid',
        'name',
        'dob',
        'email',
        'vaccine_center_id',
        'scheduled_date',
        'status',
    ];

    /**
     * @return BelongsTo
     */
    public function vaccineCenter(): BelongsTo
    {
        return $this->belongsTo(VaccineCenter::class);
    }
}
