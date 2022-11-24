<?php

namespace App\Services;

use App\Models\ExplainQuery;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\Query\Builder;

interface ProfilerContract
{

    public function profile(string $category, callable $callback): self;

    public function next(string $category): self;

    public function registerTransaction(array $schema): self;

    public function generateRecordName(): string;

    public function getCategoryName(): string;

    public function getNextCategoryItemId(string $category): int;

    public function getCategories(): array;

    public function profileQuery(callable $callback): mixed;

    public function saveQuery(QueryExecuted $queryExecuted): void;

    public function getAllRelatedQueriesFor(ExplainQuery $query): array;

    public function setNextQueryName(string $name);

}
