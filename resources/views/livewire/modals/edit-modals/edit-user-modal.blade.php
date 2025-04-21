<div class="relative min-w-full" id="edit_user_modal">
    <div class="p-4 bg-blue-600 rounded-t-lg">
        <h2 class="text-lg font-semibold text-white">Edit User</h2>
    </div>
    <div class="p-6">
        <form wire:submit.prevent="updateUser">
            <div class="flex flex-wrap mb-6 -mx-3">
                <div class="flex flex-wrap w-full mb-6 -mx-3 ">
                    <div class="w-full px-3 md:w-1/3 ">
                        <label class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" wire:model="first_name"
                            class="block w-full p-2 mt-1 border border-gray-300 rounded-md">
                        @error('first_name')
                            <p class="text-xs italic text-red-500">{{ $message }}</span>
                            @enderror
                    </div>
                    <div class="w-full px-3 md:w-1/3">
                        <label class="block text-sm font-medium text-gray-700">Middle Name</label>
                        <input type="text" wire:model="middle_name"
                            class="block w-full p-2 mt-1 border border-gray-300 rounded-md">
                        @error('middle_name')
                            <p class="text-xs italic text-red-500">{{ $message }}</span>
                            @enderror
                    </div>
                    <div class="w-full px-3 md:w-1/3">
                        <label class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" wire:model="last_name"
                            class="block w-full p-2 mt-1 border border-gray-300 rounded-md">
                        @error('last_name')
                            <p class="text-xs italic text-red-500">{{ $message }}</span>
                            @enderror
                    </div>
                </div>
                <div class="flex flex-wrap w-full mb-6 -mx-3">
                    <div class="w-full px-3 md:w-1/3">
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="text" wire:model="email"
                            class="block w-full p-2 mt-1 border border-gray-300 rounded-md">
                        @error('email')
                            <p class="text-xs italic text-red-500">{{ $message }}</span>
                            @enderror
                    </div>
                    <div class="w-full px-3 mb-3 md:w-1/3">
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                            for="grid-module">
                            Role
                        </label>
                        <div class="relative" wire:ignore>
                            <select wire:model="access_level" id="edit_access_level"
                                class="block w-full px-4 py-3 mb-3 leading-tight text-gray-700 bg-gray-200 border rounded appearance-none focus:outline-none focus:bg-white">
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role['id'] }}">{{ $role['description'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('access_level')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-full px-3 mb-6 md:w-full md:mb-0 ">
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                            for="grid-module">
                            Group
                        </label>
                        <div class="relative" wire:ignore>
                            <select wire:model="group_id" id="edit_group_id"
                                class="block w-full px-4 py-3 mb-3 leading-tight text-gray-700 bg-gray-200 border rounded appearance-none focus:outline-none focus:bg-white">
                                <option value="">Select Group</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('group_id')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
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
    $(document).ready(function() {
        initializeSelect2();
    });
    document.addEventListener('livewire:initialize', function() {
        initializeSelect2();
    });

    function initializeSelect2() {
        $('#edit_group_id').select2({
            dropdownParent: $('#edit_user_modal'), // Ensure dropdown is attached to the modal
            width: '100%',
            placeholder: "Select group",
            allowClear: true
        }).on('change', function(e) {
            var data = $(this).val();
            console.log(data);
            @this.set('group_id', data);
        });
        $('#edit_access_level').select2({
            dropdownParent: $('#edit_user_modal'), // Ensure dropdown is attached to the modal
            width: '100%',
            placeholder: "Select Role",
            allowClear: true
        }).on('change', function(e) {
            var data = $(this).val();
            console.log(data);
            @this.set('access_level', data);
        });
    }
</script>
@endscript()
