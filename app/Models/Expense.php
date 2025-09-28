<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Expense extends Model
{
    protected $fillable = [
        'user_id', 'expense_category_id', 'description', 'amount',
        'expense_date', 'receipt_number', 'notes', 'receipt_file',
        'receipt_original_name', 'receipt_mime_type', 'receipt_file_size'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
        'receipt_file_size' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Acessor para URL do comprovativo
     */
    public function getReceiptUrlAttribute()
    {
        return $this->receipt_file ? Storage::url($this->receipt_file) : null;
    }

    /**
     * Acessor para ícone do arquivo baseado no MIME type
     */
    public function getReceiptIconAttribute()
    {
        if (!$this->receipt_mime_type) return 'mdi-file';

        $mime = $this->receipt_mime_type;
        
        if (str_contains($mime, 'image')) return 'mdi-file-image';
        if (str_contains($mime, 'pdf')) return 'mdi-file-pdf';
        if (str_contains($mime, 'word')) return 'mdi-file-word';
        if (str_contains($mime, 'excel')) return 'mdi-file-excel';
        if (str_contains($mime, 'zip')) return 'mdi-folder-zip';
        
        return 'mdi-file';
    }

    /**
     * Acessor para formato legível do tamanho do arquivo
     */
    public function getReceiptFileSizeFormattedAttribute()
    {
        if (!$this->receipt_file_size) return null;

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->receipt_file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Verificar se tem comprovativo
     */
    public function hasReceipt(): bool
    {
        return !empty($this->receipt_file) && Storage::exists('public/' . $this->receipt_file);
    }

    /**
     * Boot method para deletar arquivo quando a despesa for deletada
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($expense) {
            if ($expense->receipt_file && Storage::exists('public/' . $expense->receipt_file)) {
                Storage::delete('public/' . $expense->receipt_file);
            }
        });
    }
}