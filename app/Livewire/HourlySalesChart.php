<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale;
use Carbon\Carbon;

class HourlySalesChart extends Component
{
    public $chartData = [];

    public function mount()
    {
        $this->chartData = $this->getHourlySalesData();
    }

    public function getHourlySalesData()
    {
        $data = [];
        $today = Carbon::today();
        
        // Gerar dados para as 24 horas do dia
        for ($hour = 0; $hour < 24; $hour++) {
            $startTime = $today->copy()->addHours($hour);
            $endTime = $today->copy()->addHours($hour + 1);
            
            $totalAmount = Sale::whereBetween('created_at', [$startTime, $endTime])
                ->sum('total_amount');
            
            $data[] = [
                'hour' => $hour . ':00',
                'value' => (float) $totalAmount
            ];
        }
        
        return $data;
    }
    
    public function render()
    {
        return view('livewire.hourly-sales-chart', [
            'chartData' => $this->chartData
        ]);
    }
}
