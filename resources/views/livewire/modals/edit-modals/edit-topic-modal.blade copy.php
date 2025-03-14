<!-- <div x-data="{ isOpen: false }" id="edit-topic-modal">
    <div class="p-4 bg-blue-600 rounded-t-lg">
        <h2 class="text-lg font-semibold text-white">Edit Module</h2>
    </div>
    <div class="p-6">
        <form wire:submit.prevent="save">
            <div class="mb-6" wire:ignore>
                <label class="flex items-center"> Module
                </label>
                <select wire:model="module_id" id="edit_module_id"
                    class="block w-full px-4 py-3 mb-3 leading-tight text-gray-700 bg-gray-200 border rounded appearance-none focus:outline-none focus:bg-white">
                    <option value="">Select Module</option>
                    @foreach ($this->modules as $module)
                        <option value="{{ $module->id }}">
                            {{ $module->name }}</option>
                    @endforeach
                </select>
                @error('module_id')
                    <p class="text-xs italic text-red-500">{{ $message }}</p>
                @enderror

            </div>
            <div class="mb-6">
                <label for="topic_name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" wire:model="topic_name" id="topic_name"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('topic_name')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" wire:model="enabled"
                        class="text-blue-600 border-gray-300 rounded shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Is Enabled</span>
                </label>
                @error('enabled')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-6">
                <label for="created_by" class="block text-sm font-medium text-gray-700">Created By</label>
                <input type="text" wire:model="created_by" id="created_by" disabled
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('created_by')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" wire:click="closeModal"
                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </button>
                <button type="submit"
                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Save Changes
                </button>
            </div>

        </form>

    </div>
</div>
@script()
<script>
    // Initialize Select2 on document ready
    $(document).ready(function() {
        initializeSelect2();
    });
    document.addEventListener('livewire:update', function() {
        initializeSelect2();
    });

    function initializeSelect2() {
        $('#edit_module_id').select2({
            dropdownParent: $('#edit-topic-modal'),
            width: '100%',
            placeholder: "Select an option",
            allowClear: true
        }).on('change', function(e) {
            var data = $(this).val();
            console.log(data);
            @this.set('module_id', data);
        });
    }
</script>
@endscript() -->