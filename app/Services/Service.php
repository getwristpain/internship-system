<?php

namespace App\Services;

use App\Helpers\Logger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class Service
{
    protected Model $model;

    /**
     * Service constructor.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Generate a unique cache key based on model, filters, and query parameters.
     *
     * @param  array  $filters
     * @return string
     */
    private function generateCacheKey(array $filters): string
    {
        $baseKey = get_class($this->model) . json_encode($filters);
        return "query_" . md5($baseKey);
    }

    /**
     * Run a query with caching and filtering.
     *
     * @param  \Closure  $queryCallback
     * @param  array  $filters
     * @param  int  $cacheDuration
     * @return mixed
     */
    public function cacheQuery(\Closure $queryCallback, array $filters = [], int $cacheDuration = 60): mixed
    {
        $cacheKey = $this->generateCacheKey($filters);

        return Cache::remember($cacheKey, $cacheDuration, function () use ($queryCallback) {
            return $queryCallback($this->model);
        });
    }

    /**
     * Retrieve all records from the model.
     *
     * @param  int  $cacheDuration
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(int $cacheDuration = 60): Collection
    {
        return $this->cacheQuery(fn($query) => $query->all(), ['key' => 'all'], $cacheDuration);
    }

    /**
     * Retrieve a single record by ID.
     *
     * @param  int  $id
     * @param  int  $cacheDuration
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getById(int $id, int $cacheDuration = 60): ?Model
    {
        return $this->cacheQuery(fn($query) => $query->findOrFail($id), ['id' => $id], $cacheDuration);
    }

    /**
     * Retrieve a single record by ID with related data.
     *
     * @param  int  $id
     * @param  array  $with
     * @param  int  $cacheDuration
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getByIdWithRelations(int $id, array $with = [], int $cacheDuration = 60): ?Model
    {
        return $this->cacheQuery(fn($query) => $query->with($with)->findOrFail($id), ['id' => $id, 'relations' => $with], $cacheDuration);
    }

    /**
     * Create a new record.
     *
     * @param  array  $data
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Throwable
     */
    public function create(array $data): Model
    {
        try {
            $model = $this->model->create($data);
            Logger::handle('info', 'Created new record');
            return $model;
        } catch (\Throwable $th) {
            Logger::handle('error', 'Error creating record: ' . $th->getMessage(), $th);
            throw $th;
        }
    }

    /**
     * Insert multiple records at once.
     *
     * @param  array  $data
     * @return int
     *
     * @throws \Throwable
     */
    public function bulkInsert(array $data): int
    {
        try {
            $inserted = $this->model->insert($data);
            Logger::handle('info', 'Bulk inserted records');
            return $inserted;
        } catch (\Throwable $th) {
            Logger::handle('error', 'Error bulk inserting records: ' . $th->getMessage(), $th);
            throw $th;
        }
    }

    /**
     * Update an existing record by ID.
     *
     * @param  int  $id
     * @param  array  $data
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Throwable
     */
    public function update(int $id, array $data): Model
    {
        try {
            $model = $this->getById($id);
            $model->update($data);
            Cache::forget($this->generateCacheKey(['id' => $id]));
            Logger::handle('info', "Updated record with ID {$id}");
            return $model;
        } catch (\Throwable $th) {
            Logger::handle('error', "Error updating record with ID {$id}: " . $th->getMessage(), $th);
            throw $th;
        }
    }

    /**
     * Delete a record by ID.
     *
     * @param  int  $id
     * @return bool
     *
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        try {
            $model = $this->getById($id);
            $model->delete();
            Cache::forget($this->generateCacheKey(['id' => $id]));
            Logger::handle('info', "Deleted record with ID {$id}");
            return true;
        } catch (\Throwable $th) {
            Logger::handle('error', "Error deleting record with ID {$id}: " . $th->getMessage(), $th);
            throw $th;
        }
    }

    /**
     * Restore a soft-deleted record by ID.
     *
     * @param  int  $id
     * @return bool
     *
     * @throws \Throwable
     */
    public function restore(int $id): bool
    {
        try {
            $model = $this->model->withTrashed()->findOrFail($id);
            $model->restore();
            Logger::handle('info', "Restored record with ID {$id}");
            return true;
        } catch (\Throwable $th) {
            Logger::handle('error', "Error restoring record with ID {$id}: " . $th->getMessage(), $th);
            throw $th;
        }
    }

    /**
     * Permanently delete a soft-deleted record by ID.
     *
     * @param  int  $id
     * @return bool
     *
     * @throws \Throwable
     */
    public function forceDelete(int $id): bool
    {
        try {
            $model = $this->model->withTrashed()->findOrFail($id);
            $model->forceDelete();
            Cache::forget($this->generateCacheKey(['id' => $id]));
            Logger::handle('info', "Permanently deleted record with ID {$id}");
            return true;
        } catch (\Throwable $th) {
            Logger::handle('error', "Error permanently deleting record with ID {$id}: " . $th->getMessage(), $th);
            throw $th;
        }
    }

    /**
     * Find records that match given conditions.
     *
     * @param  array  $conditions
     * @param  int  $cacheDuration
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findWhere(array $conditions, int $cacheDuration = 60): Collection
    {
        return $this->cacheQuery(fn($query) => $query->where($conditions)->get(), $conditions, $cacheDuration);
    }

    /**
     * Count the number of records that match given conditions.
     *
     * @param  array  $conditions
     * @param  int  $cacheDuration
     * @return int
     */
    public function countWhere(array $conditions, int $cacheDuration = 60): int
    {
        return $this->cacheQuery(fn($query) => $query->where($conditions)->count(), $conditions, $cacheDuration);
    }

    /**
     * Paginate records based on the specified page size.
     *
     * @param  int  $perPage
     * @param  int  $cacheDuration
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(int $perPage = 20, int $cacheDuration = 60): LengthAwarePaginator
    {
        return $this->cacheQuery(fn($query) => $query->paginate($perPage), ['per_page' => $perPage], $cacheDuration);
    }

    /**
     * Run a database transaction.
     *
     * @param  callable  $callback
     * @return mixed
     *
     * @throws \Throwable
     */
    public function transaction(callable $callback): mixed
    {
        try {
            return DB::transaction($callback);
        } catch (\Throwable $th) {
            Logger::handle('error', 'Error in transaction: ' . $th->getMessage(), $th);
            throw $th;
        }
    }

    /**
     * Process records in batches.
     *
     * @param  int  $batchSize
     * @param  callable  $callback
     * @return void
     */
    public function batchProcess(int $batchSize = 100, callable $callback): void
    {
        $this->model->chunk($batchSize, function ($records) use ($callback) {
            foreach ($records as $record) {
                $callback($record);
            }
        });
    }
}
