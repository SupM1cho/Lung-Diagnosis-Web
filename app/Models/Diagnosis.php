<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    use HasFactory;

    protected $table = 'diagnosis';
    protected $primaryKey = 'diagnosis_id';
    public $incrementing = true;
    protected $keyType = 'int';
    
    // Nonaktifkan timestamps Laravel default
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'diagnosis_date'
    ];

    protected $casts = [
        'diagnosis_date' => 'date',
    ];

    // Auto-set diagnosis_date saat create
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->diagnosis_date)) {
                $model->diagnosis_date = now()->format('Y-m-d');
            }
        });
    }

    // RELATIONSHIPS
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function labels()
    {
        return $this->hasMany(DiagnosisLabel::class, 'diagnosis_id', 'diagnosis_id');
    }

    public function symptoms()
    {
        return $this->hasMany(DiagnosisSymptom::class, 'diagnosis_id', 'diagnosis_id');
    }
}