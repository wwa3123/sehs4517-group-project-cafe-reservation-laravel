<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable {
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'phone',
        'email',
        'password_hash',
        'loyalty_points',
        'subscribe_events'
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
    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function registerUser($data) {
        return self::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password_hash' => Hash::make($data['password']),
            'subscribe_events' => request()->boolean('subscribe'),
        ]);
    }
}
