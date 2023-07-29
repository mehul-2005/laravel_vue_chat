<?php

namespace App\Listeners;

use App\Events\Message;
use App\Models\Chat;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ChatListener
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
     * @param  \App\Events\Chat  $event
     * @return void
     */
    public function handle(Message $event)
    {
        $data['sender_id'] = $event->sender_id;
        $data['receiver_id'] = $event->receiver_id;
        $data['message'] = $event->message;
        if (!empty($data['sender_id']) && !empty($data['receiver_id'])) {
            Chat::create($data);
        }
    }
}
