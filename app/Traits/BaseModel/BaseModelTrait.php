<?php


namespace App\Traits\BaseModel;

use App\Contracts\ApiResponseInterface;
use App\Enums\LogLabelEnum;
use App\Enums\ResponseMessageEnum;
use App\Traits\BaseModel\Actions\StoreDataTrait;
use Exception;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use stdClass;
use Symfony\Component\HttpFoundation\Response;

trait BaseModelTrait

{
    use AuthorizesRequests;
    use StoreDataTrait;

    /**
     * index data(paginated, sorted, searched)
     *
     * Will do further refactor over the time
     *
     * @param Model|Builder $model
     * @param array $input
     * @param ApiResponseInterface|null $apiResponseHelper
     * @param null $resource
     * @param array $relationships
     * @return JsonResponse|Builder
     */
    public function scopeIndexData(
        Model|Builder        $model,
        array                $input = [],
        ApiResponseInterface $apiResponseHelper = null,
                             $resource = null,
        array                $relationships = []
    ): Builder|JsonResponse
    {
        try {
            $query = $model;
            $modelInstance = $query->getModel();
            self::setPrerequsiteValues($modelInstance);
            [$offset, $limit, $sortKey, $searchKey, $input] = self::extractQueryParameters($input);
            [$count, $query] = self::processIndexQuery($query, $modelInstance, $input, $sortKey, $searchKey);
            if (!isset($apiResponseHelper)) {
                return $query;
            }
            $result = self::processIndexResult($query, $offset, $limit, $count, $resource, $relationships);
            return $apiResponseHelper::returnSuccess($result);
        } catch (Exception $e) {
            return $apiResponseHelper::returnError(ResponseMessageEnum::BAD_REQUEST->value, Response::HTTP_BAD_REQUEST, $e, LogLabelEnum::INDEX->value);
        }
    }

    /**
     * @param Builder $query
     * @param int $offset
     * @param int $limit
     * @param int $count
     * @param null $resource
     * @param array $relationships
     * @return stdClass
     */
    protected static function processIndexResult(Builder $query, int $offset, int $limit, int $count, $resource = null, array $relationships = []): stdClass
    {
        try {
            $query = ($limit === -1) ? $query : $query->offset($offset)->limit($limit);
            if (!empty($relationships)) {
                $query = $query->with($relationships);
            }
            $items = $query->get();
            unset($query);
            $result = new stdClass();
            $result->items = $resource ? $resource::collection($items)->resolve() : $items;
            $result->metadata = self::getPaginationMetadata($offset, $limit, $count);
            return $result;
        } finally {
            if (isset($query)) {
                unset($query);
            }
        }
    }

    /**
     * @param $query
     * @param $modelInstance
     * @param $input
     * @param $sortKey
     * @param $searchKey
     * @return array
     */
    protected static function processIndexQuery($query, $modelInstance, $input, $sortKey = null, $searchKey = null): array
    {
        $query = self::applyGlobalSearch($modelInstance::$searchable, $searchKey, $query);
        $query = self::applyFiltering($modelInstance, $input, $query);
        return [$query->count(), self::sort($query, $modelInstance, $sortKey)];
    }

    /**
     * @param $modelInstance
     * @return void
     */
    protected static function setPrerequsiteValues($modelInstance): void
    {
        $model = new $modelInstance();
        self::$fieldList = array_merge($model->getFillable(), [
            'id',
            'created_at',
            'updated_at',
        ]);
        self::setFilterAbleColumns($model);
    }

    /**
     * @param $input
     * @return array
     */
    protected static function extractQueryParameters($input): array
    {
        $offset = (int)($input[self::OFFSET_KEY] ?? config('settings.pagination.default_offset'));
        $limit = (int)($input[self::LIMIT_KEY] ?? config('settings.pagination.default_limit'));
        $sortKey = $input[self::SORT_KEY] ?? null;
        $searchKey = $input[self::SEARCH_KEY] ?? null;

        return [
            $offset,
            $limit,
            $sortKey,
            $searchKey,
            unsetArrayKeys($input,
                [
                    self::OFFSET_KEY,
                    self::LIMIT_KEY,
                    self::SORT_KEY,
                    self::SEARCH_KEY,
                    'q',
                ]
            )
        ];
    }

    /**
     * @param $modelInstance
     * @param $input
     * @param $query
     * @return mixed
     */
    protected static function applyFiltering($modelInstance, $input, $query): mixed
    {
        if (empty($input)) {
            return $query;
        }

        $tableName = $modelInstance->getTable();

        foreach (array_keys($input) as $key) {
            $query = self::search($tableName, $key, $input[$key], $query);
        }
        return $query;
    }

    /**
     * @param $searchableFields
     * @param $searchKey
     * @param $query
     * @return mixed
     */
    protected static function applyGlobalSearch($searchableFields, $searchKey, $query): mixed
    {
        if (!$searchKey || empty($searchableFields)) {
            return $query;
        }
        return self::globalSearch($searchableFields, $searchKey, $query);
    }

