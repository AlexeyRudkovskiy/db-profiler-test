<?php

namespace App\Services;

class ProfileBuilder
{

    protected int $queryId;
    protected string $query;
    protected float $duration;
    protected array $statuses = [];

    public function setQueryId(int $queryId): self
    {
        $this->queryId = $queryId;
        return $this;
    }

    public function setQuery(string $query): self
    {
        $this->query = $query;
        return $this;
    }

    public function setDuration(float $duration): self
    {
        $this->duration = $duration;
        return $this;
    }

    public function addStatus(string $status, float $duration): self
    {
        $this->statuses[] = [ 'status' => $status, 'duration' => $duration ];
        return $this;
    }

    public function build()
    {
        return [
            'query_id' => $this->queryId,
            'query' => $this->query,
            'duration' => $this->duration,
            'statuses' => $this->statuses
        ];
    }

}
