<?php

namespace App\Repositories;

use App\Exceptions\ApiErrorResponse;
use App\Exceptions\GeneralException;
use App\Repositories\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class Repository.
 */
abstract class Repository implements RepositoryContract
{
    use ApiErrorResponse, Filterable;
    /**
     * The repository model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    protected $modelBaseName;

    /**
     * The query builder.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * Alias for the query limit.
     *
     * @var int
     */
    protected $take;

    /**
     * Array of related models to eager load.
     *
     * @var array
     */
    protected $with = [];

    /**
     * Array of one or more where clause parameters.
     *
     * @var array
     */
    protected $wheres = [];

    /**
     * Array of one or more or where clause parameters.
     *
     * @var array
     */
    protected $orWheres = [];

    /**
     * Array of one or more where in clause parameters.
     *
     * @var array
     */
    protected $whereIns = [];

    /**
     * Array of one or more ORDER BY column/value pairs.
     *
     * @var array
     */
    protected $orderBys = [];

    /**
     * Array of scope methods to call on the model.
     *
     * @var array
     */
    protected $scopes = [];

    /**
     * BaseRepository constructor.
     */
    public function __construct()
    {
        $this->makeModel();
        $this->modelBaseName = class_basename($this->model());
    }

    /**
     * Specify Model class name.
     *
     * @return mixed
     */
    abstract public function model();

    /**
     * @return Model|mixed
     * @throws GeneralException
     */
    public function makeModel()
    {
        try {
            $model = resolve($this->model());
            if (!$model instanceof Model) {
                return $this->errorInternalError("Class {$this->model()} must be an instance of " . Model::class);
            }
            return $this->model = $model;
        } catch (\Exception $exception) {
            $exception->info = [
                'file' => __FILE__,
                'class' => __CLASS__,
                'function' => __FUNCTION__,
                'method' => __METHOD__
            ];
            $exceptionString = createExceptionsString($this->model(), 'make_model_error');
            $errorData = [
                'log_name' => $this->modelBaseName,
                'subject' => $this->model,
                'exception' => $exception
            ];
            logError($errorData);
            return $this->errorInternalError($exceptionString);
        }
    }

    /**
     * Get all the model records in the database.
     *
     * @param array $columns
     *
     * @return Collection|static[]
     */
    public function all(array $columns = ['*'])
    {
        try {
            $this->newQuery()->eagerLoad();
            $models = $this->query->get($columns);
            $this->unsetClauses();
            return $models;
        } catch (\Exception $exception) {
            $exception->info = [
                'file' => __FILE__,
                'class' => __CLASS__,
                'function' => __FUNCTION__,
                'method' => __METHOD__
            ];
            $exceptionString = createExceptionsString($this->model(), 'get_error');
            $errorData = [
                'log_name' => $this->modelBaseName,
                'subject' => $this->model,
                'properties' => ['columns' => $columns],
                'exception' => $exception
            ];
            logError($errorData);
            return $this->errorInternalError($exceptionString);
        }
    }

    /**
     * Count the number of specified model records in the database.
     *
     * @return int
     */
    public function count()
    {
        try {
            $this->newQuery()->eagerLoad()->setClauses()->setScopes();
            $count = $this->query->count();
            $this->unsetClauses();
            return $count;
        } catch (\Exception $exception) {
            $exception->info = [
                'file' => __FILE__,
                'class' => __CLASS__,
                'function' => __FUNCTION__,
                'method' => __METHOD__
            ];
            $exceptionString = createExceptionsString($this->model(), 'get_error');
            $errorData = [
                'log_name' => $this->modelBaseName,
                'subject' => $this->model,
                'exception' => $exception
            ];
            logError($errorData);
            return $this->errorInternalError($exceptionString);
        }
    }