    /**
     * sort query
     *
     * @param Builder $query
     * @param $modelInstance
     * @param string|null $value
     * @return Builder
     */
    public static function sort(Builder $query, $modelInstance, string $value = null): Builder
    {
        $value = $value ?? $modelInstance::$defaultSortableFields;
        if (empty($value)) {
            return $query;
        }
        $columns = is_string($value) ? explode(',', $value) : $value;
        $directSortableFields = array_unique(array_merge($modelInstance::$directSortableFields, self::$fieldList));

        foreach ($columns as $column) {
            $order = str_starts_with($column, '-') ? 'DESC' : 'ASC';
            $column = ltrim($column, '-');
            $query = in_array($column, $directSortableFields) ? self::sortByDirectField($query, $column, $order) : $query;
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @param string $field
     * @param string $order
     * @return Builder
     */
    public static function sortByDirectField(Builder $query, string $field, string $order = 'ASC'): Builder
    {
        return $query->orderBy($field, $order);
    }

    /**
     * @param $model
     * @return void
     */
    public static function setFilterAbleColumns($model = null): void
    {
        self::$tempDirectFilterableColumns = $model ? $model::$directFilterableFields : [];
        self::$tempRelatedFilterableColumns = $model ? $model::$relatedFilterableFields : [];
        self::$tempNestedRelatedFilterableColumns = $model ? $model::$nestedRelatedFilterableFields : [];
    }

    /**
     * calls eligible function for search
     *
     * @param $tableName
     * @param $key
     * @param $value
     * @param $query
     * @return mixed
     */
    public static function search($tableName, $key, $value, $query): mixed
    {
        $model = $query->getModel();
        $fieldList = self::getFieldList($model);

        if (!self::isKeyInFieldList($key, $fieldList)) {
            return $query;
        }

        self::isDirectFilterable($key, self::$tempDirectFilterableColumns, $fieldList) &&
        $query = self::applyDirectSearch($key, $value, $query, $tableName);
        return $query;
    }

    /**
     * @param $key
     * @param $fieldList
     * @return bool
     */
    private static function isKeyInFieldList($key, $fieldList): bool
    {
        return in_array($key, $fieldList);
    }

    /**
     * @param $key
     * @param $directFilterableFields
     * @param $fieldList
     * @return bool
     */
    private static function isDirectFilterable($key, $directFilterableFields, $fieldList): bool
    {
        $array = !empty($directFilterableFields) ? $directFilterableFields : $fieldList;
        return in_array($key, $array);
    }

    /**
     * @param $key
     * @param $value
     * @param $query
     * @param string|null $tableName
     * @return mixed
     */
    private static function applyDirectSearch($key, $value, $query, string $tableName = null): mixed
    {
        return self::directSearch($key, $value, $query, $tableName);
    }

    /**
     * @param $model
     * @return array
     */
    protected static function getFieldList($model): array
    {
        $fields = !empty(self::$fieldList)
            ? self::$fieldList
            : array_unique(
                array_merge(
                    $model->getFillable(),
                    [
                        'id',
                        'created_at',
                        'updated_at',
                        'created_by',
                        'updated_by',
                    ]
                )
            );
        return (!empty(self::$tempDirectFilterableColumns)
            || !empty(self::$tempRelatedFilterableColumns)
            || !empty(self::$tempNestedRelatedFilterableColumns))
            ? array_merge(
                self::$tempDirectFilterableColumns,
                array_keys(self::$tempRelatedFilterableColumns),
                array_keys(self::$tempNestedRelatedFilterableColumns)
            )
            : $fields;
    }

    /**
     * search query
     *
     * @param $key
     * @param $value
     * @param $query
     * @param string|null $tableName
     * @return mixed
     */
    public static function directSearch($key, $value, $query, string $tableName = null): mixed
    {
        $value = (str_starts_with($value, '*') && str_ends_with($value, '*')) ? str_replace('*', '%', $value) : $value;
        $updatedKey = $tableName ? $tableName . '.' . strtolower($key) : strtolower($key);
        return $query->where($updatedKey, 'LIKE', '%' . strtolower($value) . '%');
    }

    /**
     * search query
     *
     * @param $keys
     * @param $value
     * @param $query
     * @return mixed
     */
    protected static function globalSearch($keys, $value, $query): mixed
    {
        $query->where(function ($query) use ($keys, $value) {
            $searchableKeys = $keys;
            $poppedElement = array_shift($searchableKeys);
            $query = self::applySingleSearchCriteria($query, $poppedElement, $value);
            foreach ($searchableKeys as $key) {
                $query = self::applySearchCriteria($query, $key, $value);
            }
        });
        return $query;
    }

    /**
     * @param $query
     * @param $key
     * @param $value
     * @return mixed
     */
    protected static function applySingleSearchCriteria($query, $key, $value): mixed
    {
        return $query->where($key, 'LIKE', '%' . strtolower($value) . '%');
    }

    /**
     * @param $query
     * @param $key
     * @param $value
     * @return mixed
     */
    protected static function applySearchCriteria($query, $key, $value): mixed
    {
        return $query->orWhere($key, 'LIKE', '%' . strtolower($value) . '%');
    }

    /**
     * pagination metadata
     *
     * @param int $offset
     * @param int $limit
     * @param int $count
     * @return array
     */
    protected static function getPaginationMetadata(int $offset, int $limit, int $count): array
    {
        $limitFlag = $limit > 0;

        $currentPage = min(
            ($limitFlag ? (intdiv($offset, $limit) + 1) : 1),
            ($limitFlag ? (intdiv($count, $limit) + 1) : 1)
        );

        $pageCount = $limitFlag ? (int)ceil($count / $limit) : 1;
        $previousOffset = max(0, ($offset - $limit));
        $nextOffset = min($offset + $limit, $count);

        if ($limit === -1) {
            $currentPage = $pageCount = ($count > 0) ? 1 : 0;
            $nextOffset = $previousOffset = 0;
        }

        return [
            'pagination' => [
                'offset' => $offset,
                'limit' => $limit,
                'previousOffset' => $previousOffset,
                'nextOffset' => $nextOffset,
                'currentPage' => $currentPage,
                'pageCount' => $pageCount,
                'totalCount' => $count,
            ],
        ];
    }
}
