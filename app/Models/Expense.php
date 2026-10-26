<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;
use App\Traits\BelongsToTenant;

class Expense extends Model
{
    use HasFactory, Auditable, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'description',
        'amount',
        'category',
        'expense_date',
        'notes',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
