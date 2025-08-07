<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagnosisLabel extends Model
{
    use HasFactory;

    protected $table = 'diagnosis_label';
    protected $primaryKey = 'label_id';
    public $incrementing = true;
    protected $keyType = 'int';
    
    // Nonaktifkan timestamps Laravel default
    public $timestamps = false;

    protected $fillable = [
        'diagnosis_id',
        'label_name',
        'label_cscore'
    ];

    protected $casts = [
        'label_cscore' => 'float',
    ];

    // RELATIONSHIPS
    public function diagnosis()
    {
        return $this->belongsTo(Diagnosis::class, 'diagnosis_id', 'diagnosis_id');
    }
}