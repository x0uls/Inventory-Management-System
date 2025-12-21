<?php

namespace App\Traits;

trait RecyclesIds
{
    /**
     * Find the first available ID in the sequence.
     *
     * @param  string  $modelClass  The class name of the model (e.g., Category::class)
     * @param  string  $primaryKey  The primary key column name (e.g., 'category_id')
     * @return int The first available ID
     */
    public static function findFirstAvailableId($modelClass, $primaryKey)
    {
        // Get all existing IDs sorted ascending
        $ids = $modelClass::orderBy($primaryKey, 'asc')->pluck($primaryKey)->toArray();

        // If no records, start at 1
        if (empty($ids)) {
            return 1;
        }

        // Iterate to find the gap
        $expectedId = 1;
        foreach ($ids as $id) {
            if ($id != $expectedId) {
                return $expectedId; // Gap found!
            }
            $expectedId++;
        }

        // No gap found, return next number
        return $expectedId;
    }
}
