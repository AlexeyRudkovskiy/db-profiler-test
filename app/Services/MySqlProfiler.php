<?php

namespace App\Services;

class MySqlProfiler implements DbProfilerContract
{

    public function begin(): void
    {
        \DB::statement("SET profiling = 1");
    }

    public function end(): void
    {
        \DB::statement("SET profiling = 0");
        $profiles = \DB::select('SHOW PROFILES');

        $profilerData = collect($profiles)
            ->map(function ($query) {
                /** @var  ProfileBuilder $builder */
                $builder = app(ProfileBuilder::class);
                $statuses = \DB::select("SHOW PROFILE ALL FOR QUERY " . $query->Query_ID);

                $builder = $builder
                    ->setDuration($query->Duration)
                    ->setQuery($query->Query)
                    ->setQueryId($query->Query_ID);
                collect($statuses)->each(fn ($status) => $builder->addStatus($status->Status, $status->Duration));

                return $builder->build();
            });

        app(ProfilerContract::class)->registerTransaction($profilerData->toArray());
    }

}
