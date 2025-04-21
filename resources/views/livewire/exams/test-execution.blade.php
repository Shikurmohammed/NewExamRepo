<div class="space-y-6">
    <div class="flex justify-center w-full">
        <div wire:poll.60s="updateReactionTime"></div>
        <div class="w-full p-6 m-4 mb-0 bg-white rounded shadow">
            {{--  max-w-4xl --}}
            @if ($remainingTime)
                <div class="text-lg font-semibold text-blue-600 text-end ">
                    Time Remaining: <span id="time-remaining">{{ gmdate('H:i:s', $remainingTime) }}</span>
                </div>
            @endif
            {{-- ✅ Hidden Inputs for Reaction Tracking --}}
            <input type="hidden" id="reaction_time" wire:model.defer="reactionTime">
            <input type="hidden" id="display_time" wire:ignore>
            @if (!empty($questionData['questionMenu']))
                <div class="p-4 mb-6 bg-gray-100 rounded shadow">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">Question Navigator</h3>
                    <div class="grid grid-cols-6 gap-2">
                        @foreach ($questionData['questionMenu']['questions'] as $item)
                            <button wire:click="jumpToQuestion({{ $item['id'] }})"
                                class="p-2 text-sm font-semibold text-white rounded
                        {{ $item['id'] == $questionData['testLogId'] ? 'bg-blue-600' : ($item['answered'] ? 'bg-green-600' : ($item['displayed'] ? 'bg-yellow-500' : 'bg-gray-400')) }}
                        hover:opacity-90"
                                title="{{ $item['description'] }}">
                                Q{{ $loop->iteration }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            @include('livewire.exams.test-question-form', ['questionData' => $questionData])
            <div class="flex mt-6 space-x-4">
                @if ($previousLogId)
                    <button wire:click="prevQuestion({{ $previousLogId }})"
                        class="px-4 py-2 text-black bg-gray-300 rounded hover:bg-gray-400">
                        Previous
                    </button>
                @endif
                @if ($nextLogId)
                    <button wire:click="nextQuestion({{ $nextLogId }})"
                        class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                        Next
                    </button>
                @endif
                <button wire:click="confirmTestTermination"
                    class="px-4 py-2 ml-auto text-white bg-red-600 rounded hover:bg-red-700">
                    Terminate
                </button>
            </div>
        </div>
    </div>
    @if ($questionData['question']['type'] === 1)
        <di class="w-full m-4 mt-0 sm:w-2/3 md:w-1/2 lg:w-1/3">
            <textarea wire:model="answerText" class="w-full max-w-4xl p-1 border border-gray-300 rounded" rows="2"
                placeholder="Plac your comment here...">
                </textarea>
</div>
@endif
@includeWhen($showTerminationConfirmation, 'livewire.exams.partials.termination-confirmation')
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let remainingSeconds = {{ $remainingTime }};
        const timeDisplay = document.getElementById('time-remaining');

        if (timeDisplay) {
            const interval = setInterval(() => {
                if (remainingSeconds <= 0) {
                    clearInterval(interval);
                    alert("Oop, time expired!")
                    window.location.href = "{{ route('myexam_list') }}"; // Or your actual route
                } else {
                    remainingSeconds--;
                    const hrs = String(Math.floor(remainingSeconds / 3600)).padStart(2, '0');
                    const mins = String(Math.floor((remainingSeconds % 3600) / 60)).padStart(2, '0');
                    const secs = String(remainingSeconds % 60).padStart(2, '0');
                    timeDisplay.textContent = `${hrs}:${mins}:${secs}`;
                }
            }, 1000);
        }
    });

    //Reaction time
    // Track reaction time
    let reactionStartTime = Date.now();
    window.addEventListener('beforeunload', function() {
        const elapsedTime = Math.floor((Date.now() - reactionStartTime) / 1000);
        @this.set('reactionTime', elapsedTime);
    });

    setInterval(() => {
        const elapsedTime = Math.floor((Date.now() - reactionStartTime) / 1000);
        @this.set('reactionTime', elapsedTime);
    }, 30000);
</script>
