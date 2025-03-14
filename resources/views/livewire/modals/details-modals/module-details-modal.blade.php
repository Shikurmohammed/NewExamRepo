<div class="z-10">
    <div class="p-4 bg-blue-600 rounded-t-lg">
        <h2 class="text-lg font-semibold text-white">Module Details</h2>
    </div>
    <div class="p-6">
        <div class="modal-overlay" style="background: rgba(0, 0, 0, 0.5);"></div>
        <div class="space-y-4">
            <div>
                <label class="text-sm font-semibold text-gray-700">Name</label>
                <p class="mt-1 text-sm text-gray-900">{{ $module->name }}</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700">Description</label>
                <p class="mt-1 text-sm text-gray-900">{{ $module->description }}</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700">Status</label>
                <p class="mt-1 text-sm text-gray-900">
                    {{ $module->enabled ? 'Enabled' : 'Inactive' }}
                </p>
            </div>
        </div>
        <div class="flex justify-end mt-6">
            <button type="button" wire:click="closeModal"
                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Close
            </button>
        </div>
    </div>
</div>
