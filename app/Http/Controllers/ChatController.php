<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Group;
use App\Models\Group_chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Chat::all();
    }

    public function chatGet(Request $request)
    {
        $id = Auth::user()->id;
        $rec = $request->id;
        $chat =  Chat::where(function ($query) use ($id) {
            $query->where('sender_id', '=', $id)
                ->orWhere('receiver_id', '=', $id);
        })->where(function ($query) use ($rec) {
            $query->where('sender_id', '=', $rec)
                ->orWhere('receiver_id', '=', $rec);
        })->orderBy('id', 'DESC')->get();
        $data =  Chat::where(function ($query) use ($id, $rec) {
            $query->where('sender_id', '=', $rec)
                ->where('receiver_id', '=', $id)->where('read', '=', 0);
        })->update(['read' => 1]);
        if ($data > 0) {
            broadcast(new  \App\Events\ReadReport('true'));
        }
        return $chat;
    }

    public function chatReadCount()
    {
        $id = Auth::user()->id;
        $user = User::select('id')->get();

        foreach ($user as $key => $value) {
            $data[$value->id] = Chat::where(['sender_id' => $value->id, 'receiver_id' => $id, 'read' => 0])->count();
        }
        return $data;
    }

    public function userOnlineStatus(Request $request)
    {
        $id = Auth::user()->id;
        $status = $request->status;

        if ($status == 1) {
            $data['online_status'] = 'online';
        } else {
            $data['online_status'] = date('Y-m-d H:i:s');
        }
        broadcast(new \App\Events\OnlineReport('true'));
        $user = User::find($id);
        $user->update($data);
        return response()->json(200);
    }

    public function ChatGroupAdd(Request $request)
    {
        $request->validate(['selectUser' => 'required|array', 'groupName' => 'required'], ['selectUser.required' => 'Select user']);

        $data['group_name'] = $request->groupName;
        $data['group_members'] = implode(',', $request->selectUser);
        $data['group_admin'] = Auth::user()->id;
        Group::create($data);
        return response()->json(['success' => 'Data inserted']);
    }

    public function ChatGroupGet()
    {
        $id = Auth::user()->id;
        $group = array();
        $group = Group::WhereRaw("FIND_IN_SET($id, group_members)")->orWhere('group_admin', $id)->get();
        $AuthId = array($id);
        foreach ($group as $key => $value) {
            $group_data[] = $value;
            $ids = explode(',', $value['group_members']);
            $diff = array_diff($ids, $AuthId);
            $id != $value['group_admin'] ?  array_push($diff, $value['group_admin'])  : '';
            $receiver_id = $id != $value['group_admin'] ? $diff : $ids;
            $group_data[$key]['user'] = User::select(['name', 'id'])->whereIn('id', $receiver_id)->get();
        }

        return $group_data;
    }
    public function GroupChatGet(Request $request)
    {
        $id = Auth::user()->id;
        $group_id = $request->group_id;
        $chat = Group_chat::with('user')->where('group_id', $group_id)->where(function ($query) use ($id) {
            $query->WhereRaw("NOT FIND_IN_SET($id, conversation_deleted)")->orWhere('conversation_deleted', NULL);
        })->orderBy('id', 'DESC')->get();
        $data =  Group_chat::where(function ($query) use ($id, $group_id) {
            $query->where('group_id', '=', $group_id)
                ->WhereRaw("FIND_IN_SET($id, receiver_id)")->where(function ($query) use ($id) {
                    $query->where('read', 0)->orWhereRaw("NOT FIND_IN_SET($id, `read`)");
                });
        })->get();
        $update = array();
        foreach ($data as $key => $value) {
            $implode =  $value->read == 0 ? $id : $value->read . ',' . $id;
            $update[] = Group_chat::where('id', $value->id)->update(['read' => $implode]);
        }
        if (count($update) > 0) {
            broadcast(new \App\Events\GroupChatRead('true'));
        }
        return $chat;
    }

    public function group_chat_send(Request $request)
    {
        $group_id = $request->group_id;
        $sender_id =  Auth::user()->id;
        $group = Group::select(['group_members', 'group_admin'])->find($group_id);
        $group->group_members =  $sender_id != $group->group_admin ? $group->group_members . ',' . $group->group_admin : $group->group_members;
        $receiver_id = implode(',', array_diff(explode(',', $group->group_members), array($sender_id)));
        $message = $request->message;
        return broadcast(new  \App\Events\GroupChat($group_id, $sender_id, $receiver_id, $message)) ? 1 : 0;
    }

    public function GroupchatReadCount()
    {
        $id = Auth::user()->id;
        $user_data = User::select('id')->get();
        $group = Group::select('id')->get();

        foreach ($group as $key => $value) {
            $data[$value->id] = Group_chat::where(['group_id' => $value->id])->WhereRaw("FIND_IN_SET($id, receiver_id)")->where(function ($query) use ($id) {
                $query->where('read', 0)->orWhereRaw("NOT FIND_IN_SET($id, `read`)");
            })->count();
        }
        return $data;
    }

    public function GroupChatDelete(Request $request)
    {
        $id = $request->id;
        $chat = Chat::find($id);
        $data['deleted'] = $request->deleted;
        $data['deleted_at'] = date('Y-m-d H:i:s');
        $chat->update($data);
        broadcast(new \App\Events\GroupChatRead('true'));
        return response()->json(['msg' => 'Data deleted']);
    }

    public function GroupMessageDelete(Request $request)
    {
        $id = $request->id;
        $group_chats = Group_chat::find($id);
        $data['deleted'] = $request->deleted;
        $data['deleted_at'] = date('Y-m-d H:i:s');
        $group_chats->update($data);
        broadcast(new \App\Events\GroupChatRead('true'));
        return response()->json(['msg' => 'Data deleted']);
    }

    public function GroupConversationDelete(Request $request)
    {
        $id = Auth::user()->id;
        $group_id = $request->group_id;
        $group_chat = Group_chat::select(['id', 'conversation_deleted'])->where('group_id', $group_id)->get();
        $AuthId = array($id);
        if ($request->is_group == 1) {
            Group::find($group_id)->delete();
        } else {
            foreach ($group_chat as $key => $value) {
                $diff = array_diff($AuthId, explode(',', $value['conversation_deleted']));
                $value['conversation_deleted'] != '' ?  array_push($diff, $value['conversation_deleted'])  : '';
                $data['conversation_deleted'] = $value['conversation_deleted'] != '' ? implode(',', $diff) : $id;
                Group_chat::where('id', $value['id'])->update($data);
            }
        }
        return response()->json(['msg' => 'Data deleted']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sender_id = Auth::user()->id;
        $receiver_id = $request->receiver_id;
        $message = $request->message;
        return broadcast(new  \App\Events\Message($sender_id, $receiver_id, $message)) ? 1 : 0;
        // return ['status' => 'OK'];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function show(Chat $chat)
    {
        return $chat;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function edit(Chat $chat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chat $chat)
    {
        //
    }
}
