<div x-data="{ isOpen: @entangle('isModalOpen') }" id="topic_modal">

    <div class="flex items-center justify-between">
        <form wire:submit.prevent="importTopics" class="flex items-center justify-between ">
            <input type="file" wire:model="file" required class="max-w:10" />
            <button wire:loading.attr="disabled" wire:loading.class="bg-gray-500"
                class="px-2 py-1 mt-2 mr-2 text-white rounded bg-slate-400">
                <i class="fa fa-upload"></i>
                Upload<i wire:loading="importTopics" class="fa fa-spinner fa-spin"></i></button>
            @error('file')
                <span class="text-red-600">{{ $message }}</span>
            @enderror
        </form>
        <button @click="isOpen = true" class="px-2 py-1 mt-2 text-white rounded bg-slate-400">
            <i class="fa fa-plus-circle"></i> New
        </button>
    </div>
    <div x-show="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        @click.away="if (!event.target.closest('.select2-container')) { isOpen = false }" style="display: none;">
        <div class="modal-overlay" @click="isOpen = false" style="background: rgba(0, 0, 0, 0.5);"></div>
        <div class="w-full max-w-lg p-6 bg-white rounded-lg shadow-lg" x-show.transition.opacity="isOpen">
            <h2 class="mb-4 text-xl font-semibold">Create Topic</h2>
            <form wire:submit.prevent="create">
                <div class="mb-4">
                    <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="grid-module">
                        Module
                    </label>
                    <div class="relative" wire:ignore>
                        <select wire:model="module_id" id="module_id"
                            class="block w-full px-4 py-3 mb-3 leading-tight text-gray-700 bg-gray-200 border rounded appearance-none focus:outline-none focus:bg-white">
                            <option value="">Select Module</option>
                            @foreach ($this->modules as $module)
                                <option value="{{ $module->id }}">
                                    {{ $module->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('module_id')
                        <p class="text-xs italic text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Topic</label>
                    <input type="text" wire:model="topic_name"
                        class="block w-full p-2 mt-1 border border-gray-300 rounded-md">
                    @error('topic_name')
                        <span class="w-full px-2 text-red-700">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <input type="text" wire:model="description"
                        class="block w-full p-2 mt-1 border border-gray-300 rounded-md">
                    @error('description')
                        <span class="w-full px-2 text-red-700">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Owner</label>
                    <?php $currentUser = Auth::user()->name ? Auth::user()->name : ''; ?>
                    <input type="text" disabled wire:model="owner_name" value="{{ $currentUser }}"
                        class="block w-full p-2 mt-1 border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Enabled
                        <input type="hidden" wire:model="enabled" value="0">
                        <input type="checkbox" wire:model="enabled" value="1" checked></label>
                </div>

                <div class="flex justify-end">
                    <button type="button" id="closeModal" wire:click="closeModal" @click="isOpen = false"
                        class="px-4 py-2 mr-2 text-gray-700 bg-gray-300 rounded">
                        Cancel</button>
                    <button type="submit" wire:loading.attr="disabled" wire:loading.class="bg-gray-500"
                        class="px-4 py-2 text-white bg-green-500 rounded">Submit <i wire:loading="create"
                            class="fa fa-spinner fa-spin"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

@script()
<script>
    $(document).ready(function() {
        initializeSelect2(); //This will initialize select2 when document is ready(on first page load),
        //  so that element existance error will not raised.
    });
    document.addEventListener('livewire:initialize', function() {
        initializeSelect2
            (); //This will initialize select2 everytime livewire is initialized or page is updated.
    });

    function initializeSelect2() {
        $('#module_id').select2({
            dropdownParent: $('#topic_modal'), // Ensure dropdown is attached to the modal
            width: '100%',
            placeholder: "Select module",
            allowClear: true
        }).on('change', function(e) {
            var data = $(this).val();
            console.log(data);
            @this.set('module_id', data);
        });
    }
</script>
@endscript()
