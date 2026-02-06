@extends('admin.layouts.app')

@section('title', 'Data Records Analytics')

@section('content')
<div class="space-y-6" x-data="dataRecordsApp()">
    <!-- Page Header -->
    <h2 class="text-2xl font-bold text-gray-900">Data Record's For Each Month</h2>

    <!-- Month Buttons -->
    <div class="grid grid-cols-4 gap-4 md:grid-cols-8">
        @foreach($months as $month)
        <button @click="selectMonth('{{ $month }}')" 
                class="px-4 py-2 text-sm font-semibold transition-all border-2 border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50"
                :class="selectedMonth === '{{ $month }}' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'text-gray-700'">
            {{ $month }}
        </button>
        @endforeach
    </div>

    <!-- Options Cards (Show when month is selected) -->
    <div x-show="selectedMonth" x-cloak class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <!-- Data Records For Each Month -->
        <button @click="openGraph('records')" 
                class="p-8 text-center transition-all bg-white border-2 border-gray-200 shadow rounded-xl hover:border-blue-500 hover:shadow-lg">
            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-blue-100 rounded-full">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Data Record's For Each Month</h3>
        </button>

        <!-- Top Prescribed Medicines -->
        <button @click="openGraph('medicines')" 
                class="p-8 text-center transition-all bg-white border-2 border-gray-200 shadow rounded-xl hover:border-green-500 hover:shadow-lg">
            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-green-100 rounded-full">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Top Prescribed Medicines</h3>
        </button>

        <!-- Patient Age Visit Per Month -->
        <button @click="openGraph('age_visit')" 
                class="p-8 text-center transition-all bg-white border-2 border-gray-200 shadow rounded-xl hover:border-purple-500 hover:shadow-lg">
            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-purple-100 rounded-full">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Patient Age Visit Per Month</h3>
        </button>
    </div>

    <!-- Graph Modal -->
    <div x-show="showModal" 
         x-cloak
         @click.self="closeModal()"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="relative w-full max-w-4xl p-8 mx-4 bg-white shadow-2xl rounded-2xl"
             @click.stop
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900" x-text="graphTitle"></h3>
                <button @click="closeModal()" class="p-2 text-gray-400 transition-colors rounded-lg hover:text-gray-600 hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Chart Container -->
            <div class="relative" style="height: 400px;">
                <canvas id="modalChart"></canvas>
            </div>

            <!-- Close Button -->
            <div class="flex justify-end mt-6">
                <button @click="closeModal()" 
                        class="px-6 py-2 font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function dataRecordsApp() {
    return {
        selectedMonth: '',
        showModal: false,
        graphTitle: '',
        chartInstance: null,
        
        selectMonth(month) {
            this.selectedMonth = month;
        },
        
        async openGraph(type) {
            if (!this.selectedMonth) return;
            
            this.showModal = true;
            
            // Fetch data from server
            try {
                const response = await fetch('{{ route("admin.data-records.monthly-data") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        month: this.selectedMonth,
                        type: type
                    })
                });
                
                const data = await response.json();
                this.graphTitle = data.title;
                
                // Wait for modal to be visible
                this.$nextTick(() => {
                    this.renderChart(data);
                });
                
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        },
        
        renderChart(data) {
            // Destroy existing chart if any
            if (this.chartInstance) {
                this.chartInstance.destroy();
            }
            
            const ctx = document.getElementById('modalChart').getContext('2d');
            
            this.chartInstance = new Chart(ctx, {
                type: data.chartType,
                data: data.data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    },
                    scales: data.chartType !== 'pie' ? {
                        y: {
                            beginAtZero: true
                        }
                    } : {}
                }
            });
        },
        
        closeModal() {
            this.showModal = false;
            if (this.chartInstance) {
                this.chartInstance.destroy();
                this.chartInstance = null;
            }
        }
    }
}
</script>
@endpush