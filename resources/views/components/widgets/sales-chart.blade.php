@props(['labels', 'data'])

<div class="mt-4" wire:ignore x-data="chartComponent(@js($labels), @js($data))" x-init="init()">
    <h3 class="text-lg font-semibold text-gray-500 dark:text-gray-400">Tren Penjualan 7 Hari Terakhir</h3>
    <canvas x-ref="chart"></canvas>
</div>

@once
<script>
    function chartComponent(labels, data) {
        return {
            labels: labels
            , data: data
            , init() {
                new Chart(this.$refs.chart, {
                    type: 'line'
                    , data: {
                        labels: this.labels
                        , datasets: [{
                            label: 'Omzet Penjualan'
                            , data: this.data
                            , borderColor: 'rgba(75, 192, 192, 1)'
                            , backgroundColor: 'rgba(75, 192, 192, 0.2)'
                            , borderWidth: 2
                            , tension: 0.3
                            , fill: true
                        }]
                    }
                    , options: {
                        responsive: true
                        , plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                        , scales: {
                            y: {
                                beginAtZero: true
                                , ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }
    }

</script>
@endonce
