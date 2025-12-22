<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport implements WithMultipleSheets
{
    protected $sales;
    protected $expenses;
    protected $products;
    protected $dateFrom;
    protected $dateTo;
    protected $reportType;
    protected $metricas;

    public function __construct($sales, $expenses, $products, $dateFrom, $dateTo, $reportType, $metricas)
    {
        $this->sales = $sales;
        $this->expenses = $expenses;
        $this->products = $products;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->reportType = $reportType;
        $this->metricas = $metricas;
    }

    public function sheets(): array
    {
        $sheets = [];

        if ($this->reportType === 'all' || $this->reportType === 'sales') {
            $sheets[] = new SalesSheet($this->sales);
        }

        if ($this->reportType === 'all' || $this->reportType === 'expenses') {
            $sheets[] = new ExpensesSheet($this->expenses);
        }

        if ($this->reportType === 'all' || $this->reportType === 'products') {
            $sheets[] = new ProductsSheet($this->products);
        }

        return $sheets;
    }
}

class SalesSheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    protected $sales;

    public function __construct($sales)
    {
        $this->sales = $sales;
    }

    public function collection()
    {
        return $this->sales->map(function ($sale) {
            return [
                'ID' => $sale->id,
                'Data' => $sale->sale_date,
                'Cliente' => $sale->customer_name,
                'Total' => $sale->total_amount,
                'Método' => $sale->payment_method,
                'Atendente' => $sale->user->name ?? 'N/A',
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Data', 'Cliente', 'Total (MZN)', 'Método', 'Atendente'];
    }

    public function title(): string
    {
        return 'Vendas';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

class ExpensesSheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    protected $expenses;

    public function __construct($expenses)
    {
        $this->expenses = $expenses;
    }

    public function collection()
    {
        return $this->expenses->map(function ($expense) {
            return [
                'ID' => $expense->id,
                'Data' => $expense->expense_date,
                'Descrição' => $expense->description,
                'Categoria' => $expense->category->name ?? 'N/A',
                'Valor' => $expense->amount,
                'Usuário' => $expense->user->name ?? 'N/A',
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Data', 'Descrição', 'Categoria', 'Valor (MZN)', 'Usuário'];
    }

    public function title(): string
    {
        return 'Despesas';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

class ProductsSheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function collection()
    {
        return $this->products->map(function ($product) {
            return [
                'Nome' => $product->name,
                'Categoria' => $product->category->name ?? 'N/A',
                'Preço Compra' => $product->purchase_price,
                'Preço Venda' => $product->selling_price,
                'Qtd Vendida' => $product->quantity_sold,
                'Receita' => $product->revenue_generated,
                'Estoque Atual' => $product->stock_quantity,
            ];
        });
    }

    public function headings(): array
    {
        return ['Nome', 'Categoria', 'Preço Compra', 'Preço Venda', 'Qtd Vendida', 'Receita', 'Estoque Atual'];
    }

    public function title(): string
    {
        return 'Produtos';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
