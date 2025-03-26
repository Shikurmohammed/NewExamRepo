<div class="container">
    <div class="test-header">
        <h2>{{ $testName }}</h2>
        <span class="info-link">
            {{-- href="{{ route('test.info', $testId) }}" --}}
            <a onclick="window.open(this.href,'testInfoWindow','height=600,width=800');return false;"
                title="@lang('m_new_window_link')">
                @lang('w_info')
            </a>
        </span>
    </div>

    @if ($showTerminationConfirmation)
        <div class="confirm-box">
            {{ $terminationForm }}
        </div>
    @else
        <form wire:submit.prevent="updateQuestion" id="testExecutionForm">
            <div>
                {{ $questionForm }}

                <input type="hidden" wire:model="reactionTime" id="reaction_time">
                <input type="hidden" id="display_time" value="{{ now()->timestamp }}">

                <div class="test-comment">
                    <textarea wire:model="testComment" placeholder="@lang('w_test_comment')"></textarea>
                </div>

                <button type="button" wire:click="confirmTermination" class="btn btn-danger">
                    @lang('w_terminate_exam')
                </button>
            </div>
        </form>
    @endif
</div>

@push('scripts')
    <script>
        // Timer functionality
        function updateTimer() {
            @this.set('remainingTime', @this.remainingTime - 1);
            if (@this.remainingTime <= 0) {
                clearInterval(timer);
                @this.terminateTest();
            }
        }

        let timer = setInterval(updateTimer, 1000);

        // Set reaction time when form is submitted
        document.getElementById('testExecutionForm').addEventListener('submit', function() {
            let submittime = new Date();
            let displayTime = document.getElementById('display_time').value;
            @this.set('reactionTime', submittime.getTime() - displayTime);
        });
    </script>
@endpush
