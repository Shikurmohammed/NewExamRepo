<div>
    <div class="p-4 bg-blue-600 rounded-t-lg">
        <h2 class="text-lg font-semibold text-white">Question Details</h2>
    </div>
    <div class="p-6">
        <div class="modal-overlay" style="background: rgba(0, 0, 0, 0.5);"></div>
        <div class="space-y-4">
            <div>
                <label class="text-sm font-semibold text-gray-700">Topic:</label>
                <span class="mt-1 text-sm text-gray-900">{{ $question->topic->name }}</span>
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-700 ">Description</label>
                <span class="mt-1 text-sm text-gray-900">{{ $question->description }}</span>
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-700">Explanation:</label>
                <span class="mt-1 text-sm text-gray-900">{{ $question->explanation }}</span>
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-700 ">Status</label>
                <span class="mt-1 text-sm text-gray-900">
                    {{ $question->enabled ? 'Enabled' : 'Inactive' }}
                </span>
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-700">Question Type:</label>
                <span class="mt-1 text-sm text-gray-900">{{ $question->type == 1 ? 'Single answer' : '' }}</span>
                <span class="mt-1 text-sm text-gray-900">{{ $question->type == 2 ? 'Multiple answers' : '' }}</span>
                <span class="mt-1 text-sm text-gray-900">{{ $question->type == 3 ? 'Free answer' : '' }}</span>
                <span class="mt-1 text-sm text-gray-900">{{ $question->type == 4 ? 'Ordering answers' : '' }}</span>
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-700">Diifficulty:</label>
                <span class="mt-1 text-sm text-gray-900">{{ $question->difficulty == 1 ? 'Easy' : '' }}</span>
                <span class="mt-1 text-sm text-gray-900">{{ $question->difficulty == 2 ? 'Medium' : '' }}</span>
                <span class="mt-1 text-sm text-gray-900">{{ $question->difficulty == 3 ? 'Hard' : '' }}</span>

            </div>
            <div>
                <label class="text-sm font-semibold text-gray-700">Created By:</label>
                <span class="mt-1 text-sm text-gray-900">{{ $question->created_by }}</span>
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-700">Created At:</label>
                <span
                    class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($question->created_at)->format('Y-M-d H:i:s') }}</span>
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
