<?php

namespace App\Services;

use App\Helpers\Logger;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

abstract class Service
{
    protected Model $model;
    protected string $cacheContext = 'record';
    protected int $cacheDuration = 60;

    /**
     * Service constructor.
     *
     * @param Model $model
     * @param string $cacheContext
     * @param int $cacheDuration
     */
    protected function __construct(Model $model, string $cacheContext = 'record', int $cacheDuration = 60)
    {
        $this->model = $model;
        $this->cacheContext = $cacheContext;
        $this->cacheDuration = $cacheDuration;
    }

    /**
     * Log a message with a specific level.
     *
     * @param string $level
     * @param string $message
     * @param string $context
     * @param \Throwable|null $exception
     *
     * @return string
     */
    protected function logger(string $level, string $message, string $context = '', ?\Throwable $exception = null): string
    {
        return Logger::handle($level, $message, $context, exception: $exception);
    }

    /**
     * Generate a unique cache key based on model, filters, and query parameters.
     *
     * @param array $filters
     *
     * @return string|null
     */
    private function generateCacheKey(array $filters): ?string
    {
        try {
            $baseKey = get_class($this->model) . json_encode($filters);
            $context = Str::slug(Str::lower($this->cacheContext), '_');

            return "query_" . $context . '_' . md5($baseKey);
        } catch (\Throwable $th) {
            $this->logger('error', 'error.generate_failed', 'cache_key', $th);
            throw $th;
        }
    }

    /**
     * Run a query with caching and filtering.
     *
     * @param \Closure $queryCallback
     * @param string $cacheKey
     * @param array $filters
     * @param int $cacheDuration
     *
     * @return mixed
     */
    protected function cacheQuery(\Closure $queryCallback, string $cacheKey = 'key', array $filters = [], int $cacheDuration = 60): mixed
    {
        try {
            $formattedCacheKey = $this->generateCacheKey(array_merge(['cache_key' => $cacheKey], $filters));

            return Cache::remember($formattedCacheKey, $cacheDuration ?? $this->cacheDuration, function () use ($queryCallback) {
                return $queryCallback($this->model);
            });
        } catch (\Throwable $th) {
            $this->logger('error', __('system.error.proccess_failed', ['action' => __('system.action.cached_query')]), $th);
            throw $th;
        }
    }

    /**
     * Retrieve all records from the model.
     *
     * @param array $with
     *
     * @return Collection|null
     */
    protected function getAll(array $with = []): ?Collection
    {
        try {
            return $this->cacheQuery(function ($query) use ($with) {
                if (!empty($with)) {
                    $query->with($with);
                }

                return $query->all();
            }, 'all');
        } catch (\Throwable $th) {
            $this->logger('error', 'Failed to take all records from the model.', $th);
            throw $th;
        }
    }

    /**
     * Retrieve a single record by ID.
     *
     * @param $id
     * @param array $with
     *
     * @return Model|null
     */
    protected function getById($id, array $with = []): ?Model
    {
        try {
            return $this->cacheQuery(function ($query) use ($id, $with) {
                if (!empty($with)) {
                    $query->with($with);
                }

                return $query->find($id);
            }, 'record', ['id' => $id]);
        } catch (\Throwable $th) {
            $this->logger('error', 'Failed to retrieve a single record with ID.', $th);
            throw $th;
        }
    }

    /**
     * Create a new record.
     *
     * @param array $data
     *
     * @return Model
     */
    protected function create(array $data): Model
    {
        try {
            return $this->cacheQuery(function ($query) use ($data) {
                $model = $query->create($data);
                $this->logger('info', 'Created new record');

                return $model;
            }, 'new');
        } catch (\Throwable $th) {
            $this->logger('error', 'Error creating record.', $th);
            throw $th;
        }
    }

