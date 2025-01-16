<div x-data="{ isOpen: @entangle('isModalOpen') }">
    <!-- Button to open modal -->
    <button @click="isOpen = true" class="mt-2 bg-slate-400 text-white px-2 py-1 rounded"><i class="fa fa-plus-circle"></i>
        New</button>
    <button wire:click="$refresh" class="mt-2 bg-slate-400 text-white px-2 py-1 rounded"><i class="fa fa-upload"></i>
        Import</button>

    <!-- Modal -->
    <div x-show="isOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        @click.away="isOpen = false" style="display: none;">
        <div class="modal-overlay" style="background: rgba(0, 0, 0, 0.5);"></div>
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full " x-show.transition.opacity="isOpen">
            <h2 class="text-xl font-semibold mb-4">Create Answer</h2>
            <form wire:submit.prevent="create">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Answer</label>
                    <input type="text" wire:model="module_name"
                        class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    @error('module_name')
                        <span class="px-2 w-full text-red-700">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Owner</label>
                    <?php $currentUser = Auth::user()->name ? Auth::user()->name : ''; ?>
                    <input type="text" disabled wire:model="owner_name" value="{{ $currentUser }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Enabled
                        <input type="hidden" wire:model="enabled" value="0">
                        <input type="checkbox" wire:model="enabled" value="1" checked></label>
                </div>

                <div class="flex justify-end">
                    <button type="button" id="closeModal" wire:click="closeModal" @click="isOpen = false"
                    class="bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2">
                    Cancel</button>
                <button type="submit" wire:loading.attr="disabled" wire:loading.class="bg-gray-500"
                    class="bg-green-500 text-white px-4 py-2 rounded">Submit <i wire:loading="create"
                        class="fa fa-spinner fa-spin"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
