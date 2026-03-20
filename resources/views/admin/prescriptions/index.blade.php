@extends('admin.layouts.app')

@section('title', 'Medicine Requests')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Medicine Requests & Inventory</h2>
            <p class="mt-1 text-sm text-gray-500">Review patient medicine requests and manage health center stock</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Search patients..."
                       class="w-56 px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="absolute w-5 h-5 text-gray-400 left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <div class="flex overflow-hidden border border-gray-200 rounded-lg">
                <button onclick="showTab('requests')" id="tab-requests"
                        class="px-4 py-2 text-sm font-semibold text-white transition-colors bg-blue-600">
                    Requests
                </button>
                <button onclick="showTab('inventory')" id="tab-inventory"
                        class="px-4 py-2 text-sm font-semibold text-gray-600 transition-colors bg-white hover:bg-gray-50">
                    Inventory
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(()=>show=false,5000)"
         class="flex items-center gap-3 px-4 py-3 text-sm text-green-800 border border-green-200 rounded-lg bg-green-50">
        <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-center gap-3 px-4 py-3 text-sm text-red-700 border border-red-200 rounded-lg bg-red-50">
        {{ session('error') }}
    </div>
    @endif

    {{-- ══ REQUESTS TAB ══ --}}
    <div id="panel-requests">
        <div class="p-6 bg-white shadow rounded-xl">
            <div class="overflow-x-auto">
                <table class="w-full" id="prescriptionsTable">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">No.</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Patient</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Medicine</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Dosage / Qty</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Rx</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Status</th>
                            <th class="px-4 py-3 text-sm font-semibold text-center text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($prescriptions as $prescription)
                        <tr class="transition-colors hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $prescription['no'] }}</td>
                            <td class="px-4 py-3 text-sm">
                                <p class="font-semibold text-gray-900">{{ $prescription['patient_name'] }}</p>
                                <p class="text-xs text-gray-500">Age: {{ $prescription['age'] }}</p>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <p class="font-medium text-gray-900">{{ $prescription['medicine_request'] }}</p>
                                @if($prescription['frequency'])
                                    <p class="text-xs text-gray-500">{{ $prescription['frequency'] }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                <span>{{ $prescription['dosage'] ?? '—' }}</span>
                                @if(!empty($prescription['quantity_requested']))
                                    <span class="ml-1 text-xs text-gray-400">/ {{ $prescription['quantity_requested'] }} pcs</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($prescription['prescription_image'])
                                    <button onclick="openImageModal('{{ asset('storage/' . $prescription['prescription_image']) }}')"
                                            class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full hover:bg-blue-200">
                                        📋 With Rx
                                    </button>
                                @else
                                    <span class="px-2 py-1 text-xs text-gray-500 bg-gray-100 rounded-full">No Rx</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($prescription['approval_status'] === 'approved')
                                    <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">✓ Approved</span>
                                @elseif($prescription['approval_status'] === 'rejected')
                                    <span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">✗ Rejected</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">⏳ Pending</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.prescriptions.show', $prescription['id']) }}"
                                       class="px-3 py-1.5 text-xs font-semibold text-white bg-gray-700 rounded hover:bg-gray-900">
                                        View
                                    </a>
                                    @if($prescription['approval_status'] === 'pending')
                                    <form method="POST" action="{{ route('admin.prescriptions.approve', $prescription['id']) }}" class="inline">
                                        @csrf
                                        <button type="submit"
                                                onclick="return confirm('Approve this request? The quantity will be automatically deducted from inventory.')"
                                                class="px-3 py-1.5 text-xs font-semibold text-white bg-green-600 rounded hover:bg-green-700">
                                            Approve
                                        </button>
                                    </form>
                                    <button onclick="openRejectModal({{ $prescription['id'] }})"
                                            class="px-3 py-1.5 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700">
                                        Reject
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-sm text-center text-gray-500">No medicine requests found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ══ INVENTORY TAB (reads from DB) ══ --}}
    <div id="panel-inventory" class="hidden">

        {{-- Stock Summary Cards --}}
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="p-4 bg-white border-2 border-green-200 shadow rounded-xl">
                <p class="text-sm font-medium text-gray-600">In Stock</p>
                <p class="mt-1 text-3xl font-bold text-green-600">
                    {{ $inventory->where('stock', '>=', 50)->count() }}
                </p>
                <p class="text-xs text-gray-500">medicines</p>
            </div>
            <div class="p-4 bg-white border-2 border-yellow-200 shadow rounded-xl">
                <p class="text-sm font-medium text-gray-600">Low Stock</p>
                <p class="mt-1 text-3xl font-bold text-yellow-600">
                    {{ $inventory->where('stock', '>', 0)->where('stock', '<', 50)->count() }}
                </p>
                <p class="text-xs text-gray-500">medicines (&lt; 50 pcs)</p>
            </div>
            <div class="p-4 bg-white border-2 border-red-200 shadow rounded-xl">
                <p class="text-sm font-medium text-gray-600">Out of Stock</p>
                <p class="mt-1 text-3xl font-bold text-red-600">
                    {{ $inventory->where('stock', '<=', 0)->count() }}
                </p>
                <p class="text-xs text-gray-500">medicines</p>
            </div>
        </div>

        <div class="p-6 bg-white shadow rounded-xl">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Medicine Inventory</h3>
                    <p class="mt-0.5 text-xs text-gray-500">
                        Stock automatically deducts when a medicine request is approved.
                    </p>
                </div>
                <button onclick="document.getElementById('addMedicineModal').classList.remove('hidden')"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Medicine
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full" id="inventoryTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Medicine Name</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Category</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Stock</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Unit</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Status</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Available</th>
                            <th class="px-4 py-3 text-sm font-semibold text-center text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($inventory as $med)
                        <tr class="transition-colors hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $med->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $med->category }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="font-bold text-lg {{ $med->stock <= 0 ? 'text-red-600' : ($med->stock < 50 ? 'text-yellow-600' : 'text-gray-900') }}">
                                    {{ $med->stock }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $med->unit }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if($med->stock <= 0)
                                    <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Out of Stock</span>
                                @elseif($med->stock < 50)
                                    <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">⚠ Low Stock</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">In Stock</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <form method="POST" action="{{ route('admin.inventory.toggleAvailable', $med->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="px-2 py-1 text-xs font-semibold rounded-full transition-colors {{ $med->is_available ? 'text-green-700 bg-green-100 hover:bg-green-200' : 'text-gray-600 bg-gray-100 hover:bg-gray-200' }}">
                                        {{ $med->is_available ? '✓ Visible' : '✗ Hidden' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                <button onclick="openEditStock({{ $med->id }}, '{{ addslashes($med->name) }}', {{ $med->stock }}, '{{ $med->unit }}')"
                                        class="px-3 py-1.5 text-xs font-semibold text-white bg-blue-600 rounded hover:bg-blue-700">
                                    Edit Stock
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-sm text-center text-gray-500">
                                No medicines in inventory. <a href="#" onclick="document.getElementById('addMedicineModal').classList.remove('hidden')" class="text-blue-600 hover:underline">Add one now.</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Image Modal --}}
<div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75" onclick="closeImageModal()">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative max-w-2xl" onclick="event.stopPropagation()">
            <button onclick="closeImageModal()" class="absolute top-0 right-0 p-2 -mt-10 -mr-10 text-white bg-red-600 rounded-full hover:bg-red-700">✕</button>
            <img id="modalImage" src="" alt="Prescription" class="max-w-full max-h-screen rounded-lg shadow-2xl">
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div id="rejectModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="w-full max-w-md p-6 bg-white shadow-2xl rounded-2xl">
            <h3 class="mb-4 text-xl font-bold text-gray-900">Reject Medicine Request</h3>
            <form id="rejectForm" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block mb-1.5 text-sm font-semibold text-gray-700">Reason for rejection <span class="text-red-500">*</span></label>
                    <textarea name="reason" required rows="4"
                              class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-400"
                              placeholder="Explain why this request is being rejected..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeRejectModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700">Confirm Rejection</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Stock Modal --}}
