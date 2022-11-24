<?php

namespace App\Listeners;

use App\Services\MySqlProfiler;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Database\MySqlConnection;
use Illuminate\Queue\InteractsWithQueue;

class OnTransactionBegin
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(TransactionBeginning $beginning)
    {
        $dbProfiler = match (get_class($beginning->connection)) {
            MySqlConnection::class => new MySqlProfiler(),
            default => throw new Exception("Your database is not supported yet!"),
        };

        $dbProfiler->begin();
    }
}
