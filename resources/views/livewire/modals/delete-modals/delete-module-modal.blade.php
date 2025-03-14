<div>
    <!-- Modal Header -->
    <div class="p-4 bg-blue-600 rounded-t-lg">
        <h2 class="text-lg font-semibold text-white">Confirm Deletion</h2>
    </div>

    <!-- Modal Body -->
    <div class="p-6">
        <p class="text-gray-700">{{ $message }}</p>

        <!-- Buttons -->
        <div class="flex justify-end mt-6 space-x-4">
            <button type="button" wire:click="closeModal"
                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Cancel
            </button>
            <button type="button" wire:click="delete"
                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Delete
            </button>
        </div>
    </div>
</div>