<div id="editStockModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="w-full max-w-sm p-6 bg-white shadow-2xl rounded-2xl">
            <h3 class="mb-1 text-xl font-bold text-gray-900">Edit Stock</h3>
            <p id="editStockName" class="mb-5 text-sm text-gray-500"></p>
            <form id="editStockForm" method="POST" class="space-y-4">
                @csrf @method('PATCH')
                <div>
                    <label class="block mb-1.5 text-sm font-semibold text-gray-700">Stock Count</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="stock" id="editStockInput" min="0"
                               class="flex-1 px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <span id="editStockUnit" class="text-sm text-gray-500"></span>
                    </div>
                </div>
                <div class="p-3 text-xs text-blue-800 border border-blue-200 rounded-lg bg-blue-50">
                    💡 Use this to restock when new medicines arrive at the health center.
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeEditStock()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                    <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">Save Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Add Medicine Modal --}}
<div id="addMedicineModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="w-full max-w-md p-6 bg-white shadow-2xl rounded-2xl">
            <h3 class="mb-5 text-xl font-bold text-gray-900">Add New Medicine</h3>
            <form method="POST" action="{{ route('admin.inventory.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block mb-1.5 text-sm font-semibold text-gray-700">Medicine Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="e.g. Amoxicillin 250mg"
                           class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block mb-1.5 text-sm font-semibold text-gray-700">Category</label>
                    <select name="category" class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option>Pain Relief / Fever</option>
                        <option>Antibiotic</option>
                        <option>Antihistamine</option>
                        <option>Hypertension</option>
                        <option>Diabetes</option>
                        <option>Supplement</option>
                        <option>Vitamin / Supplement</option>
                        <option>Rehydration</option>
                        <option>Respiratory</option>
                        <option>Gastrointestinal</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1.5 text-sm font-semibold text-gray-700">Initial Stock</label>
                        <input type="number" name="stock" min="0" value="0"
                               class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-1.5 text-sm font-semibold text-gray-700">Unit</label>
                        <select name="unit" class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option>tablets</option>
                            <option>capsules</option>
                            <option>bottles</option>
                            <option>sachets</option>
                            <option>vials</option>
                            <option>ampules</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('addMedicineModal').classList.add('hidden')"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                    <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">Add Medicine</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showTab(tab) {
    ['requests','inventory'].forEach(t => {
        document.getElementById('panel-' + t).classList.toggle('hidden', t !== tab);
        const btn = document.getElementById('tab-' + t);
        btn.classList.toggle('bg-blue-600', t === tab);
        btn.classList.toggle('text-white',    t === tab);
        btn.classList.toggle('bg-white',      t !== tab);
        btn.classList.toggle('text-gray-600', t !== tab);
    });
}

document.getElementById('searchInput').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#prescriptionsTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});

function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').classList.remove('hidden');
}
function closeImageModal() { document.getElementById('imageModal').classList.add('hidden'); }

function openRejectModal(id) {
    document.getElementById('rejectForm').action = '/admin/prescriptions/' + id + '/reject';
    document.getElementById('rejectModal').classList.remove('hidden');
}
function closeRejectModal() { document.getElementById('rejectModal').classList.add('hidden'); }

function openEditStock(id, name, stock, unit) {
    document.getElementById('editStockName').textContent = name;
    document.getElementById('editStockInput').value = stock;
    document.getElementById('editStockUnit').textContent = unit;
    document.getElementById('editStockForm').action = '/admin/inventory/' + id;
    document.getElementById('editStockModal').classList.remove('hidden');
}
function closeEditStock() { document.getElementById('editStockModal').classList.add('hidden'); }
</script>
@endsection