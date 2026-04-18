import ApexCharts from 'apexcharts';

document.addEventListener('DOMContentLoaded', () => {
    // 0. Theme Helper: Detect if we are in light or dark mode
    const getTheme = () => document.documentElement.getAttribute('data-bs-theme') || 'light';

    // Helper: format currency for Rwandan Market (No decimals for RWF)
    function formatCurrency(value) {
        if (value == null) return '-';
        return new Intl.NumberFormat('en-RW', {
            style: 'currency',
            currency: 'RWF',
            maximumFractionDigits: 0
        }).format(value);
    }

    // Chart instances stored globally within this scope to allow updates
    let salesChart, inventoryChart;

    // 1. Sales Performance Chart (Revenue Analytics)
    const spEl = document.getElementById('salesPurchaseChart');
    if (spEl && window.dashboardData) {
        const spOptions = {
            series: [{ 
                name: 'Net Earnings', 
                data: window.dashboardData.sales || [] 
            }],
            colors: ['#0D8ABC'], 
            chart: { 
                type: 'area', 
                height: 350, 
                toolbar: { show: false },
                zoom: { enabled: false },
                background: 'transparent',
                fontFamily: 'inherit',
                theme: { mode: getTheme() }
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
            xaxis: { 
                categories: window.dashboardData.labels || [],
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#6c757d' } }
            },
            yaxis: { 
                labels: { 
                    style: { colors: '#6c757d' },
                    // Rwandan Francs get large; use 'k' for thousands
                    formatter: (val) => val >= 1000 ? (val / 1000).toFixed(0) + 'k' : val
                } 
            },
            tooltip: { 
                theme: getTheme(),
                x: { format: 'dd MMM' },
                y: { formatter: (val) => formatCurrency(val) } 
            },
            grid: {
                borderColor: getTheme() === 'dark' ? '#333' : '#f1f1f1',
                padding: { pb: 15 }
            }
        };
        salesChart = new ApexCharts(spEl, spOptions);
        salesChart.render();
    }

    // 2. Inventory Health Chart (Radial Bar)
    const custEl = document.getElementById('customerChart');
    if (custEl && window.dashboardData) {
        const custOptions = {
            // Series [Low Stock Count, Healthy Stock Count]
            series: window.dashboardData.inventoryStats || [0, 100],
            chart: { 
                height: 300, 
                type: 'radialBar',
                theme: { mode: getTheme() }
            },
            colors: ['#FB2C36', '#00C951'], // Red for Critical, Green for Healthy
            labels: ['Critical', 'Healthy'],
            plotOptions: {
                radialBar: {
                    hollow: { size: '60%' },
                    track: { background: getTheme() === 'dark' ? '#333' : "#f8f9fa" },
                    dataLabels: { 
                        name: { fontSize: '14px', color: '#6c757d', offsetY: -10 }, 
                        value: { 
                            fontSize: '22px', 
                            fontWeight: 'bold', 
                            offsetY: 5,
                            color: getTheme() === 'dark' ? '#fff' : '#333',
                            formatter: (val) => val // Show raw count instead of percentage
                        },
                        total: {
                            show: true,
                            label: 'Low Items',
                            color: '#6c757d',
                            // Specifically show the first value in our array (low stock)
                            formatter: () => window.dashboardData.inventoryStats[0]
                        }
                    }
                }
            },
            stroke: { lineCap: 'round' }
        };
        inventoryChart = new ApexCharts(custEl, custOptions);
        inventoryChart.render();
    }

    // 3. Theme Toggler Listener
    const themeToggler = document.getElementById('themeToggler');
    if (themeToggler) {
        themeToggler.addEventListener('click', () => {
            setTimeout(() => {
                const currentTheme = getTheme();
                const gridColor = currentTheme === 'dark' ? '#333' : '#f1f1f1';

                if (salesChart) {
                    salesChart.updateOptions({
                        chart: { theme: { mode: currentTheme } },
                        tooltip: { theme: currentTheme },
                        grid: { borderColor: gridColor }
                    });
                }

                if (inventoryChart) {
                    inventoryChart.updateOptions({
                        chart: { theme: { mode: currentTheme } },
                        plotOptions: {
                            radialBar: {
                                track: { background: currentTheme === 'dark' ? '#333' : "#f8f9fa" },
                                dataLabels: { value: { color: currentTheme === 'dark' ? '#fff' : '#333' } }
                            }
                        }
                    });
                }
            }, 50);
        });
    }
});