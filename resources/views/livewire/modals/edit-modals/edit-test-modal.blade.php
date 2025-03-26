<div id="edit_test_modal">
    <div class="p-4 bg-blue-600 rounded-t-lg">
        <h2 class="text-lg font-semibold text-white">Edit Test</h2>
    </div>
    <div class="p-6">
        <form wire:submit.prevent="updateTest" class="w-full p-6 bg-white rounded-lg shadow-md">
            <!-- First Row -->
            <div class="grid grid-cols-1 gap-4 mb-2 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Test Name</label>
                    <input type="text" wire:model="test_name"
                        class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('test_name')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Start</label>
                    <input type="datetime-local" wire:model.live="start"
                        class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('start')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">End</label>
                    <input type="datetime-local" wire:model.live="end"
                        class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('end')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Second Row -->
            <div class="grid grid-cols-1 gap-4 mb-2 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Duration (min)</label>
                    <input type="number" wire:model="duration"
                        class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('duration')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Exam Group</label>
                    <div wire:ignore>
                        <select wire:model="group_id" id="group_id"
                            class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Group</option>
                            @foreach ($this->groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('group_id')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Basic Points</label>
                    <input type="number" wire:model="score_right"
                        class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('score_right')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Points for wrong</label>
                    <input type="number" wire:model="score_wrong"
                        class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('score_wrong')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Third Row -->
            <div class="grid grid-cols-1 gap-4 mb-2 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Points to pass</label>
                    <input type="number" wire:model="score_threshold"
                        class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('score_threshold')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Exam password</label>
                    <input type="password" wire:model="exam_password"
                        class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('exam_password')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Points for no Answer</label>
                    <input type="text" wire:model="score_unanswered"
                        class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('score_unanswered')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Order Mode</label>
                    <div wire:ignore>
                        <select wire:model="questions_order_mode" id="questions_order_mode"
                            class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Module</option>
                            @foreach ($this->qordmode as $ok)
                                <option value="{{ $ok['id'] }}">{{ $ok['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('questions_order_mode')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Description Section -->
            <div class="mb-2">
                <label class="block mb-1 text-sm font-medium text-gray-700">Description</label>
                <textarea wire:model="description"
                    class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    rows="2"></textarea>
                @error('description')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <!-- Checkbox Section -->
            <div class="grid grid-cols-1 gap-4 mb-2 sm:grid-cols-2 lg:grid-cols-3">
                <div class="flex items-center">
                    <input type="checkbox" wire:model="random_questions_select"
                        class="w-5 h-5 text-blue-600 form-checkbox">
                    <label class="ml-2 text-sm text-gray-700">Random Questions</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" wire:model="random_answers_select"
                        class="w-5 h-5 text-blue-600 form-checkbox">
                    <label class="ml-2 text-sm text-gray-700">Random Answers</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" wire:model="mcma_partial_score" class="w-5 h-5 text-blue-600 form-checkbox">
                    <label class="ml-2 text-sm text-gray-700">Partial Score for MCMA</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" wire:model="noanswer_enabled" class="w-5 h-5 text-blue-600 form-checkbox">
                    <label class="ml-2 text-sm text-gray-700">No Answer Option</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" wire:model="comment_enabled" class="w-5 h-5 text-blue-600 form-checkbox">
                    <label class="ml-2 text-sm text-gray-700">Exam Comment</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" wire:model="repeatable" class="w-5 h-5 text-blue-600 form-checkbox">
                    <label class="ml-2 text-sm text-gray-700">Repeatable</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" wire:model="result_to_user" class="w-5 h-5 text-blue-600 form-checkbox">
                    <label class="ml-2 text-sm text-gray-700">Results to users</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" wire:model="logout_on_timeout"
                        class="w-5 h-5 text-blue-600 form-checkbox">
                    <label class="ml-2 text-sm text-gray-700">Logout on time out</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" wire:model="menu_enabled" class="w-5 h-5 text-blue-600 form-checkbox">
                    <label class="ml-2 text-sm text-gray-700">Questions menu</label>
                </div>
            </div>

            <!-- Submit and Cancel Buttons -->
            <div class="flex justify-end space-x-4">
                <button type="button" wire:click="closeModal"
                    class="px-4 py-2 text-gray-700 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit"
                    class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">Submit</button>
            </div>
        </form>
    </div>
</div>

@script()
<script>
    $(document).ready(function() {
        initializeSelect2();
    });
    document.addEventListener('livewire:initialize', function() {
        initializeSelect2();
    });

    function initializeSelect2() {
        $('#group_id').select2({
            dropdownParent: $('#edit_test_modal'), // Ensure dropdown is attached to the modal
            width: '100%',
            placeholder: "Select exam group",
            allowClear: true
        }).on('change', function(e) {
            var data = $(this).val();
            console.log(data);
            @this.set('group_id', data);
        });
        $('#questions_order_mode').select2({
            dropdownParent: $('#edit_test_modal'), // Ensure dropdown is attached to the modal
            width: '100%',
            placeholder: "Select order",
            allowClear: true
        }).on('change', function(e) {
            var data = $(this).val();
            console.log(data);
            @this.set('questions_order_mode', data);
        });
    }
</script>
@endscript()
