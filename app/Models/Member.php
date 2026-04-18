<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Member extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'member_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'phone',
        'email',
        'password_hash',
        'role',
        'subscribe_events',
        'loyalty_points',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    /**
     * Get the reservations for the member.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'member_id', 'member_id');
    }

    /**
     * Get the event registrations for the member.
     */
    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class, 'member_id', 'member_id');
    }

    public static function registerMember($data) {
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

    public function getAuthPassword() {
        return $this->password_hash;
    }

    public function getAuthPasswordName() {
        return 'password_hash';
    }
}
