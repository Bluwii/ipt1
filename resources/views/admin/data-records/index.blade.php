@extends('admin.layouts.app')

@section('title', 'Data Records Analytics')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <h2 class="text-2xl font-bold text-gray-900">Data Record's For Each Month</h2>

    <!-- Month Buttons -->
    <div class="grid grid-cols-4 gap-3 md:grid-cols-6 lg:grid-cols-12">
        @foreach($months as $month)
        <button onclick="selectMonth('{{ $month }}', this)"
                data-month="{{ $month }}"
                class="px-4 py-2 text-sm font-semibold text-gray-700 transition-all border-2 border-gray-300 rounded-lg month-btn hover:border-blue-500 hover:bg-blue-50">
            {{ $month }}
        </button>
        @endforeach
    </div>

    <!-- Option Cards — hidden until a month is selected -->
    <div id="optionCards" class="hidden">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">

            <!-- Visit / Data Records -->
            <button onclick="openChart('records')"
                    class="p-8 text-center transition-all bg-white border-2 border-gray-200 shadow rounded-xl hover:border-orange-500 hover:shadow-lg">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-orange-100 rounded-full">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Data Record's For Each Month</h3>
                <p class="mt-1 text-sm text-gray-500">Daily visit totals — bar chart</p>
            </button>

            <!-- Top Prescribed Medicines -->
            <button onclick="openChart('medicines')"
                    class="p-8 text-center transition-all bg-white border-2 border-gray-200 shadow rounded-xl hover:border-blue-500 hover:shadow-lg">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-blue-100 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Top Prescribed Medicines</h3>
                <p class="mt-1 text-sm text-gray-500">Most requested medications — pie chart</p>
            </button>

            <!-- Patient Age Visit -->
            <button onclick="openChart('age_visit')"
                    class="p-8 text-center transition-all bg-white border-2 border-gray-200 shadow rounded-xl hover:border-purple-500 hover:shadow-lg">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-purple-100 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Patient Age Visit Per Month</h3>
                <p class="mt-1 text-sm text-gray-500">Age group breakdown — donut chart</p>
            </button>

            <!-- Walk-in vs Online -->
            <button onclick="openChart('walkin_online')"
                    class="p-8 text-center transition-all bg-white border-2 border-gray-200 shadow rounded-xl hover:border-orange-400 hover:shadow-lg">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-orange-100 rounded-full">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Walk-in vs Online</h3>
                <p class="mt-1 text-sm text-gray-500">Daily booking source comparison</p>
            </button>
        </div>
    </div>

</div>