    /**
     * Update an existing record by ID.
     *
     * @param $id
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function update($id, array $data, array $context = []): Model
    {
        try {
            $user = $this->getById($id);

            if ($user->update($data)) {
                Cache::forget($this->generateCacheKey(['cache_key' => 'record', 'id' => $id]));
                $this->logger('info', __('system.success.data_updated', $context));
            }

            return $user;
        } catch (\Throwable $th) {
            $this->logger('error', "Error updating record with ID {$id}: " . $th->getMessage(), $th);
            throw $th;
        }
    }

    /**
     * Delete a record by ID.
     *
     * @param $id
     *
     * @return bool
     */
    protected function delete($id): bool
    {
        try {
            $deleted = $this->getById($id)->delete();

            if ($deleted) {
                Cache::forget($this->generateCacheKey(['cache_key' => 'record', 'id' => $id]));
                $this->logger('info', "Deleted record with ID {$id}");
            }

            return $deleted;
        } catch (\Throwable $th) {
            $this->logger('error', "Error deleting record with ID {$id}: " . $th->getMessage(), $th);
            throw $th;
        }
    }

    /**
     * Permanently delete a soft-deleted record by ID.
     *
     * @param $id
     *
     * @return bool
     */
    protected function forceDelete($id): bool
    {
        try {
            $forceDeleted = $this->model->withTrashed()->find($id)->forceDelete();

            if ($forceDeleted) {
                Cache::forget($this->generateCacheKey(['cache_key' => 'record', 'id' => $id]));
                $this->logger('info', "Permanently deleted record with ID {$id}");
            }

            return $forceDeleted;
        } catch (\Throwable $th) {
            $this->logger('error', "Error permanently deleting record with ID {$id}: " . $th->getMessage(), $th);
            throw $th;
        }
    }

    /**
     * Find a single record that match given conditions.
     *
     * @param array $conditions
     * @param array $with
     *
     * @return Model|null
     */
    protected function findOne(array $conditions, array $with = [], bool $createable = false, array $data = []): ?Model
    {
        try {
            $recordData = $this->cacheQuery(function ($query) use ($conditions, $with) {
                if (!empty($with)) {
                    $query->with($with);
                }

                return $query->where($conditions)->first();
            }, 'find_one', $conditions);

            if (empty($recordData) && $createable && !empty($data)) {
                return $this->create($data);
            }

            return $recordData;
        } catch (\Throwable $th) {
            $this->logger('error', 'Failed to find a single record that match given conditions or creating a record.', $th);
            throw $th;
        }
    }

    /**
     * Find records that match given conditions.
     *
     * @param array $conditions
     * @param array $with
     *
     * @return Collection|null
     */
    protected function findWhere(array $conditions, array $with = [], bool $createable = false, array $data = []): ?Collection
    {
        try {
            $recordData = $this->cacheQuery(function ($query) use ($conditions, $with) {
                if (!empty($with)) {
                    $query->with($with);
                }

                return $query->where($conditions)->get();
            }, 'find_where', $conditions);

            if (empty($recordData) && $createable && !empty($data)) {
                return $this->create($data);
            }

            return $recordData;
        } catch (\Throwable $th) {
            $this->logger('error', 'Failed to find records that match given conditions.', $th);
            throw $th;
        }
    }

    /**
     * Run a database transaction.
     *
     * @param callable $callback
     *
     * @return mixed
     */
    protected function transaction(callable $callback): mixed
    {
        try {
            return DB::transaction($callback);
        } catch (\Throwable $th) {
            $this->logger('error', 'Error in transaction.', $th);
            throw $th;
        }
    }

    /**
     * Process records in batches.
     *
     * @param int $batchSize
     * @param callable $callback
     *
     * @return void
     */
    protected function batchProcess(int $batchSize = 100, callable $callback): void
    {
        try {
            $this->model->chunk($batchSize, function ($records) use ($callback) {
                foreach ($records as $record) {
                    $callback($record);
                }
            });
        } catch (\Throwable $th) {
            $this->logger('error', 'Failed to proccess records in batches.', $th);
            throw $th;
        }
    }
}
