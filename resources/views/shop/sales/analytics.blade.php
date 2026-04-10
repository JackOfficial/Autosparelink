<x-shop-dashboard>
    <x-slot:title>Sales Analytics</x-slot:title>

    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0" style="border-radius: 15px;">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-chart-line me-2 text-primary"></i>Revenue Trend (Last 30 Days)</h5>
                        <button class="btn btn-sm btn-light border" onclick="window.print()"><i class="fas fa-print"></i></button>
                    </div>
                    <div class="card-body">
                        <div style="height: 400px;">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Data Table Summary --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0" style="border-radius: 15px;">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold text-dark">Daily Breakdown</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 small text-muted">DATE</th>
                                        <th class="text-end pe-4 small text-muted">REVENUE (RWF)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($revenueData as $data)
                                        <tr>
                                            <td class="ps-4 fw-bold text-dark">
                                                {{ \Carbon\Carbon::parse($data->date)->format('M d, Y') }}
                                            </td>
                                            <td class="text-end pe-4 fw-bold text-primary">
                                                {{ number_format($data->total) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center py-4 text-muted">No data available for the selected period.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Growth Card --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 bg-primary text-white mb-4" style="border-radius: 15px;">
                    <div class="card-body p-4 text-center">
                        <div class="fs-1 mb-2 opacity-50"><i class="fas fa-award"></i></div>
                        <h5 class="fw-bold mb-1">Top Performance</h5>
                        <p class="small text-white-50">Highest daily revenue recorded:</p>
                        <h2 class="fw-bold mb-0">{{ number_format($revenueData->max('total')) }} RWF</h2>
                    </div>
                </div>
                
                <div class="card shadow-sm border-0" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Analytics Insights</h6>
                        <ul class="list-unstyled small mb-0">
                            <li class="mb-3 d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>Data covers only <strong>Delivered</strong> orders.</span>
                            </li>
                            <li class="mb-3 d-flex align-items-center">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                <span>Average daily revenue: <strong>{{ number_format($revenueData->avg('total')) }} RWF</strong></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Prepare data from PHP
        const rawData = @json($revenueData->reverse()->values());
        const labels = rawData.map(item => item.date);
        const totals = rawData.map(item => item.total);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenue (RWF)',
                    data: totals,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#0d6efd'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [5, 5] },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' RWF';
                            }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    </script>
    @endpush
</x-shop-dashboard>