<!-- ── Chart Modal ──────────────────────────────────────────────────────── -->
<div id="chartModal"
     class="fixed inset-0 z-50 items-center justify-center hidden bg-black bg-opacity-50"
     onclick="closeModal(event)">

    <div id="modalBox"
         class="relative w-full max-w-3xl p-8 mx-4 bg-white shadow-2xl rounded-2xl">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 id="modalTitle" class="text-xl font-bold text-gray-900">Loading…</h3>
                <p id="modalSubtitle" class="text-sm text-gray-500 mt-0.5"></p>
            </div>
            <button onclick="closeModal()"
                    class="p-2 text-gray-400 rounded-lg hover:text-gray-700 hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Spinner -->
        <div id="chartSpinner" class="flex items-center justify-center" style="height:380px;">
            <div class="w-12 h-12 border-4 border-orange-500 rounded-full border-t-transparent animate-spin"></div>
        </div>

        <!-- Chart -->
        <div id="chartWrapper" class="hidden" style="position:relative; height:380px;">
            <canvas id="modalChart"></canvas>
        </div>

        <!-- Walk-in vs Online summary badges -->
        <div id="chartSummary" class="justify-center hidden gap-6 mt-4">
            <span class="px-4 py-2 text-sm font-semibold text-blue-800 bg-blue-100 rounded-full">
                🌐 Online: <span id="summaryOnline">0</span>
            </span>
            <span class="px-4 py-2 text-sm font-semibold text-orange-800 bg-orange-100 rounded-full">
                🚶 Walk-in: <span id="summaryWalkin">0</span>
            </span>
        </div>

        <!-- Footer -->
        <div class="flex justify-end mt-6">
            <button onclick="closeModal()"
                    class="px-6 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                Close
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
    let selectedMonth = '';
    let chartInstance = null;

    /* ── Month selection ─────────────────────────────────────────────── */
    function selectMonth(month, btn) {
        selectedMonth = month;

        // Toggle button highlight
        document.querySelectorAll('.month-btn').forEach(b => {
            b.classList.remove('border-blue-500', 'bg-blue-50', 'text-blue-700');
            b.classList.add('border-gray-300', 'text-gray-700');
        });
        btn.classList.add('border-blue-500', 'bg-blue-50', 'text-blue-700');
        btn.classList.remove('border-gray-300', 'text-gray-700');

        // Show option cards
        document.getElementById('optionCards').classList.remove('hidden');
    }

    /* ── Open modal & fetch chart data ───────────────────────────────── */
    async function openChart(type) {
        if (!selectedMonth) return;

        const modal   = document.getElementById('chartModal');
        const spinner = document.getElementById('chartSpinner');
        const wrapper = document.getElementById('chartWrapper');
        const title   = document.getElementById('modalTitle');
        const subtitle = document.getElementById('modalSubtitle');

        // Show modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        spinner.classList.remove('hidden');
        wrapper.classList.add('hidden');
        title.textContent  = 'Loading…';
        subtitle.textContent = '';

        // Destroy old chart
        if (chartInstance) { chartInstance.destroy(); chartInstance = null; }

        try {
            const res = await fetch('{{ route("admin.data-records.monthly-data") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ month: selectedMonth, type }),
            });

            const data = await res.json();

            title.textContent    = data.title;
            subtitle.textContent = selectedMonth + ' {{ now()->year }}';

            spinner.classList.add('hidden');
            wrapper.classList.remove('hidden');

            // Show summary badges only for walk-in vs online
            const summaryEl = document.getElementById('chartSummary');
            if (data.summary) {
                document.getElementById('summaryOnline').textContent = data.summary.online;
                document.getElementById('summaryWalkin').textContent = data.summary.walkin;
                summaryEl.classList.remove('hidden');
                summaryEl.classList.add('flex');
            } else {
                summaryEl.classList.add('hidden');
                summaryEl.classList.remove('flex');
            }

            renderChart(data);

        } catch (err) {
            console.error(err);
            spinner.classList.add('hidden');
            title.textContent = 'Error loading chart.';
        }
    }

    /* ── Render Chart.js ─────────────────────────────────────────────── */
    function renderChart(data) {
        const canvas = document.getElementById('modalChart');
        const ctx    = canvas.getContext('2d');

        // Shared options
        const baseOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true, position: 'bottom' },
                tooltip: { mode: 'index', intersect: false },
            },
        };

        if (data.chartType === 'bar') {
            chartInstance = new Chart(ctx, {
                type: 'bar',
                data: data.data,
                options: {
                    ...baseOptions,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, precision: 0 },
                            grid: { color: 'rgba(0,0,0,0.05)' },
                        },
                        x: { grid: { display: false } },
                    },
                },
            });

        } else if (data.chartType === 'pie') {
            chartInstance = new Chart(ctx, {
                type: 'pie',
                data: data.data,
                options: {
                    ...baseOptions,
                    plugins: {
                        legend: { display: true, position: 'right' },
                        tooltip: { mode: 'nearest', intersect: true },
                    },
                },
            });

        } else if (data.chartType === 'doughnut') {
            chartInstance = new Chart(ctx, {
                type: 'doughnut',
                data: data.data,
                options: {
                    ...baseOptions,
                    cutout: '55%',
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: { usePointStyle: true, padding: 16 },
                        },
                        tooltip: { mode: 'nearest', intersect: true },
                    },
                },
            });
        }
    }

    /* ── Close modal ─────────────────────────────────────────────────── */
    function closeModal(event) {
        // If clicking backdrop (not the inner box) or close button
        if (event && event.target !== document.getElementById('chartModal')) return;

        const modal = document.getElementById('chartModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');

        if (chartInstance) { chartInstance.destroy(); chartInstance = null; }
    }

    // Close button calls closeModal() with no event — handle that
    const origClose = closeModal;
    window.closeModal = function(event) {
        if (!event) {
            const modal = document.getElementById('chartModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            if (chartInstance) { chartInstance.destroy(); chartInstance = null; }
            document.getElementById('chartSummary').classList.add('hidden');
            document.getElementById('chartSummary').classList.remove('flex');
            return;
        }
        if (event.target === document.getElementById('chartModal')) {
            const modal = document.getElementById('chartModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            if (chartInstance) { chartInstance.destroy(); chartInstance = null; }
            document.getElementById('chartSummary').classList.add('hidden');
            document.getElementById('chartSummary').classList.remove('flex');
        }
    };
</script>
@endpush