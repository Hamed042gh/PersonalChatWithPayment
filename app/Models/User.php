<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use App\Models\invoice;
use App\Models\Message;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_activity',
        'messages_count'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
        ];
    }

    public function isOnline()
    {
        return $this->last_activity && Carbon::parse($this->last_activity)->diffInMinutes(now()) < 5;
    }

    public function messages_count()
    {
        return $this->sentMessages()->count();
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }



    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
    public function invoices()
    {
        return $this->hasMany(invoice::class);
    }
}
