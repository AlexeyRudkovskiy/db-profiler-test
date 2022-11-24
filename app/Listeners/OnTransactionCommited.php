<?php

namespace App\Listeners;

use App\Services\MySqlProfiler;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Events\TransactionCommitted;
use Illuminate\Database\MySqlConnection;
use Illuminate\Queue\InteractsWithQueue;

class OnTransactionCommited
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
    public function handle(TransactionCommitted $event)
    {
        $dbProfiler = match (get_class($event->connection)) {
            MySqlConnection::class => new MySqlProfiler(),
            default => throw new Exception("Your database is not supported yet!"),
        };

        $dbProfiler->end();
    }
}
