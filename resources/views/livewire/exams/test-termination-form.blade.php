<div>
    <button wire:click="confirmTestTermination"
        class="px-4 py-2 font-semibold text-white bg-red-600 rounded hover:bg-red-700">
        Terminate
    </button>
    @if ($showTerminationConfirmation)
        <div class="p-6 text-gray-800 bg-yellow-100 border border-yellow-400 rounded-lg shadow-md">
            <h2 class="mb-2 text-lg font-bold">Confirm Test Termination</h2>

            @if ($omittedQuestionsCount > 0)
                <p class="font-semibold text-red-600">
                    ⚠️ {{ $omittedQuestionsCount }} unanswered question(s).
                </p>
            @endif

            <p class="mt-2">Are you sure you want to terminate the test? This action cannot be undone.</p>

            <div class="flex mt-4 space-x-3">
                <button wire:click="terminateTest"
                    class="px-4 py-2 font-semibold text-white bg-red-600 rounded hover:bg-red-700">
                    Yes, Terminate Test
                </button>

                <button wire:click="$set('showTerminationConfirmation', false)"
                    class="px-4 py-2 text-gray-800 bg-gray-300 rounded hover:bg-gray-400">
                    Cancel
                </button>
            </div>
        </div>
    @endif

</div>
