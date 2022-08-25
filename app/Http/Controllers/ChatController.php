<?php

namespace App\Http\Controllers;

use App\Events\SentMessage;
use App\Models\Chat;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $chats = '';
        $friend = '';
        $me = auth()->user();
        $friends = friend::where('friend_id', auth()->id())->with('user', function($query){
            $query->select('id', 'name');
        })->select('id','user_id')->get();
        return view('chat',compact('chats', 'friend', 'friends', 'me'));
    }

    public function userList(Request $request)
    {
        $users = User::all();
        return view('user', compact('users'));
    }

    public function chat(Request $request, $friend_id){

        if(friend::where(['user_id' => auth()->id(), 'friend_id' => $friend_id])->count() === 0 || friend::where(['user_id' => $friend_id, 'friend_id' => auth()->id()])->count() === 0) {
            $uuid = Str::uuid();
            friend::create([
                'user_id' => auth()->id(),
                'chat_id' => $uuid,
                'friend_id' => $friend_id
            ]);

            friend::create([
                'user_id' => $friend_id,
                'chat_id' => $uuid,
                'friend_id' => auth()->id()
            ]);
        }

        $user_id = auth()->user()->id;
        $chat_id = friend::where(['user_id'=>auth()->id(), 'friend_id' =>$friend_id])->first()->chat_id;
        $chats = Chat::where('chat_id', $chat_id)->get();
        $friend = User::where('id', $friend_id)->first();
        $me =User::where('id', auth()->id())->with('friends',function($query) use ($chat_id){
            $query->where('chat_id', $chat_id);
        })->first();
        $friends =friend::where('friend_id', auth()->id())->with('user', function($query){
            $query->select('id', 'name');
        })->select('id','user_id')->get();

        return view('chat' , compact('chats','friend', 'friends', 'me'));
    }

    public function sentMessage(Request $request, $friend_id){
       $chat = chat::create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'chat_id' => friend::where(['user_id'=>auth()->id(), 'friend_id' =>$friend_id])->first()->chat_id,
            'friend_id' => $friend_id
        ]);

        broadcast(new SentMessage($chat))->toOthers();

        return response()->json(['status' => 'success', 'message' => 'Message sent successfully']);
    }


}
