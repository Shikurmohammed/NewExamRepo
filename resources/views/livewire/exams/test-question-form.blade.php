<h2 class="mb-4 text-xl font-semibold">
    {{ $questionData['question']['id'] ?? 'Question' }}.
    {{ $questionData['question']['description'] ?? 'No question text' }}
</h2>

@foreach ($questionData['answers'] ?? [] as $answerId => $answerText)
    <div class="flex items-center mb-2 space-x-2">
        <input type="{{ $questionData['question']['type'] === '1' ? 'radio' : 'checkbox' }}"
            wire:model="{{ $questionData['question']['type'] === '1' ? 'answerSelected' : 'answerPositions.' . $answerId }}"
            value="1" id="answer_{{ $answerId }}" name="answer" class="w-5 h-5 text-blue-600 form-checkbox">
        <label for="answer_{{ $answerId }}" class="text-gray-700">
            {{-- {{ $answerText }} --}}a
        </label>
    </div>
@endforeach

{{-- Open Answer ==4 --}}
