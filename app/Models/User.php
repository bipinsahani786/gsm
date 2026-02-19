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
        'uid',
        'mobile',
        'rid',
        'position_id',
        'level_id',
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
            'password' => 'hashed',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::created(function ($user) {

            $user->wallet()->create([
                'uid' => $user->uid,
                'wallet_id' => 'WLT-' . strtoupper(Str::random(8)), // Unique Wallet ID
                'income_wallet' => 0.00,
                'personal_wallet' => 0.00,
                'total_wallet' => 0.00
            ]);
        });
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }
}
