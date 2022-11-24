<?php

namespace App\Services;

interface DbProfilerContract
{

    public function begin(): void;

    public function end(): void;

}
