<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($user) {
            $user->name = self::makeNameUrlCompatible($user->name);
            
            if ($user->isDirty('name')) {
                $user->name = self::makeUniqueName($user->name, $user->id);
            }
        });
    }


    private static function makeNameUrlCompatible($name)
    {
        // заменяем пробелы на подчеркивания, удаляем спецсимволы
        $name = preg_replace('/[^\p{L}\p{N}\s]/u', '', $name); // удаляем спецсимволы
        $name = str_replace(' ', '_', trim($name)); // пробелы в подчеркивания
        return $name;
    }

    private static function makeUniqueName($name, $excludeId = null)
    {
        $originalName = $name;
        $counter = 1;

        $query = self::where('name', $name);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $name = $originalName . '_' . $counter;
            $counter++;
            
            $query = self::where('name', $name);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $name;
    }

    public function museums()
    {
        return $this->hasMany(Museum::class);
    }

    public function exhibits()
    {
        return $this->hasMany(Exhibit::class);
    }

    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
            ->withTimestamps();
    }

    public function addFriend(User $user)
    {
        if ($this->id === $user->id) {
            return false;
        }
        
        if ($this->isFriendWith($user)) {
            return false;
        }
        
        \DB::transaction(function () use ($user) {
            $this->friends()->attach($user->id);
            
            $user->friends()->attach($this->id);
        });
        
        return true;
    }

    public function removeFriend(User $user)
    {
        if (!$this->isFriendWith($user)) {
            return false;
        }
        
        \DB::transaction(function () use ($user) {
            $this->friends()->detach($user->id);
            
            $user->friends()->detach($this->id);
        });
        
        return true;
    }

    public function isFriendWith(User $user)
    {
        return $this->friends()->where('friend_id', $user->id)->exists();
    }

    public function mutualFriends(User $user)
    {
        return $this->friends()
            ->whereHas('friends', function ($query) use ($user) {
                $query->where('friend_id', $user->id);
            })
            ->get();
    }

    public function isFriendOfCurrentUser()
    {
        if (!auth()->check()) {
            return false;
        }
        
        return auth()->user()->isFriendWith($this);
    }
}
