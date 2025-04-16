<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//id 	name 	role 	hire_date
class Employee extends Model
{
    use HasFactory;
    protected $table = 'employees';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'role',
        'hire_date',
    ];

    protected $casts = [
        'hire_date' => 'datetime',
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
                     ->orWhere('role', 'like', "%{$search}%");
    }
}