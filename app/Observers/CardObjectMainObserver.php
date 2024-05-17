<?php

namespace App\Observers;

use App\Models\CardObjectMain;
//use App\Models\CardObjectMain;
use App\Models\CardGraph;

class CardObjectMainObserver
{
    /**
     * Handle the CardObjectMain "created" event.
     */
    public function created(CardObjectMain $cardObjectMain): void
    {
        //
    }

    /**
     * Handle the CardObjectMain "updated" event.
     */
    public function updated(CardObjectMain $cardObjectMain): void
    {
        CardGraph::where('cards_ids', $cardObjectMain->_id)
            ->update(['infrastructure_type' => $cardObjectMain->infrastructure]);
        CardGraph::where('cards_ids', $cardObjectMain->_id)
            ->update(['curator' => $cardObjectMain->curator]);
    }

    /**
     * Handle the CardObjectMain "deleted" event.
     */
    public function deleted(CardObjectMain $cardObjectMain): void
    {
        //
    }

    /**
     * Handle the CardObjectMain "restored" event.
     */
    public function restored(CardObjectMain $cardObjectMain): void
    {
        //
    }

    /**
     * Handle the CardObjectMain "force deleted" event.
     */
    public function forceDeleted(CardObjectMain $cardObjectMain): void
    {
        //
    }
}
