<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;
    protected $fillable = [
        'sale_date', 'total_amount', 'payment_method', 'status',
        'cash_amount', 'card_amount', 'mpesa_amount', 'emola_amount'
    ];
    
    // A sale has many sale items
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
    // A sale belongs to a user (the one who made the sale)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}