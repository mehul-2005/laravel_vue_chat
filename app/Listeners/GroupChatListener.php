<?php

namespace App\Listeners;

use App\Events\GroupChat;
use App\Models\Group_chat;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GroupChatListener
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
     * @param  \App\Events\GroupChat  $event
     * @return void
     */
    public function handle(GroupChat $event)
    {
        $data['group_id'] = $event->group_id;
        $data['sender_id'] = $event->sender_id;
        $data['receiver_id'] = $event->receiver_id;
        $data['message'] = $event->message;
        if (!empty($data['sender_id']) && !empty($data['receiver_id'])) {
            Group_chat::create($data);
        }
    }
}
