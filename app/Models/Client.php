<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use HasFactory;

class Client extends Model
{

    protected $table = 'clients';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

}