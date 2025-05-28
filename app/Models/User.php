<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUlids;

    protected $primaryKey = 'user_id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'email',
        'password',
        'membership_date',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'membership_date' => 'date',
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class, 'user_id', 'user_id');
    }
}
