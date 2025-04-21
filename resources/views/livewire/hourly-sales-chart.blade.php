<div>
    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            <div id="hourlySalesChart"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        document.addEventListener('livewire:load', function () {
            const chart = new ApexCharts(document.querySelector("#hourlySalesChart"), {
                chart: {
                    type: 'line',
                    height: 300
                },
                series: [{
                    name: 'Vendas',
                    data: @json(collect($chartData)->pluck('value'))
                }],
                xaxis: {
                    categories: @json(collect($chartData)->pluck('hour')),
                    title: { text: 'Hora' }
                },
                yaxis: {
                    title: { text: 'Valor (MZN)' }
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val.toFixed(2) + " MZN"
                        }
                    }
                }
            });

            chart.render();
        });
    </script>
</div>
