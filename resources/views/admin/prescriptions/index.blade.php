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
            {{-- Tab: Requests | Inventory --}}
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
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(()=>show=false,4000)"
         class="flex items-center gap-3 px-4 py-3 text-sm text-green-800 border border-green-200 rounded-lg bg-green-50">
        <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
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
                                            class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold text-blue-700 transition-colors bg-blue-100 rounded-full hover:bg-blue-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        With Rx
                                    </button>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 text-xs text-gray-500 bg-gray-100 rounded-full">
                                        No Rx
                                    </span>
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
                                    {{-- View Detail --}}
                                    <a href="{{ route('admin.prescriptions.show', $prescription['id']) }}"
                                       class="px-3 py-1.5 text-xs font-semibold text-white bg-gray-700 rounded hover:bg-gray-900">
                                        View
                                    </a>

                                    {{-- Quick approve/reject for pending --}}
                                    @if($prescription['approval_status'] === 'pending')
                                    <form method="POST" action="{{ route('admin.prescriptions.approve', $prescription['id']) }}" class="inline">
                                        @csrf
                                        <button type="submit"
                                                onclick="return confirm('Approve this medicine request?')"
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
                            <td colspan="7" class="px-4 py-12 text-sm text-center text-gray-500">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    No medicine requests found.
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ══ INVENTORY TAB ══ --}}
    <div id="panel-inventory" class="hidden">
        <div class="p-6 bg-white shadow rounded-xl">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-gray-900">Medicine Inventory</h3>
                <button onclick="document.getElementById('addMedicineModal').classList.remove('hidden')"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Medicine
                </button>
            </div>
            <p class="mb-4 text-sm text-gray-500">These medicines are shown to patients when they request medicine. Manage your health center's available stock here.</p>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Medicine Name</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Category</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Stock</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Unit</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Status</th>
                            <th class="px-4 py-3 text-sm font-semibold text-center text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php
                        $inventory = [
                            ['name'=>'Paracetamol 500mg','category'=>'Pain Relief / Fever','stock'=>500,'unit'=>'tablets','status'=>'In Stock'],
                            ['name'=>'Mefenamic Acid 500mg','category'=>'Pain Relief','stock'=>200,'unit'=>'tablets','status'=>'In Stock'],
                            ['name'=>'Amoxicillin 500mg','category'=>'Antibiotic','stock'=>150,'unit'=>'capsules','status'=>'In Stock'],
                            ['name'=>'Amoxicillin 250mg (Syrup)','category'=>'Antibiotic','stock'=>30,'unit'=>'bottles','status'=>'Low Stock'],
                            ['name'=>'Cetirizine 10mg','category'=>'Antihistamine','stock'=>100,'unit'=>'tablets','status'=>'In Stock'],
                            ['name'=>'Loratadine 10mg','category'=>'Antihistamine','stock'=>0,'unit'=>'tablets','status'=>'Out of Stock'],
                            ['name'=>'Amlodipine 5mg','category'=>'Hypertension','stock'=>300,'unit'=>'tablets','status'=>'In Stock'],
                            ['name'=>'Amlodipine 10mg','category'=>'Hypertension','stock'=>200,'unit'=>'tablets','status'=>'In Stock'],
                            ['name'=>'Losartan 50mg','category'=>'Hypertension','stock'=>250,'unit'=>'tablets','status'=>'In Stock'],
                            ['name'=>'Metformin 500mg','category'=>'Diabetes','stock'=>25,'unit'=>'tablets','status'=>'Low Stock'],
                            ['name'=>'Ferrous Sulfate 325mg','category'=>'Supplement','stock'=>400,'unit'=>'tablets','status'=>'In Stock'],
                            ['name'=>'Vitamin A 10,000 IU','category'=>'Vitamin / Supplement','stock'=>1000,'unit'=>'capsules','status'=>'In Stock'],
                            ['name'=>'Vitamin B Complex','category'=>'Vitamin / Supplement','stock'=>600,'unit'=>'tablets','status'=>'In Stock'],
                            ['name'=>'Multivitamins','category'=>'Vitamin / Supplement','stock'=>800,'unit'=>'tablets','status'=>'In Stock'],
                            ['name'=>'Ascorbic Acid 500mg','category'=>'Vitamin / Supplement','stock'=>500,'unit'=>'tablets','status'=>'In Stock'],
                            ['name'=>'ORS (Oral Rehydration Salts)','category'=>'Rehydration','stock'=>200,'unit'=>'sachets','status'=>'In Stock'],
                            ['name'=>'Salbutamol 2mg','category'=>'Respiratory','stock'=>0,'unit'=>'tablets','status'=>'Out of Stock'],
                            ['name'=>'Cotrimoxazole 400/80mg','category'=>'Antibiotic','stock'=>100,'unit'=>'tablets','status'=>'In Stock'],
                            ['name'=>'Omeprazole 20mg','category'=>'Gastrointestinal','stock'=>80,'unit'=>'capsules','status'=>'Low Stock'],
                            ['name'=>'Antacid (Aluminum Hydroxide)','category'=>'Gastrointestinal','stock'=>150,'unit'=>'tablets','status'=>'In Stock'],
                            ['name'=>'Ibuprofen 200mg','category'=>'Pain Relief / Anti-inflammatory','stock'=>300,'unit'=>'tablets','status'=>'In Stock'],
                        ];
                        @endphp
                        @foreach($inventory as $i => $med)
                        <tr class="transition-colors hover:bg-gray-50" id="inv-row-{{ $i }}">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $med['name'] }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $med['category'] }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span id="stock-display-{{ $i }}" class="font-semibold {{ $med['stock'] == 0 ? 'text-red-600' : ($med['stock'] < 50 ? 'text-yellow-600' : 'text-gray-900') }}">
                                    {{ $med['stock'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $med['unit'] }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if($med['status'] === 'In Stock')
                                    <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">In Stock</span>
                                @elseif($med['status'] === 'Low Stock')
                                    <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Low Stock</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Out of Stock</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="openEditInventory({{ $i }}, '{{ addslashes($med['name']) }}', {{ $med['stock'] }}, '{{ $med['unit'] }}')"
                                            class="px-3 py-1.5 text-xs font-semibold text-white bg-blue-600 rounded hover:bg-blue-700">
                                        Edit Stock
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
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
            <button onclick="closeImageModal()"
                    class="absolute top-0 right-0 p-2 -mt-10 -mr-10 text-white bg-red-600 rounded-full hover:bg-red-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
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
                    <label class="block mb-1.5 text-sm font-semibold text-gray-700">
                        Reason for rejection <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" required rows="4"
                              class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-400"
                              placeholder="Explain why this request is being rejected..."></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-1">
                    <button type="button" onclick="closeRejectModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700">
                        Confirm Rejection
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Inventory Modal --}}
<div id="editInventoryModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="w-full max-w-md p-6 bg-white shadow-2xl rounded-2xl">
            <h3 class="mb-1 text-xl font-bold text-gray-900">Edit Stock</h3>
            <p id="editMedicineName" class="mb-5 text-sm text-gray-500"></p>
            <div class="space-y-4">
                <div>
                    <label class="block mb-1.5 text-sm font-semibold text-gray-700">Current Stock</label>
                    <input type="number" id="editStockInput" min="0"
                           class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block mb-1.5 text-sm font-semibold text-gray-700">Unit</label>
                    <input type="text" id="editUnitInput" readonly
                           class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-lg bg-gray-50 text-gray-500">
                </div>
                <div class="p-3 text-xs text-blue-800 border border-blue-200 rounded-lg bg-blue-50">
                    💡 Updating stock here helps the admin track how much medicine is available for patient requests. Low stock (&lt;50) and out of stock (0) will be highlighted.
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-5">
                <button onclick="closeEditInventory()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    Cancel
                </button>
                <button onclick="saveInventory()"
                        class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Save Stock
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Add Medicine Modal --}}
<div id="addMedicineModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="w-full max-w-md p-6 bg-white shadow-2xl rounded-2xl">
            <h3 class="mb-5 text-xl font-bold text-gray-900">Add New Medicine to Inventory</h3>
            <div class="space-y-4">
                <div>
                    <label class="block mb-1.5 text-sm font-semibold text-gray-700">Medicine Name <span class="text-red-500">*</span></label>
                    <input type="text" id="newMedicineName" placeholder="e.g. Amoxicillin 250mg"
                           class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block mb-1.5 text-sm font-semibold text-gray-700">Category</label>
                    <select id="newMedicineCategory"
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
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
                        <input type="number" id="newMedicineStock" min="0" value="0"
                               class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-1.5 text-sm font-semibold text-gray-700">Unit</label>
                        <select id="newMedicineUnit"
                                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option>tablets</option>
                            <option>capsules</option>
                            <option>bottles</option>
                            <option>sachets</option>
                            <option>vials</option>
                            <option>ampules</option>
                        </select>
                    </div>
                </div>
                <div class="p-3 text-xs border rounded-lg text-amber-800 bg-amber-50 border-amber-200">
                    ⚠️ Note: After adding a medicine here, update the medicine list in the <code class="px-1 rounded bg-amber-100">records/index.blade.php</code> file so patients can select it when requesting.
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-5">
                <button onclick="document.getElementById('addMedicineModal').classList.add('hidden')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    Cancel
                </button>
                <button onclick="addMedicine()"
                        class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Add Medicine
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// ── Tab switching ─────────────────────────────────────────────────────────
function showTab(tab) {
    ['requests', 'inventory'].forEach(t => {
        document.getElementById('panel-' + t).classList.toggle('hidden', t !== tab);
        const btn = document.getElementById('tab-' + t);
        if (t === tab) {
            btn.classList.add('bg-blue-600', 'text-white');
            btn.classList.remove('bg-white', 'text-gray-600');
        } else {
            btn.classList.remove('bg-blue-600', 'text-white');
            btn.classList.add('bg-white', 'text-gray-600');
        }
    });
}

