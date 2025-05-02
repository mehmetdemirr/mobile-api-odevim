<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{

    protected $table = 'people'; 
    use HasFactory;
    protected $fillable = [
        'name',
        'surname',
        'age',
        'tc',
        'address',
        'phone',
        'email',
        'birth_date',
        'gender',
        'marital_status',
        'profession',
        'city',
        'country',
        'postal_code',
        'notes'
    ];
}