    /**
     * Create a new model record in the database.
     *
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        try {
            $this->unsetClauses();
            return $this->model->create($data);
        } catch (\Exception $exception) {
            $exception->info = [
                'file' => __FILE__,
                'class' => __CLASS__,
                'function' => __FUNCTION__,
                'trait' => __TRAIT__,
                'method' => __METHOD__
            ];
            $exceptionString = createExceptionsString($this->model(), 'create_error');
            $errorData = [
                'log_name' => $this->modelBaseName,
                'subject' => $this->model,
                'properties' => $data,
                'exception' => $exception
            ];
            logError($errorData);
            return $this->errorInternalError($exceptionString);
        }
    }

    public function firstOrCreate(array $data)
    {
        try {
            $this->unsetClauses();
            return $this->model->firstOrCreate($data);
        } catch (\Exception $exception) {
            $exception->info = [
                'file' => __FILE__,
                'class' => __CLASS__,
                'function' => __FUNCTION__,
                'trait' => __TRAIT__,
                'method' => __METHOD__
            ];
            $exceptionString = createExceptionsString($this->model(), 'create_error');
            $errorData = [
                'log_name' => $this->modelBaseName,
                'subject' => $this->model,
                'properties' => $data,
                'exception' => $exception
            ];
            logError($errorData);
            return $this->errorInternalError($exceptionString);
        }
    }

    /**
     * Create one or more new model records in the database.
     *
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function createMultiple(array $data)
    {
        try {
            $this->unsetClauses();
            return $this->model->insert($data);
        } catch (\Exception $exception) {
            $exception->info = [
                'file' => __FILE__,
                'class' => __CLASS__,
                'function' => __FUNCTION__,
                'method' => __METHOD__
            ];
            $exceptionString = createExceptionsString($this->model(), 'create_error');
            $errorData = [
                'log_name' => $this->modelBaseName,
                'subject' => $this->model,
                'properties' => $data,
                'exception' => $exception
            ];
            logError($errorData);
            return $this->errorInternalError($exceptionString);
        }
    }

    /**
     * Delete one or more model records from the database.
     *
     * @return mixed
     */
    public function delete()
    {
        try {
            $this->newQuery()->setClauses()->setScopes();
            $result = $this->query->delete();
            $this->unsetClauses();
            return $result;
        } catch (\Exception $exception) {
            $exception->info = [
                'file' => __FILE__,
                'class' => __CLASS__,
                'function' => __FUNCTION__,
                'method' => __METHOD__
            ];
            $exceptionString = createExceptionsString($this->model(), 'delete_error');
            $errorData = [
                'log_name' => $this->modelBaseName,
                'subject' => $this->model,
                'exception' => $exception
            ];
            logError($errorData);
            return $this->errorInternalError($exceptionString);
        }
    }

    /**
     * Delete the specified model record from the database.
     *
     * @param $id
     *
     * @return bool|null
     * @throws \Exception
     */
    public function deleteById($id): bool
    {
        try {
            $this->unsetClauses();
            return $this->getById($id)->delete();
        } catch (\Exception $exception) {
            $exception->info = [
                'file' => __FILE__,
                'class' => __CLASS__,
                'function' => __FUNCTION__,
                'method' => __METHOD__
            ];
            $exceptionString = createExceptionsString($this->model(), 'delete_error');
            $errorData = [
                'log_name' => $this->modelBaseName,
                'subject' => $this->model,
                'properties' => ['id' => $id],
                'exception' => $exception
            ];
            logError($errorData);
            return $this->errorInternalError($exceptionString);
        }
    }

    /**
     * Delete the specified model record from the database.
     *
     * @param $model
     * @return bool|null
     */
    public function deleteByModel($model)
    {
        try {
            if ($model->delete()) {
                return $model;
            }
            return $this->errorNotFound();
        } catch (\Exception $exception) {
            $exception->info = [
                'file' => __FILE__,
                'class' => __CLASS__,
                'function' => __FUNCTION__,
                'method' => __METHOD__
            ];
            $exceptionString = createExceptionsString($this->model(), 'delete_error');
            $data = [
                'log_name' => $this->modelBaseName,
                'subject' => $model,
                'properties' => $model,
                'exception' => $exception
            ];
            logError($data);
            return $this->errorInternalError($exceptionString);
        }
    }

    /**
     * Delete multiple records.
     *
     * @param array $ids
     *
     * @return int
     */
    public function deleteMultipleById(array $ids): int
    {
        try {
            return $this->model->destroy($ids);
        } catch (\Exception $exception) {
            $exception->info = [
                'file' => __FILE__,
                'class' => __CLASS__,
                'function' => __FUNCTION__,
                'method' => __METHOD__
            ];
            $exceptionString = createExceptionsString($this->model(), 'delete_error');
            $errorData = [
                'log_name' => $this->modelBaseName,
                'subject' => $this->model,
                'properties' => ['ids' => $ids],
                'exception' => $exception
            ];
            logError($errorData);
            return $this->errorInternalError($exceptionString);
        }
    }

