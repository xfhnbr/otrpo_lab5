<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function addFriend(Request $request, User $user)
    {
        $currentUser = auth()->user();
        
        if ($currentUser->id === $user->id) {
            return back()->with('error', 'Нельзя добавить себя в друзья');
        }
        
        $result = $currentUser->addFriend($user);
        
        if ($result) {
            return back()->with('success', 'Пользователь добавлен в друзья');
        }
        
        return back()->with('info', 'Пользователь уже в друзьях');
    }

    public function removeFriend(Request $request, User $user)
    {
        $currentUser = auth()->user();
        
        $result = $currentUser->removeFriend($user);
        
        if ($result) {
            return back()->with('success', 'Пользователь удален из друзей');
        }
        
        return back()->with('info', 'Пользователь не был в друзьях');
    }

    public function index(User $user = null)
    {
        $user = $user ?? auth()->user();
        $friends = $user->friends()->paginate(10);
        
        return view('friends.index', compact('user', 'friends'));
    }
}