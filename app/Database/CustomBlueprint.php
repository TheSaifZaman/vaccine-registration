<?php

namespace App\Database;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;

class CustomBlueprint extends Blueprint
{
    /**
     * Add a "deleted by", "created by", "updated by" user column for the table.
     *
     */
    public function actionBy(): ColumnDefinition
    {
        $this->ulid('created_by')->nullable();
        $this->ulid('updated_by')->nullable();
        return $this->ulid('deleted_by')->nullable();
    }

    /**
     * Create a new ulid column on the table with cascading.
     *
     * @param string $column
     */
    public function ulid($column = 'id', $cascadeOn = null, $reference = 'id', $name = null): ColumnDefinition
    {
        $columnDefinition = $this->addColumn('ulid', $column);
        if ($cascadeOn) {
            $this->foreign($column, $name)->references($reference)->on($cascadeOn)->onDelete('cascade');
        }
        return $columnDefinition;
    }

    /**
     * Add a "deleted at", "created at", "updated at" user column for the table.
     *
     */
    public function actionAt(): ColumnDefinition
    {
        $this->timestamp('created_at')->useCurrent();
        $this->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        return $this->softDeletes();
    }

    /**
     * Create a new ulid column on the table with primary key.
     */
    public function id($column = 'id'): ColumnDefinition
    {
        return $this->ulid($column)->primary();
    }
}