    /**
     * Get the first specified model record from the database.
     *
     * @param array $columns
     *
     * @return Model|static
     */
    public function first(array $columns = ['*'])
    {
        try {
            $this->newQuery()->eagerLoad()->setClauses()->setScopes();
            $model = $this->query->firstOrFail($columns);
            $this->unsetClauses();
            return $model;
        } catch (\Exception $exception) {
            $exception->info = [
                'file' => __FILE__,
                'class' => __CLASS__,
                'function' => __FUNCTION__,
                'method' => __METHOD__
            ];
            $exceptionString = createExceptionsString($this->model(), 'get_error');
            $model = empty($model) ? $this->model : $model;
            $errorData = [
                'log_name' => $this->modelBaseName,
                'subject' => $model,
                'properties' => ['columns' => $columns],
                'exception' => $exception
            ];
            logError($errorData);
            return $this->errorInternalError($exceptionString);
        }
    }

    /**
     * Get all the specified model records in the database.
     *
     * @param array $columns
     *
     * @return Collection|static[]
     */
    public function get(array $columns = ['*'])
    {
        try {
            $this->newQuery()->eagerLoad()->setClauses()->setScopes();
            $models = $this->query->get($columns);
            $this->unsetClauses();
            return $models;
        } catch (\Exception $exception) {
            $exception->info = [
                'file' => __FILE__,
                'class' => __CLASS__,
                'function' => __FUNCTION__,
                'method' => __METHOD__
            ];
            $exceptionString = createExceptionsString($this->model(), 'get_error');
            $model = empty($models) ? $this->model : $models;
            $errorData = [
                'log_name' => $this->modelBaseName,
                'subject' => $model,
                'properties' => ['columns' => $columns],
                'exception' => $exception
            ];
            logError($errorData);
            return $this->errorInternalError($exceptionString);
        }
    }

    /**
     * Get the specified model record from the database.
     *
     * @param       $id
     * @param array $columns
     *
     * @return Collection|Model
     */
    public function getById($id, array $columns = ['*'])
    {
        try {
            $this->unsetClauses();
            $this->newQuery()->eagerLoad();
            return $this->query->findOrFail($id, $columns);
        } catch (\Exception $exception) {
            $exception->info = [
                'file' => __FILE__,
                'class' => __CLASS__,
                'function' => __FUNCTION__,
                'method' => __METHOD__
            ];
            $exceptionString = createExceptionsString($this->model(), 'get_error');
            $errorData = [
                'log_name' => $this->modelBaseName,
                'subject' => $this->model,
                'properties' => ['id' => $id, 'columns' => $columns],
                'exception' => $exception
            ];
            logError($errorData);
            return $this->errorInternalError($exceptionString);
        }
    }

    /**
     * @param       $item
     * @param       $column
     * @param array $columns
     *
     * @return Model|null|static
     */
    public function getByColumn($item, $column, array $columns = ['*'])
    {
        try {
            $this->unsetClauses();
            $this->newQuery()->eagerLoad();
            return $this->query->where($column, $item)->first($columns);
        } catch (\Exception $exception) {
            $exception->info = [
                'file' => __FILE__,
                'class' => __CLASS__,
                'function' => __FUNCTION__,
                'method' => __METHOD__
            ];
            $exceptionString = createExceptionsString($this->model(), 'get_error');
            $errorData = [
                'log_name' => $this->modelBaseName,
                'subject' => $this->model,
                'properties' => ['item' => $item, 'column' => $column, 'columns' => $columns],
                'exception' => $exception
            ];
            logError($errorData);
            return $this->errorInternalError($exceptionString);
        }
    }

    /**
     * @param int $limit
     * @param array $columns
     * @param string $pageName
     * @param null $page
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($limit = 25, array $columns = ['*'], $pageName = 'page', $page = null)
    {
        try {
            if ($limit == -1) return $this->get($columns);
            $this->newQuery()->eagerLoad()->setClauses()->setScopes();
            $models = $this->query->paginate((int)$limit, $columns, $pageName, (int)$page);
            $this->unsetClauses();
            return $models;
        } catch (\Exception $exception) {
            $exception->info = [
                'file' => __FILE__,
                'class' => __CLASS__,
                'function' => __FUNCTION__,
                'method' => __METHOD__
            ];
            $exceptionString = createExceptionsString($this->model(), 'get_error');
            $model = empty($models) ? $this->model : $models;
            $data = [
                'log_name' => $this->modelBaseName,
                'subject' => $model,
                'properties' => ['where' => $this->wheres, 'whereIns' => $this->whereIns, 'orderBys' => $this->orderBys, 'takes' => $this->take],
                'exception' => $exception
            ];
            logError($data);
            return $this->errorInternalError($exceptionString);
        }
    }

    /**
     * Update the specified model record in the database.
     *
     * @param $model
     * @param array $data
     * @param array $options
     *
     * @return Collection|Model
     */
    public function updateByModel($model, array $data, array $options = [])
    {
        try {
            if ($model->update($data, $options)) {
                return $model;
            }
            return $this->errorNotFound();
        } catch (\Exception $exception) {
            $exception->info = [
                'file' => __FILE__,
                'class' => __CLASS__,
                'function' => __FUNCTION__,
                'method' => __METHOD__
            ];
            $exceptionString = createExceptionsString($this->model(), 'update_error');
            $data = [
                'log_name' => $this->modelBaseName,
                'subject' => $model,
                'properties' => $data,
                'exception' => $exception
            ];
            logError($data);
            return $this->errorInternalError($exceptionString);
        }
    }


