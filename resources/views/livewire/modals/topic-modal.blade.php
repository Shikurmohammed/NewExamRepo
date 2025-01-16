<div x-data="{ isOpen: @entangle('isModalOpen') }">
    <!-- Button to open modal -->
    <button @click="isOpen = true" class="mt-2 bg-slate-400 text-white px-2 py-1 rounded">
        <i class="fa fa-plus-circle"></i> New</button>

    <!-- Modal -->
    <div x-show="isOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        @click.away="isOpen = false" style="display: none;">
        <div class="modal-overlay" @click="open = false" style="background: rgba(0, 0, 0, 0.5);"></div>
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full " x-show.transition.opacity="isOpen">
            <h2 class="text-xl font-semibold mb-4">Create Module</h2>

            @if (session()->has('success'))
                <div class="text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="text-red-700">
                    {{ session('error') }}
                </div>
            @endif
            <form wire:submit.prevent="create">
                <div class="mb-4">
                    <div>
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="grid-module">
                            Module
                        </label>
                        <select wire:model="module_id" id="module_id"
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white">
                            <option value="">Select Module</option>
                            @foreach ($this->modules as $module)
                                <option value="{{ $module->id }}">
                                    {{ $module->name }}</option>
                            @endforeach
                        </select>
                        @error('module_id')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Topic</label>
                    <input type="text" wire:model="topic_name"
                        class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    @error('topic_name')
                        <span class="px-2 w-full text-red-700">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <input type="text" wire:model="description"
                        class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    @error('description')
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
