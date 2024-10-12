<?php

namespace Modules\VaccineCenter\Models;

use App\Models\BaseModel;
use App\Traits\CustomActionEventTrait;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Registration\Models\Registration;

class VaccineCenter extends BaseModel
{
    use HasFactory, HasUlids, CustomActionEventTrait;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'daily_limit',
        'description',
        'status',
        'entity_type',
    ];

    /**
     * @var array|string[]
     */
    protected static array $defaultSortableFields = ['name'];
    /**
     * @var array|string[]
     */
    protected static array $searchable = ['name'];

    /**
     * @return HasMany
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }
}