// ── Search ────────────────────────────────────────────────────────────────
document.getElementById('searchInput').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#prescriptionsTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});

// ── Image Modal ───────────────────────────────────────────────────────────
function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').classList.remove('hidden');
}
function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// ── Reject Modal ──────────────────────────────────────────────────────────
function openRejectModal(id) {
    document.getElementById('rejectForm').action = '/admin/prescriptions/' + id + '/reject';
    document.getElementById('rejectModal').classList.remove('hidden');
}
function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

// ── Edit Inventory ────────────────────────────────────────────────────────
let editingRow = null;

function openEditInventory(rowIndex, name, stock, unit) {
    editingRow = rowIndex;
    document.getElementById('editMedicineName').textContent = name;
    document.getElementById('editStockInput').value = stock;
    document.getElementById('editUnitInput').value = unit;
    document.getElementById('editInventoryModal').classList.remove('hidden');
}

function closeEditInventory() {
    document.getElementById('editInventoryModal').classList.add('hidden');
    editingRow = null;
}

function saveInventory() {
    if (editingRow === null) return;
    const newStock = parseInt(document.getElementById('editStockInput').value, 10);
    if (isNaN(newStock) || newStock < 0) {
        alert('Please enter a valid stock number.');
        return;
    }

    // Update the display cell
    const displayEl = document.getElementById('stock-display-' + editingRow);
    if (displayEl) {
        displayEl.textContent = newStock;
        displayEl.className = 'font-semibold ' + (
            newStock === 0 ? 'text-red-600' :
            newStock < 50 ? 'text-yellow-600' : 'text-gray-900'
        );
    }

    // Note: In production, send PATCH to /admin/medicine-inventory/{id}
    // For now, it updates the UI only
    closeEditInventory();

    // Show a brief success flash
    const flash = document.createElement('div');
    flash.className = 'fixed top-4 right-4 z-[999] px-4 py-3 text-sm font-semibold text-white bg-green-600 rounded-lg shadow-lg';
    flash.textContent = '✓ Stock updated successfully';
    document.body.appendChild(flash);
    setTimeout(() => flash.remove(), 2500);
}

function addMedicine() {
    const name = document.getElementById('newMedicineName').value.trim();
    if (!name) { alert('Please enter a medicine name.'); return; }
    document.getElementById('addMedicineModal').classList.add('hidden');

    const flash = document.createElement('div');
    flash.className = 'fixed top-4 right-4 z-[999] px-4 py-3 text-sm font-semibold text-white bg-blue-600 rounded-lg shadow-lg';
    flash.innerHTML = '✓ Medicine added. Remember to update the patient-facing medicine list in <code>records/index.blade.php</code>.';
    document.body.appendChild(flash);
    setTimeout(() => flash.remove(), 4000);
}
</script>
@endsection