    /**
     * Update the specified model record in the database.
     *
     * @param       $id
     * @param array $data
     * @param array $options
     *
     * @return Collection|Model
     */
    public function updateById($id, array $data, array $options = [])
    {
        try {
            $this->unsetClauses();
            $model = $this->getById($id);
            $model->update($data, $options);
            return $model;
        } catch (\Exception $exception) {
            $exception->info = [
                'file' => __FILE__,
                'class' => __CLASS__,
                'function' => __FUNCTION__,
                'method' => __METHOD__
            ];
            $exceptionString = createExceptionsString($this->model(), 'update_error');
            $model = empty($modelCreated) ? $this->model : $modelCreated;
            $errorData = [
                'log_name' => $this->modelBaseName,
                'subject' => $model,
                'properties' => $data,
                'exception' => $exception
            ];
            logError($errorData);
            return $this->errorInternalError($exceptionString);
        }
    }

    /**
     * Set the query limit.
     *
     * @param int $limit
     *
     * @return $this
     */
    public function limit($limit)
    {
        $this->take = $limit;
        return $this;
    }

    /**
     * Set an ORDER BY clause.
     *
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function orderBy($column, $direction = 1)
    {
        if (!$direction) {
            $direction = 'desc';
        } else {
            $direction = 'asc';
        }
        $this->orderBys[] = compact('column', 'direction');
        return $this;
    }

    /**
     * Add a simple where clause to the query.
     *
     * @param string $column
     * @param string $value
     * @param string $operator
     *
     * @return $this
     */
    public function where($column, $value, $operator = '=')
    {
        $this->wheres[] = compact('column', 'value', 'operator');
        return $this;
    }

    /**
     * Add a simple where clause to the query when parameters are passed in array.
     *
     * @param $conditions
     * @return $this
     */
    public function whereArray($conditions)
    {
        if (empty($conditions[0])) {
            return $this;
        }
        if (is_array($conditions[0]) > 0) {
            foreach ($conditions as $value) {
                if (!empty($value[1]) && is_array($value[1])) {
                    $this->whereIn($value[0], $value[1]);
                } else {
                    $this->wheres[] = array(
                        'column' => $value[0],
                        'value' => empty($value[1]) ? '=' : $value[1],
                        'operator' => empty($value[2]) ? '=' : $value[2]
                    );
                }
            }
        } else {
            return $this->where($conditions[0], $conditions[1], empty($conditions[2]) ? '=' : $conditions[2]);
        }
        return $this;
    }

    /**
     * Add a simple where in clause to the query.
     *
     * @param string $column
     * @param mixed $values
     *
     * @return $this
     */
    public function whereIn($column, $values)
    {
        $values = is_array($values) ? $values : [$values];
        $this->whereIns[] = compact('column', 'values');
        return $this;
    }

    /**
     * Set Eloquent relationships to eager load.
     *
     * @param $relations
     *
     * @return $this
     */
    public function with($relations)
    {
        try {
            if (is_string($relations)) {
                $relations = func_get_args();
            }
            $this->with = $relations;
            return $this;
        } catch (\Exception $exception) {
            $exception->info = [
                'file' => __FILE__,
                'class' => __CLASS__,
                'function' => __FUNCTION__,
                'method' => __METHOD__
            ];
            $exceptionString = createExceptionsString($this->model(), 'get_error');
            $errorData = [
                'log_name' => $this->modelBaseName,
                'subject' => $this->model,
                'properties' => ['relations' => $relations],
                'exception' => $exception
            ];
            logError($errorData);
            return $this->errorInternalError($exceptionString);
        }
    }

