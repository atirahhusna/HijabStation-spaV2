<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Order Status per Month') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-pink-500 py-12 text-gray-800">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white shadow-lg rounded-lg p-6">
            <canvas id="ordersChart" width="400" height="200"></canvas>
        </div>

        @include('layouts.footer')
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('ordersChart').getContext('2d');
        const ordersChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Orders Done',
                    data: @json($data),
                    backgroundColor: 'rgba(237, 85, 101, 0.7)',
                    borderColor: 'rgba(237, 85, 101, 1)',
                    borderWidth: 1,
                    borderRadius: 5,
                    maxBarThickness: 50,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true, position: 'top' },
                    title: { display: true, text: 'Orders Completed by Month' }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    </script>
</x-app-layout>
