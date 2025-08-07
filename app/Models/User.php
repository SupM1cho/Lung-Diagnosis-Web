<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Sesuaikan dengan nama tabel dan primary key
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Nonaktifkan timestamps Laravel default karena hanya ada created_at
    public $timestamps = false;

    protected $fillable = [
        'username', 
        'email', 
        'password',
        'created_at'
    ];

    protected $hidden = [
        'password', 
        'remember_token'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Override method untuk created_at manual
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->created_at)) {
                $model->created_at = now()->format('Y-m-d');
            }
        });
    }

    // RELATIONSHIPS
    public function diagnoses()
    {
        return $this->hasMany(Diagnosis::class, 'user_id', 'user_id');
    }

    // Override method untuk authentication dengan username
    public function getAuthIdentifierName()
    {
        return 'user_id';
    }

    // Method untuk login dengan username atau email
    public function findForPassport($username) {
        return $this->where('username', $username)->orWhere('email', $username)->first();
    }
}