    /**
     * Create a new instance of the model's query builder.
     *
     * @return $this
     */
    protected function newQuery()
    {
        try {
            $this->query = $this->model->newQuery();
            return $this;
        } catch (\Exception $exception) {
            $exception->info = [
                'file' => __FILE__,
                'class' => __CLASS__,
                'function' => __FUNCTION__,
                'method' => __METHOD__
            ];
            $exceptionString = createExceptionsString($this->model(), 'query_error');
            $errorData = [
                'log_name' => $this->modelBaseName,
                'subject' => $this->model,
                'exception' => $exception
            ];
            logError($errorData);
            return $this->errorInternalError($exceptionString);
        }
    }

    /**
     * Add relationships to the query builder to eager load.
     *
     * @return $this
     */
    protected function eagerLoad()
    {
        try {
            foreach ($this->with as $relation) {
                $this->query->withCount($relation);
            }
            return $this;
        } catch (\Exception $exception) {
            $exception->info = [
                'file' => __FILE__,
                'class' => __CLASS__,
                'function' => __FUNCTION__,
                'method' => __METHOD__
            ];
            $exceptionString = createExceptionsString($this->model(), 'eager_load_error');
            $errorData = [
                'log_name' => $this->modelBaseName,
                'subject' => $this->model,
                'exception' => $exception
            ];
            logError($errorData);
            return $this->errorInternalError($exceptionString);
        }
    }

    /**
     * Set clauses on the query builder.
     *
     * @return $this
     */
    protected function setClauses()
    {
        try {
            foreach ($this->wheres as $where) {
                $this->query->where($where['column'], $where['operator'], $where['value']);
            }

            foreach ($this->orWheres as $where) {
                $this->query->orWhere($where['column'], $where['operator'], $where['value']);
            }

            foreach ($this->whereIns as $whereIn) {
                $this->query->whereIn($whereIn['column'], $whereIn['values']);
            }

            foreach ($this->orderBys as $orders) {
                $this->query->orderBy($orders['column'], $orders['direction']);
            }

            if (isset($this->take) and !is_null($this->take)) {
                $this->query->take($this->take);
            }

            return $this;
        } catch (\Exception $exception) {
            $exception->info = [
                'file' => __FILE__,
                'class' => __CLASS__,
                'function' => __FUNCTION__,
                'method' => __METHOD__
            ];
            $exceptionString = createExceptionsString($this->model(), 'set_clauses_error');
            $errorData = [
                'log_name' => $this->modelBaseName,
                'subject' => $this->model,
                'exception' => $exception
            ];
            logError($errorData);
            return $this->errorInternalError($exceptionString);
        }
    }

    /**
     * Set query scopes.
     *
     * @return $this
     */
    protected function setScopes()
    {
        try {
            foreach ($this->scopes as $method => $args) {
                $this->query->$method(...$args);
            }
            return $this;
        } catch (\Exception $exception) {
            $exception->info = [
                'file' => __FILE__,
                'class' => __CLASS__,
                'function' => __FUNCTION__,
                'method' => __METHOD__
            ];
            $exceptionString = createExceptionsString($this->model(), 'set_scopes_error');
            $errorData = [
                'log_name' => $this->modelBaseName,
                'subject' => $this->model,
                'exception' => $exception
            ];
            logError($errorData);
            return $this->errorInternalError($exceptionString);
        }
    }


    /**
     * @param array $conditions
     * @param array $requestParameters
     * @return Repository
     */
    public function setFilters($conditions = [], $requestParameters = [])
    {
        if (isset($conditions[0])) {
            if (!is_array($conditions[0])) {
                $conditions = [$conditions];
            }
        }

        if (!empty($requestParameters)) {
            if (isset($requestParameters['order_by'])) {
                $this->setOrder($requestParameters['order_by']);
                unset($requestParameters['order_by']);
            }
            $requestParameters = $this->createFiltersFromRequest($requestParameters);
        }

        return $this->whereArray(array_merge($conditions, $requestParameters));
    }

    public function setOrder($orders)
    {
        if (is_array($orders)) {
            foreach ($orders as $orderData) {
                $this->setOrder($orderData);
            }
        } else {
            $orderData = explode(',', $orders);
            $this->orderBy($orderData[0], isset($orderData[1]) ? $orderData[1] : 1);
        }

        return $this;
    }

    /**
     * Reset the query clause parameter arrays.
     *
     * @return $this
     */
    protected function unsetClauses()
    {
        $this->wheres = [];
        $this->whereIns = [];
        $this->scopes = [];
        $this->take = null;
        return $this;
    }


    /**
     * Add the given query scope.
     *
     * @param string $scope
     * @param array $args
     *
     * @return $this
     */
    public function __call($scope, $args)
    {
        $this->scopes[$scope] = $args;
        return $this;
    }
}
