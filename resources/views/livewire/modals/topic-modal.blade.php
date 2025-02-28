<div x-data="{ isOpen: @entangle('isModalOpen') }">
    <button @click="isOpen = true" class="px-2 py-1 mt-2 text-white rounded bg-slate-400">
        <i class="fa fa-plus-circle"></i> New</button>
    <div x-show="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        @click.away="isOpen = false" style="display: none;">
        <div class="modal-overlay" @click="open = false" style="background: rgba(0, 0, 0, 0.5);"></div>
        <div class="w-full max-w-lg p-6 bg-white rounded-lg shadow-lg " x-show.transition.opacity="isOpen">
            <h2 class="mb-4 text-xl font-semibold">Create Topic</h2>

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
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                            for="grid-module">
                            Module
                        </label>
                        <select wire:model="module_id" id="module_id"
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
