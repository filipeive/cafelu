<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use HasFactory;

use App\Traits\Auditable;

class Client extends Model
{
    use Auditable;

    protected $table = 'clients';
    public $timestamps = false; // Disable timestamps if not needed


    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];
    //relations, accessors, mutators, etc. can be added here as needed

}