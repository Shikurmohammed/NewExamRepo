<div id="user_group_modal" class="relative">
    <div class="p-4 bg-blue-600 rounded-t-lg">
        <h2 class="text-lg font-semibold text-white">User ~ Group Mapping Form</h2>
    </div>
    @error('selectedUserIds')
        <p class="text-xs italic text-center text-red-500">{{ $message }}</p>
    @enderror
    <div class="p-6">
        <form>
            <div class="mb-6">
                <input type="hidden" wire:model='selectedUserIds'>
                <label class="flex items-center"> Group
                </label>
                <div class="relative" wire:ignore>
                    <select wire:model="group_id" id="group_ids" multiple
                        class="block w-full px-4 py-3 mb-3 leading-tight text-gray-700 bg-gray-200 border rounded appearance-none focus:outline-none focus:bg-white">
                        <option value="">Select Module</option>
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}">
                                {{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('group_id')
                    <p class="text-xs italic text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" wire:click="closeModal"
                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </button>
                <button type="button" wire:click="addUsersToGroup"
                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Add
                </button>
                <button type="button"
                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Move
                </button>
                <button type="button" wire:click="removeUsersFromGroup"
                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Remove
                </button>

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
        $('#group_ids').select2({
            dropdownParent: $('#user_group_modal'), // Ensure dropdown is attached to the modal
            width: '100%',
            placeholder: "Select group",
            allowClear: true
        }).on('change', function(e) {
            var data = $(this).val();
            console.log(data);
            @this.set('group_id', data);
        });
    }
</script>
@endscript()
