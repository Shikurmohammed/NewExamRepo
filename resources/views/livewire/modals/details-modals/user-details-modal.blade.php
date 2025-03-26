<div class="z-10">
    <div class="p-4 bg-blue-600 rounded-t-lg">
        <h2 class="text-lg font-semibold text-white">User Details</h2>
    </div>
    <div class="p-6">
        <div class="modal-overlay" style="background: rgba(0, 0, 0, 0.5);"></div>
        <div class="space-y-4">
            <div>
                <label class="text-sm font-semibold text-gray-700">First Name:</label>
                <span class="mt-1 text-sm text-gray-900">{{ $user->first_name }}</span>
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-700">Middle Name:</label>
                <span class="mt-1 text-sm text-gray-900">{{ $user->middle_name }}</span>
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-700">Last Name:</label>
                <span class="mt-1 text-sm text-gray-900">{{ $user->last_name }}</span>
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-700 ">Email:</label>
                <span class="mt-1 text-sm text-gray-900">{{ $user->email }}</span>
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-700 ">Access Level:</label>
                <span class="mt-1 text-sm text-gray-900">{{ $user->access_level }}</span>
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-700 ">Status:</label>
                <span
                    class="mt-1 text-sm {{ $user->status == 1 ? 'text-green-400' : ' text-red-400' }}">{{ $user->status == 1 ? 'Active' : 'InActive' }}</span>
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-700 ">CreatedAt:</label>
                <span class="mt-1 text-sm text-gray-900">
                    {{ \Carbon\Carbon::parse($user->created_at)->format('Y-M-d H:i:s a') }}
                </span>
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-700 ">LastUpdateAt:</label>
                <span class="mt-1 text-sm text-gray-900">
                    {{ \Carbon\Carbon::parse($user->updated_at)->format('Y-M-d H:i:s a') }}
                </span>
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
