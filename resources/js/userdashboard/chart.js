import ApexCharts from 'apexcharts';

document.addEventListener('DOMContentLoaded', () => {
    // Helper: format currency for Rwandan Market
    function formatCurrency(value) {
        if (value == null) return '-';
        return new Intl.NumberFormat('en-RW', {
            style: 'currency',
            currency: 'RWF',
            maximumFractionDigits: 0
        }).format(value);
    }

    // 1. Sales vs Purchase Bar Chart
    const spEl = document.getElementById('salesPurchaseChart');
    if (spEl) {
        const spOptions = {
            series: [
                { name: 'Sales', data: window.dashboardData?.sales || [] },
                { name: 'Purchase', data: window.dashboardData?.purchases || [] }
            ],
            colors: ['#f7a085', '#E66239'],
            chart: { type: 'bar', height: 350, toolbar: { show: false } },
            plotOptions: { bar: { horizontal: false, columnWidth: '85%', borderRadius: 3 } },
            xaxis: { categories: ['28 Jan', '29 Jan', '30 Jan', '31 Jan', '1 Feb', '2 Feb', '3 Feb', '4 Feb', '5 Feb'] },
            yaxis: { labels: { formatter: (val) => val + 'k' } },
            tooltip: { y: { formatter: (val) => "$ " + val + "k" } }
        };
        new ApexCharts(spEl, spOptions).render();
    }

    // 2. Customer Radial Chart
    const custEl = document.getElementById('customerChart');
    if (custEl) {
        const custOptions = {
            series: [44, 55],
            chart: { height: 250, type: 'radialBar' },
            colors: ['#5BE49B', '#E66239'],
            labels: ['First Time', 'Return'],
            plotOptions: {
                radialBar: {
                    hollow: { size: '40%' },
                    track: { background: "#f0f0f0" },
                    dataLabels: { name: { fontSize: '22px' }, value: { fontSize: '16px' } }
                }
            },
            fill: {
                type: 'gradient',
                gradient: { shade: 'dark', type: 'vertical', gradientToColors: ['#007867', '#FFD666'], stops: [0, 100] }
            },
            stroke: { lineCap: 'round' }
        };
        new ApexCharts(custEl, custOptions).render();
    }

    // 3. Sales Overview Area Chart
    const salesEl = document.getElementById('salesChart');
    if (salesEl) {
        const salesThisYear = [42000, 53000, 48000, 61000, 72000, 69000, 74000, 82000, 78000, 86000, 91000, 97000];
        const salesLastYear = [38000, 45000, 47000, 56000, 65000, 63000, 68000, 70000, 69000, 75000, 80000, 84000];

        const sOptions = {
            chart: { id: 'sales-overview', type: 'area', height: 420, toolbar: { show: false } },
            colors: ['#E66239', '#198754'],
            stroke: { width: [3, 2.5], curve: 'smooth' },
            series: [
                { name: 'This Year', data: salesThisYear },
                { name: 'Last Year', data: salesLastYear }
            ],
            xaxis: { categories: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'] },
            yaxis: { labels: { formatter: (val) => formatCurrency(val) } },
            tooltip: { shared: true, y: { formatter: (val) => formatCurrency(val) } }
        };

        const salesChartObj = new ApexCharts(salesEl, sOptions);
        salesChartObj.render();

        // Handle buttons ONLY if they exist on the page
        const btnRandom = document.getElementById('btn-random');
        if (btnRandom) {
            btnRandom.addEventListener('click', () => {
                const rand = () => Math.round((Math.random() * 80 + 20) * 1000);
                salesChartObj.updateSeries([
                    { name: 'This Year', data: Array.from({length: 12}, rand) },
                    { name: 'Last Year', data: Array.from({length: 12}, rand) }
                ]);
            });
        }
    }
});