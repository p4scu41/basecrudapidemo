<?php

namespace App\Listeners;

use App\Events\BaseEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Listeners Base Class
 *
 * @category Listeners
 * @package  App\Listeners
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-04-04
 */
class BaseListener
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
     * @param BaseEvent $event Event instance
     *
     * @return void
     */
    public function handle(BaseEvent $event)
    {
        //
    }
}
