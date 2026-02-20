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
        'last_salary_paid_at',
        'position_achieved_at',
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

  
    public function sponsor()
    {
        return $this->belongsTo(User::class, 'rid', 'uid');
    }

    
    public function downlines()
    {
        return $this->hasMany(User::class, 'rid', 'uid');
    }


    public function checkAndUpdatePosition()
    {
        // 1. Direct Active Members Count (Level 1)
        $directActiveCount = \App\Models\User::where('rid', $this->uid)->whereNotNull('level_id')->count();

        // 2. Total Active Team Count (L1 + L2 + L3)
        $l1Uids = \App\Models\User::where('rid', $this->uid)->pluck('uid');
        $l2Uids = \App\Models\User::whereIn('rid', $l1Uids)->pluck('uid');
        
        $totalActiveTeam = \App\Models\User::whereNotNull('level_id')
            ->where(function($query) use ($l1Uids, $l2Uids) {
                $query->where('rid', $this->uid)
                      ->orWhereIn('rid', $l1Uids)
                      ->orWhereIn('rid', $l2Uids);
            })
            ->count();

        // 3. Find the highest eligible position
        $eligiblePosition = \App\Models\Position::where('status', 1)
            ->where(function ($query) {
                $query->whereNull('required_level_id')
                      ->orWhere('required_level_id', $this->level_id);
            })
            ->where('required_directs', '<=', $directActiveCount)
            ->where('required_members', '<=', $totalActiveTeam)
            ->orderBy('salary', 'desc')
            ->first();

        // 4. Update and record the date if eligible
        if ($eligiblePosition && $this->position_id != $eligiblePosition->id) {
            
            $this->position_id = $eligiblePosition->id;
            
            // NAYA: Rank milne ki taarikh save karo aur purani salary date reset karo
            $this->position_achieved_at = now(); 
            $this->last_salary_paid_at = null;   
            
            $this->save();

            // OPTIONAL: Agar rank milte hi pehle din koi chota-mota One-Time Bonus dena ho toh aap yahan code daal sakte hain.
            // Lekin Monthly Salary Cron Job (Step 3) se jayegi.
        }
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }   



    // 1 Recursive Downline
    public function nestedDownlines()
    {
        return $this->downlines()->with(['nestedDownlines', 'level', 'position']);
    }
}
