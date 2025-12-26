<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Museum;
use App\Models\Exhibit;
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
        
        $feed = $this->getFriendFeed($user);
        
        return view('friends.index', compact('user', 'friends', 'feed'));
    }

    private function getFriendFeed(User $user)
    {
        $friendIds = $user->friends()->pluck('friend_id');
        
        if ($friendIds->isEmpty()) {
            return collect();
        }
        
        $museums = Museum::whereIn('user_id', $friendIds)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($museum) {
                return [
                    'type' => 'museum',
                    'item' => $museum,
                    'created_at' => $museum->created_at,
                    'user' => $museum->user
                ];
            });
        
        $exhibits = Exhibit::whereIn('user_id', $friendIds)
            ->with(['user', 'museum'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($exhibit) {
                return [
                    'type' => 'exhibit',
                    'item' => $exhibit,
                    'created_at' => $exhibit->created_at,
                    'user' => $exhibit->user
                ];
            });
        
        $feed = $museums->merge($exhibits)
            ->sortByDesc('created_at')
            ->take(10);
        
        return $feed;
    }
}