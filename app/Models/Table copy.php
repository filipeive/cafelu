<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{   
    public $timestamps = false; // Disable timestamps if not needed
    protected $table = 'tables'; // Specify the table name if different from the model name
    protected $fillable = [
        'number',
        'capacity',
        'status',
        'is_main',
        'group_id',
        'merged_capacity',
        'position_x',
        'position_y'
    ];
    // A table can have many sales
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
    // orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    public function activeOrders()
    {
        return $this->orders()
            ->whereIn('status', ['pending', 'preparing', 'ready', 'delivered']);
    }

    // Verificar se a mesa pertence a um grupo
    public function isInGroup()
    {
        return !is_null($this->group_id);
    }

    // Obter todas as mesas do grupo
    public function getGroupTables()
    {
        if (!$this->isInGroup()) {
            return collect([$this]);
        }

        return self::where('group_id', $this->group_id)->get();
    }

    // Verificar se a mesa está livre
    public function isFree()
    {
        return $this->status === 'free';
    }

    // Verificar se a mesa está ocupada
    public function isOccupied()
    {
        return $this->status === 'occupied';
    }

    // Verificar se a mesa pode ser liberada (sem pedidos pendentes)
    public function canBeFree()
    {
        return $this->activeOrders()->count() === 0;
    }
}