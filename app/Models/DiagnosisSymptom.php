<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagnosisSymptom extends Model
{
    use HasFactory;

    protected $table = 'diagnosis_symptom';
    protected $primaryKey = 'symptom_id';
    public $incrementing = true;
    protected $keyType = 'int';
    
    // Nonaktifkan timestamps Laravel default
    public $timestamps = false;

    protected $fillable = [
        'diagnosis_id',
        'symptom_name'
    ];

    // RELATIONSHIPS
    public function diagnosis()
    {
        return $this->belongsTo(Diagnosis::class, 'diagnosis_id', 'diagnosis_id');
    }
}