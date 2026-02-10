@extends('admin.layouts.app')

@section('title', 'Prescription Requests')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900">Prescription & Request</h2>
        
        <!-- Search -->
        <div class="relative">
            <input type="text" 
                   placeholder="Search" 
                   class="w-64 px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <svg class="absolute w-5 h-5 text-gray-400 left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>

    <!-- Prescriptions Table -->
    <div class="p-6 bg-white shadow rounded-xl">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">No.</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Patient Name's</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Age</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Dose</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Medicine Request</th>
                        <th class="px-4 py-3 text-sm font-semibold text-center text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($prescriptions as $prescription)
                    <tr class="transition-colors hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $prescription['no'] }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $prescription['patient_name'] }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $prescription['age'] }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $prescription['dose'] }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $prescription['medicine_request'] }}</td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center justify-center gap-2">
                                <!-- View Image Button -->
                                @if($prescription['prescription_image'])
                                    <button onclick="openImageModal('{{ asset('storage/' . $prescription['prescription_image']) }}')"
                                            class="px-4 py-1.5 text-xs font-semibold text-white bg-blue-600 rounded hover:bg-blue-700">
                                        View Image
                                    </button>
                                @endif
                                
                                <!-- Status Badge -->
                                @if($prescription['approval_status'] === 'pending')
                                    <span class="px-3 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pending</span>
                                @elseif($prescription['approval_status'] === 'approved')
                                    <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Approved</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Rejected</span>
                                @endif
                                
                                <!-- Approve/Reject Buttons (only for pending) -->
                                @if($prescription['approval_status'] === 'pending')
                                    <form method="POST" action="{{ route('admin.prescriptions.approve', $prescription['id']) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                onclick="return confirm('Approve this prescription request?')"
                                                class="px-4 py-1.5 text-xs font-semibold text-white bg-green-600 rounded hover:bg-green-700">
                                            Approve
                                        </button>
                                    </form>
                                    
                                    <button onclick="openRejectModal({{ $prescription['id'] }})"
                                            class="px-4 py-1.5 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700">
                                        Reject
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75" onclick="closeImageModal()">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative max-w-4xl" onclick="event.stopPropagation()">
            <button onclick="closeImageModal()" class="absolute top-0 right-0 p-2 -mt-10 -mr-10 text-white bg-red-600 rounded-full hover:bg-red-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <img id="modalImage" src="" alt="Prescription" class="max-w-full max-h-screen rounded-lg">
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="w-full max-w-md p-6 bg-white rounded-lg">
            <h3 class="mb-4 text-xl font-bold">Reject Prescription</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <label class="block mb-2 text-sm font-semibold">Reason for rejection:</label>
                <textarea name="reason" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" rows="4"></textarea>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-white bg-red-600 rounded hover:bg-red-700">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

function openRejectModal(id) {
    document.getElementById('rejectForm').action = '/admin/prescriptions/' + id + '/reject';
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
@endsection