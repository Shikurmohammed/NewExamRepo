<div x-data="{ isOpen: @entangle('isModalOpen') }" id="user_modal" class="relative z-100">
    <div class="flex items-center justify-between">
        <form wire:submit.prevent="importModules" class="flex items-center justify-between ">
            <div>
                <input type="file" wire:model="file" required class="max-w:10" />
                <button type="submit" wire:loading.attr="disabled" wire:loading.class="bg-gray-500"
                    class="px-2 py-1 mt-2 mr-2 text-white rounded bg-slate-400">
                    <i class="fa fa-upload"></i>
                    Upload<i wire:loading="importModules" class="fa fa-spinner fa-spin"></i></button>
            </div>
            @error('file')
                <br>
                <span class="block text-red-600">{{ $message }}</span>
            @enderror
        </form>
        <button @click="isOpen = true" wire:loading.attr="disabled" wire:loading.class="bg-gray-500"
            class="px-2 py-1 mt-2 text-white rounded bg-slate-400"> <i class="fa fa-plus-circle"></i> New</button>
    </div>
    <div x-show="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        @click.away="isOpen = false" style="display: none;">
        <div class="modal-overlay" style="background: rgba(0, 0, 0, 0.5);"></div>
        <div class="p-6 bg-white rounded-lg shadow-lg " x-show.transition.opacity="isOpen">
            <h2 class="mb-4 text-xl font-semibold">Create User</h2>
            <form wire:submit.prevent="createUser">
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
                    <div class="w-full px-3 md:w-1/3">
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                            for="grid-module">
                            Group
                        </label>
                        <div class="relative" wire:ignore>
                            <select wire:model="group_id" id="group_id"
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
                    <div class="w-full px-3 mb-3 md:w-1/3">
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                            for="grid-module">
                            Role
                        </label>
                        <div class="relative" wire:ignore>
                            <select wire:model="access_level" id="access_level"
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
                </div>
                <div class="flex justify-end">
                    <button type="button" id="closeModal" wire:click="closeModal" @click="isOpen = false"
                        class="px-4 py-2 mr-2 text-gray-700 bg-gray-300 rounded">
                        Cancel</button>
                    <button type="submit" wire:loading.attr="disabled" wire:loading.class="bg-gray-500"
                        class="px-4 py-2 text-white bg-green-500 rounded">Submit
                        <i wire:loading="create" class="fa fa-spinner fa-spin"></i></button>
                </div>
            </form>
        </div>
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
            dropdownParent: $('#user_modal'), // Ensure dropdown is attached to the modal
            width: '100%',
            placeholder: "Select module",
            allowClear: true
        }).on('change', function(e) {
            var data = $(this).val();
            console.log(data);
            @this.set('group_id', data);
        });
        $('#access_level').select2({
            dropdownParent: $('#user_modal'), // Ensure dropdown is attached to the modal
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
