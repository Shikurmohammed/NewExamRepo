<input type="radio" name="answpos" id="answpos_{{ $answer['id'] ?? 0 }}" value="{{ $answer['id'] ?? 0 }}"
    @if ($answer['selected'] ?? 0) checked @endif
    onchange="handleAnswerSelection({{ $answer['id'] ?? 0 }},
     this.value)" />

<label for="answpos_{{ $answer['id'] ?? 0 }}"> {!! $answer['description'] ?? '' !!} </label>
