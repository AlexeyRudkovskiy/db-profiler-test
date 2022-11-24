<?php

namespace App\Services;

use App\Models\DbProfile;
use App\Models\ExplainQuery;
use Carbon\Carbon;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Str;

class BaseProfiler implements ProfilerContract
{

    protected bool $shouldSaveQuery = false;
    protected bool $defineNextCategory = false;
    protected bool $profilingQueries = false;
    protected string $categoryName = '';
    protected string $nextQueryName = '';
    protected ?int $previousQueryId = null;

    protected $defaultCategory = 'Unnamed Transaction';

    public function profile(string $category, callable $callback): self
    {
        $currentCategory = $this->categoryName;

        $this->categoryName = $category;
        $callback();

        $this->categoryName = $currentCategory;
        return $this;
    }

    public function next(string $category): self
    {
        $this->defineNextCategory = true;
        $this->categoryName = $category;
        return $this;
    }

    public function registerTransaction(array $schema): self
    {
        $categoryName = $this->getCategoryName();
        $itemId = $this->getNextCategoryItemId($categoryName);

        DbProfile::create([
            'queries' => $schema,
            'category' => $categoryName,
            'query' => $itemId
        ]);

        $this->categoryName = '';
        return $this;
    }

    public function generateRecordName(): string
    {
        $categoryName = !empty($this->categoryName) ? $this->categoryName : $this->defaultCategory;

        $countExisting = DbProfile::whereCategory($categoryName)->count();
        return $categoryName . ' #' . $countExisting;
    }

    public function getCategoryName(): string
    {
        return !empty($this->categoryName) ? $this->categoryName : $this->defaultCategory;
    }

    public function getNextCategoryItemId(string $category): int
    {
        return DbProfile::whereCategory($category)->count() + 1;
    }

    public function getCategories(): array
    {
        return \App\Models\DbProfile::query()
            ->select(['category'])
            ->addSelect(\DB::raw('count(id) as queries'))
            ->addSelect(\DB::raw('max(created_at) as latest_at'))
            ->groupBy('category')
            ->orderBy('latest_at', 'desc')
            ->get()
            ->toArray();
    }

    public function profileQuery(callable $callback): mixed
    {
        $this->profilingQueries = true;
        $result = $callback($this);
        $this->profilingQueries = false;
        $this->previousQueryId = null;

        return $result;
    }

    public function saveQuery(QueryExecuted $queryExecuted): void
    {
        if (!$this->profilingQueries) {
            return ;
        }

        $this->profilingQueries = false;

        $sqlQuery = $queryExecuted->sql;
        $bindings = $queryExecuted->bindings;

        $sql_with_bindings = \Str::replaceArray('?', $bindings, $sqlQuery);

        $preparedQuery = \DB::select('EXPLAIN ' . $sqlQuery, $bindings);
        $encodedExplain = json_encode($preparedQuery);
        $explainedResult = json_decode($encodedExplain, true);

        $preparedQuery = \DB::select('EXPLAIN FORMAT=JSON ' . $sqlQuery, $bindings);
        $explainedJsonResult = $preparedQuery[0]->EXPLAIN;
        $explainedJsonResult = json_decode($explainedJsonResult);

        $bindings = array_map(function ($item) {
            if ($item instanceof Carbon) {
                return [ 'type' => 'string', 'value' => $item->toDateTimeString() ];
            } else if (is_string($item)) {
                return [ 'type' => 'string', 'value' => $item ];
            } else if (is_numeric($item)) {
                return [ 'type' => 'numeric', 'value' => $item ];
            } else if (is_bool($item)) {
                return [ 'type' => 'boolean', 'value' => (int) $item ];
            } else {
                return [ 'type' => 'other', 'value' => (string) $item ];
            }
        }, $bindings);

        if (!empty($this->nextQueryName)) {
            $queryName = $this->nextQueryName;
            $this->nextQueryName = '';
        } else {
            $queryName = Str::limit($sqlQuery, 128);
        }

        $explainQuery = ExplainQuery::create([
            'name' => $queryName,
            'query' => $sqlQuery,
            'bindings' => $bindings,
            'explain' => [
                'table' => $explainedResult,
                'json' => $explainedJsonResult
            ],
            'previous_query_id' => $this->previousQueryId
        ]);

        $this->profilingQueries = true;
        $this->previousQueryId = $explainQuery->id;
    }

    public function getAllRelatedQueriesFor(ExplainQuery $query): array
    {
        $items = [ $query ];

        $currentItem = $query->previousQuery;

        while ($currentItem !== null) {
            array_unshift($items, $currentItem);
            $currentItem = $currentItem->previousQuery;
        }

        $currentItem = $query->nextQuery;
        while ($currentItem !== null) {
            array_push($items, $currentItem);
            $currentItem = $currentItem->nextQuery;
        }

        return $items;
    }

    public function setNextQueryName(string $name)
    {
        $this->nextQueryName = $name;
    }

}
