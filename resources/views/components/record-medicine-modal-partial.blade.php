{{-- Prescription Request Modal section only --}}
{{-- Replace the existing modal in resources/views/records/index.blade.php --}}
{{-- The modal uses $availableMedicines passed from HealthRecordController --}}

<!-- ===================== MEDICINE REQUEST MODAL ===================== -->
<div x-show="prescriptionModal"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     @keydown.escape.window="prescriptionModal = false">

    <div class="fixed inset-0 transition-opacity bg-black bg-opacity-50"
         @click="prescriptionModal = false"></div>

    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative w-full max-w-xl overflow-hidden bg-white shadow-2xl rounded-2xl"
             @click.outside="prescriptionModal = false"
             x-data="medicineRequestForm()">

            <!-- Header -->
            <div class="px-6 py-5 bg-gradient-to-r from-purple-600 to-purple-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-white/20 rounded-xl">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Request Medicine</h3>
                            <p class="text-xs text-purple-200">Select from available health center stock</p>
                        </div>
                    </div>
                    <button @click="prescriptionModal = false"
                            class="p-2 transition-colors rounded-lg text-white/70 hover:text-white hover:bg-white/10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Patient Info Banner -->
            <div class="px-6 py-3 border-b border-purple-100 bg-purple-50">
                <div class="flex items-center gap-2 text-sm text-purple-800">
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>Requesting as: <strong>{{ Auth::user()->name }}</strong></span>
                    <span class="text-purple-400">•</span>
                    <span class="text-xs text-purple-600">{{ Auth::user()->email }}</span>
                </div>
            </div>

            <!-- Form Body -->
            <form id="medicineRequestForm" class="p-6 space-y-5" @submit.prevent="submitRequest()">
                @csrf

                <!-- Medicine Selection — from DB inventory -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700">
                        Select Medicine <span class="text-red-500">*</span>
                    </label>
                    @if($availableMedicines->isEmpty())
                        <div class="p-3 text-sm text-yellow-800 border border-yellow-200 rounded-lg bg-yellow-50">
                            No medicines currently available in inventory. Please contact the health center.
                        </div>
                    @else
                    <div class="relative">
                        <select name="medication_name" x-model="selectedMedicine" required
                                class="w-full py-3 pl-4 pr-10 text-sm bg-white border border-gray-300 appearance-none rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">-- Select medicine --</option>
                            @foreach($availableMedicines as $category => $medicines)
                                <optgroup label="{{ $category }}">
                                    @foreach($medicines as $med)
                                        <option value="{{ $med->name }}">
                                            {{ $med->name }}
                                            @if($med->stock < 50) (Low Stock: {{ $med->stock }} left)
                                            @else ({{ $med->stock }} {{ $med->unit }} available)
                                            @endif
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Dosage & Quantity -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-700">
                            Dosage <span class="text-red-500">*</span>
                        </label>
                        <select name="dosage" x-model="dosage" required
                                class="w-full px-4 py-3 text-sm bg-white border border-gray-300 appearance-none rounded-xl focus:ring-2 focus:ring-purple-500">
                            <option value="">Select dosage</option>
                            <option>1 tablet daily</option>
                            <option>1 tablet twice daily</option>
                            <option>1 tablet 3x daily</option>
                            <option>½ tablet daily</option>
                            <option>As needed (PRN)</option>
                            <option>As directed</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-700">
                            Quantity <span class="text-red-500">*</span>
                        </label>
                        <select name="quantity_requested" required
                                class="w-full px-4 py-3 text-sm bg-white border border-gray-300 appearance-none rounded-xl focus:ring-2 focus:ring-purple-500">
                            <option value="">Select qty</option>
                            <option value="10">10 pcs</option>
                            <option value="20">20 pcs</option>
                            <option value="30">30 pcs</option>
                            <option value="60">60 pcs</option>
                            <option value="90">90 pcs (3-month)</option>
                        </select>
                    </div>
                </div>

                <!-- Frequency & Duration -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-700">Frequency</label>
                        <select name="frequency"
                                class="w-full px-4 py-3 text-sm bg-white border border-gray-300 appearance-none rounded-xl focus:ring-2 focus:ring-purple-500">
                            <option value="">Select frequency</option>
                            <option>Once daily</option>
                            <option>Twice daily</option>
                            <option>Three times daily</option>
                            <option>Every 6 hours</option>
                            <option>Every 8 hours</option>
                            <option>As needed</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-700">Duration</label>
                        <select name="duration_days"
                                class="w-full px-4 py-3 text-sm bg-white border border-gray-300 appearance-none rounded-xl focus:ring-2 focus:ring-purple-500">
                            <option value="">Select duration</option>
                            <option value="3">3 days</option>
                            <option value="5">5 days</option>
                            <option value="7">7 days</option>
                            <option value="14">14 days</option>
                            <option value="30">30 days</option>
                            <option value="90">90 days</option>
                            <option value="0">Ongoing / Maintenance</option>
                        </select>
                    </div>
                </div>

                <!-- Purpose -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700">
                        Purpose / Condition <span class="font-normal text-gray-400">(optional)</span>
                    </label>
                    <textarea name="instructions" rows="2" maxlength="300"
                              class="block w-full px-4 py-3 text-sm border border-gray-300 resize-none rounded-xl focus:ring-2 focus:ring-purple-500"
                              placeholder="e.g. For hypertension maintenance, diabetes management..."></textarea>
                </div>

                <!-- Prescription Upload (optional) -->
                <div class="p-4 border-2 border-gray-200 border-dashed rounded-xl bg-gray-50">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-0.5">
                            <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-lg">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <label class="text-sm font-semibold text-gray-700">Doctor's Prescription</label>
                                <span class="px-2 py-0.5 text-xs font-semibold text-emerald-700 bg-emerald-100 rounded-full">Recommended</span>
                                <span class="text-xs font-normal text-gray-400">Optional</span>
                            </div>
                            <p class="mb-3 text-xs text-gray-500">With a valid prescription, your request is processed faster and with priority.</p>
                            <input type="file" name="prescription_image" id="prescriptionImageInput"
                                   accept="image/jpeg,image/png,image/jpg"
                                   @change="handleFileChange($event)"
                                   class="block w-full text-sm text-gray-500 cursor-pointer file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                            <p class="mt-1 text-xs text-gray-400">JPG or PNG, max 5MB</p>
                            <div x-show="previewUrl" class="relative inline-block mt-3">
                                <img :src="previewUrl" class="object-cover h-20 border border-gray-200 rounded-lg">
                                <button type="button" @click="clearFile()"
                                        class="absolute flex items-center justify-center w-5 h-5 text-xs text-white bg-red-500 rounded-full -top-2 -right-2 hover:bg-red-600">✕</button>
                                <p class="mt-1 text-xs font-medium text-green-600">✓ Prescription attached</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Error area -->
                <div id="medicineRequestError" class="hidden p-3 text-sm text-red-800 bg-red-100 border border-red-200 rounded-xl"></div>

                <!-- Submit Buttons -->
                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <button type="button" @click="prescriptionModal = false"
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="medicineSubmitBtn"
                            class="px-6 py-2.5 text-sm font-semibold text-white bg-purple-600 rounded-xl hover:bg-purple-700 disabled:opacity-50 transition-colors shadow-sm">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function medicineRequestForm() {
    return {
        selectedMedicine: '',
        dosage: '',
        previewUrl: null,

        handleFileChange(event) {
            const file = event.target.files[0];
            if (!file) { this.previewUrl = null; return; }
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be under 5MB.');
                event.target.value = '';
                this.previewUrl = null;
                return;
            }
            const reader = new FileReader();
            reader.onload = (e) => { this.previewUrl = e.target.result; };
            reader.readAsDataURL(file);
        },

        clearFile() {
            this.previewUrl = null;
            const input = document.getElementById('prescriptionImageInput');
            if (input) input.value = '';
        },

        async submitRequest() {
            const form   = document.getElementById('medicineRequestForm');
            const btn    = document.getElementById('medicineSubmitBtn');
            const errDiv = document.getElementById('medicineRequestError');

            btn.disabled = true;
            btn.textContent = 'Submitting…';
            errDiv.classList.add('hidden');

            const formData = new FormData(form);

            try {
                const response = await fetch('{{ route("prescriptions.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    window.location.reload();
                } else {
                    const messages = data.errors
                        ? Object.values(data.errors).flat().join(' ')
                        : (data.message || 'Submission failed. Please try again.');
                    errDiv.textContent = messages;
                    errDiv.classList.remove('hidden');
                }
            } catch (err) {
                errDiv.textContent = 'An unexpected error occurred. Please try again.';
                errDiv.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Submit Request';
            }
        }
    }
}
</script>