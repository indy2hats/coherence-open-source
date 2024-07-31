<?php

namespace App\Http\Controllers;

use App\Models\Santa;
use App\Models\SantaMessage;
use App\Models\User;
use App\Notifications\SantaWishNotification;
use Illuminate\Http\Request;
use Notification;

class SantaMemberController extends Controller
{
    public function index()
    {
        $santas = Santa::get();
        $users = User::notClients()->active()->get();
        $count = Santa::whereNull('giftee_id')->count();

        return view('settings.santas.index', compact('santas', 'users', 'count'));
    }

    public function store(Request $request)
    {
        $path = '';

        $request->validate([
            'user_id' => 'required|unique:santas,user_id'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('santas');
        }

        $data = [
            'user_id' => request('user_id'),
            'phone' => request('phone'),
            'address' => request('address'),
            'image' => $path,
        ];

        Santa::create($data);

        $santas = Santa::get();

        $content = view('settings.santas.list', compact('santas'))->render();

        $res = [
            'status' => 'success',
            'data' => $content,
            'message' => 'Santa Member added successfully!'
        ];

        return response()->json($res);
    }

    public function destroy($id)
    {
        Santa::find($id)->delete();

        $santas = Santa::get();

        $content = view('settings.santas.list', compact('santas'))->render();

        $res = [
            'status' => 'success',
            'data' => $content,
            'message' => 'Santa Member deleted successfully!'
        ];

        return response()->json($res);
    }

    public function edit($id)
    {
        $santa = Santa::find($id);
        $users = User::notClients()->active()->get();

        return view('settings.santas.edit', compact('santa', 'users'));
    }

    public function update(Request $request, $id)
    {
        $data = [
            'user_id' => request('user_id'),
            'phone' => request('phone'),
            'address' => request('address'),
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('santas');

            $data['image'] = $path;
        }

        Santa::find($id)->update($data);

        $santas = Santa::get();

        $content = view('settings.santas.list', compact('santas'))->render();

        $res = [
            'status' => 'success',
            'data' => $content,
            'message' => 'Santa member updated successfully!'
        ];

        return response()->json($res);
    }

    public function setSanta()
    {
        $santas = [];
        $pairs = [];

        $users = Santa::all();

        foreach ($users as $user) {
            $santas[$user->id] = $user->user->full_name;
        }

        $givers = $santas;
        $receivers = array_values($santas);

        foreach ($givers as $id => $giver) {
            $hasReceived = false;
            while (! $hasReceived) {
                $receiver = rand(0, count($receivers) - 1);
                if ($receivers[$receiver] != $giver) {
                    $pairs[] = [
                        'id' => $id,
                        'santa' => $giver,
                        'giftee_id' => array_search($receivers[$receiver], $santas),
                        'giftee' => $receivers[$receiver]
                    ];

                    unset($receivers[$receiver]);
                    $receivers = array_values($receivers);

                    $hasReceived = true;
                } else {
                    if (count($receivers) == 1) {
                        $pairs[] = [
                            'id' => $id,
                            'santa' => $giver,
                            'giftee_id' => $pairs[0]['giftee_id'],
                            'giftee' => $pairs[0]['giftee']
                        ];
                        $pairs[0]['giftee'] = $giver;
                        $pairs[0]['giftee_id'] = $id;

                        $hasReceived = true;
                    }
                }
            }
        }

        foreach ($pairs as $pair) {
            Santa::find($pair['id'])->update([
                'giftee_id' => $pair['giftee_id']
            ]);
        }

        return view('settings.santas.pairs', compact('pairs'));
    }

    public function viewSanta()
    {
        $pairs = [];

        $users = Santa::all();

        foreach ($users as $key => $user) {
            $pairs[$key]['santa'] = $user->user->full_name;
            $pairs[$key]['giftee'] = $user->giftee->user->full_name;
        }

        return view('settings.santas.pairs', compact('pairs'));
    }

    public function resetSanta()
    {
        Santa::where('id', '>', 0)->update(
            [
                'giftee_id' => null,
                'has_confirmed' => 0
            ]
        );

        return redirect()->route('santa-members.index');
    }

    public function findSanta()
    {
        $santa = Santa::whereUserId(auth()->user()->id)->first();

        if (! $santa) {
            abort(500, 'You dont have a santa!!');
        }

        if ($santa->has_confirmed == 0) {
            return view('settings.santas.scratch', compact('santa'));
        } else {
            $messages = SantaMessage::whereUserId($santa->id)->get();

            return view('settings.santas.mysanta', compact('santa', 'messages'));
        }
    }

    public function confirmSanta()
    {
        Santa::whereUserId(auth()->user()->id)->update(
            [
                'has_confirmed' => 1
            ]
        );

        return response()->json('success');
    }

    public function sendMessage(Request $request)
    {
        $santa = Santa::whereUserId(auth()->user()->id)->first();

        SantaMessage::create([
            'user_id' => $santa->giftee_id,
            'content' => $request->message
        ]);

        return response()->json('Message Sent Successfully!');
    }

    public function sendWish(Request $request)
    {
        $userId = auth()->user()->id;
        $user = Santa::whereUserId($userId)->first();

        $santa = Santa::whereGifteeId($user->id)->first();

        $santa->wish = $request->message;

        $santa->save();

        Notification::send($santa->user, new SantaWishNotification($request->message));

        Santa::whereUserId($userId)->update(
            [
                'send_wish' => 1
            ]
        );

        return response()->json('Your Wish Sent Successfully!');
    }
}
