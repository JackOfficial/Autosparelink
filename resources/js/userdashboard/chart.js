import ApexCharts from 'apexcharts';

document.addEventListener('DOMContentLoaded', () => {
    // Helper: format currency for Rwandan Market (No decimals for RWF)
    function formatCurrency(value) {
        if (value == null) return '-';
        return new Intl.NumberFormat('en-RW', {
            style: 'currency',
            currency: 'RWF',
            maximumFractionDigits: 0
        }).format(value);
    }

    // 1. Sales Performance Chart (Dynamic from Controller)
    const spEl = document.getElementById('salesPurchaseChart');
    if (spEl) {
        const spOptions = {
            series: [
                { 
                    name: 'Revenue', 
                    data: window.dashboardData?.sales || [] 
                }
            ],
            // Using a professional blue/primary theme for AutoSpareLink
            colors: ['#0D8ABC'], 
            chart: { 
                type: 'area', 
                height: 350, 
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [20, 100]
                }
            },
            // Dynamically inject dates from Laravel
            xaxis: { 
                categories: window.dashboardData?.labels || [],
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: { 
                labels: { 
                    formatter: (val) => {
                        return val >= 1000 ? (val / 1000).toFixed(0) + 'k' : val;
                    } 
                } 
            },
            tooltip: { 
                theme: 'light',
                x: { format: 'dd MMM' },
                y: { 
                    formatter: (val) => formatCurrency(val) 
                } 
            },
            grid: {
                borderColor: '#f1f1f1',
                padding: { pb: 15 }
            }
        };
        new ApexCharts(spEl, spOptions).render();
    }

    // 2. Inventory Distribution / Stock Status (Optional/Future use)
    // We can use this to show Low Stock vs Healthy Stock
    const custEl = document.getElementById('customerChart');
    if (custEl) {
        const custOptions = {
            series: [window.dashboardData?.lowStock || 30, 70], // Example data
            chart: { height: 250, type: 'radialBar' },
            colors: ['#ff4560', '#00e396'],
            labels: ['Low Stock', 'Healthy'],
            plotOptions: {
                radialBar: {
                    hollow: { size: '50%' },
                    track: { background: "#f8f9fa" },
                    dataLabels: { 
                        name: { fontSize: '14px', color: '#6c757d', offsetY: -10 }, 
                        value: { fontSize: '20px', fontWeight: 'bold', offsetY: 5 } 
                    }
                }
            },
            stroke: { lineCap: 'round' }
        };
        new ApexCharts(custEl, custOptions).render();
    }
});