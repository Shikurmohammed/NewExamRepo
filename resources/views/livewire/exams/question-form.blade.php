@if ($question)
    <div @if ($showFullscreen) class="fullscreen-mode" @endif>
        <input type="hidden" name="testid" value="{{ $testId }}" />
        <input type="hidden" name="testlogid" value="{{ $testLogId }}" />
        <input type="hidden" name="testuser_id" value="{{ $question->testlog_testuser_id }}" />
        <input type="hidden" name="examtime" value="{{ $examEndTime->timestamp }}" />

        @if ($testData->test_logout_on_timeout)
            <input type="hidden" name="timeout_logout" value="1" />
        @endif

        <div class="tcecontentbox">
            <a name="questionsection" id="questionsection"></a>

            @if ($question->question->question_type == 3)
                <label for="answertext">
                    {!! $question->question->question_description !!}
                </label>
            @else
                {!! $question->question->question_description !!}
            @endif

            <div class="row">
                <hr />
            </div>

            <div class="rowl">
                @if ($question->question->question_type == 3)
                    @if ($enableVirtualKeyboard)
                        <script src="{{ asset('js/vk/vk_easy.js?vk_skin=default') }}"></script>
                    @endif

                    <textarea cols="{{ $answerTextareaCols }}" rows="{{ $answerTextareaRows }}" wire:model.defer="answerText"
                        @if ($enableVirtualKeyboard) keyboardInput @endif id="answertext">
                    </textarea>
                @else
                    <ol @if ($inlineAnswers) class="answer_inline" @else class="answer" @endif>
                        @foreach ($answers as $answer)
                            <li>
                                @switch($question->question->question_type)
                                    @case(1)
                                        {{-- MCSA --}}
                                        <input type="radio" wire:model.defer="selectedAnswers.{{ $answer['id'] }}"
                                            id="answpos_{{ $answer['id'] }}" value="1"
                                            @if ($autoNextEnabled) wire:change="saveAnswer" @endif />
                                        <label for="answpos_{{ $answer['id'] }}">
                                            {!! $answer['description'] !!}
                                        </label>
                                    @break

                                    @case(2)
                                        {{-- MCMA --}}
                                        @if ($testData->test_mcma_radio)
                                            {{-- Radio button implementation --}}
                                        @else
                                            <input type="checkbox" wire:model.defer="selectedAnswers.{{ $answer['id'] }}"
                                                id="answpos_{{ $answer['id'] }}" value="1" />
                                            <label for="answpos_{{ $answer['id'] }}">
                                                {!! $answer['description'] !!}
                                            </label>
                                        @endif
                                    @break

                                    @case(4)
                                        {{-- ORDER --}}
                                        <select wire:model.defer="selectedAnswers.{{ $answer['id'] }}"
                                            id="answpos_{{ $answer['id'] }}" size="0">
                                            @if ($noAnswerEnabled)
                                                <option value="0">&nbsp;</option>
                                            @endif
                                            @for ($i = 1; $i <= count($answers); $i++)
                                                <option value="{{ $i }}"
                                                    @if ($answer['position'] == $i) selected @endif>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                        <label for="answpos_{{ $answer['id'] }}">
                                            {!! $answer['description'] !!}
                                        </label>
                                    @break
                                @endswitch
                            </li>
                        @endforeach

                        @if ($question->question->question_type == 1 && $noAnswerEnabled)
                            <li>
                                <input type="radio" wire:model.defer="selectedAnswers.default" id="answpos_0"
                                    value="0" />
                                <label for="answpos_0">
                                    {{ Lang::get('m_unanswered') }}
                                </label>
                            </li>
                        @endif
                    </ol>
                @endif
            </div>
        </div>

        @if ($questionTimer > 0)
            <script>
                setTimeout(() => {
                    @this.set('autoNext', true);
                    @this.call('saveAnswer');
                }, {{ $questionTimer * 1000 }});
            </script>
        @endif

        <script>
            // Keyboard handling
            document.addEventListener('keypress', function(e) {
                @this.handleKeyPress(e.keyCode);
            });

            // Initialize display time
            document.addEventListener('DOMContentLoaded', function() {
                @this.set('displayTime', new Date().getTime());
            });
        </script>
    </div>
@endif
