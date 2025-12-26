<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exhibit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'image_filename',
        'user_id',
        'museum_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Deleted_user',
            'email' => 'unknown@example.com',
        ]);
    }

    public function museum()
    {
        return $this->belongsTo(Museum::class);
    }

    public function getImageUrlAttribute()
    {
        return asset('storage/exhibits/' . $this->image_filename);
    }
    
}