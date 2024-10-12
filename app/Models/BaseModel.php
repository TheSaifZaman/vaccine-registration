<?php

namespace App\Models;

use App\Traits\BaseModel\BaseModelTrait;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected const SORT_KEY = 'sort';
    protected const OFFSET_KEY = 'offset';
    protected const LIMIT_KEY = 'limit';
    protected const SEARCH_KEY = 'search';
    protected static array $defaultSortableFields = [];
    public static array $directSortableFields = [];
    public static array $relationalSortableFields = [];
    public static array $directFilterableFields = [];
    protected static array $tempDirectFilterableColumns = [];
    public static array $relatedFilterableFields = [];
    protected static array $tempRelatedFilterableColumns = [];
    public static array $nestedRelatedFilterableFields = [];
    protected static array $tempNestedRelatedFilterableColumns = [];
    public static array $relatedRelations = [];
    public static array $relationships = [];
    protected static array $searchable = [];
    protected static array $fieldList = [];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    use BaseModelTrait;
}
