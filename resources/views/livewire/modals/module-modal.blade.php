<div x-data="{ isOpen: @entangle('isModalOpen') }">
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
    <!-- Modal -->
    <div x-show="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        @click.away="isOpen = false" style="display: none;">
        <div class="modal-overlay" style="background: rgba(0, 0, 0, 0.5);"></div>
        <div class="w-full max-w-lg p-6 bg-white rounded-lg shadow-lg " x-show.transition.opacity="isOpen">
            <h2 class="mb-4 text-xl font-semibold">Create Module</h2>

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
                {{-- action="{{ route('QuestionBank.module.create') }}" method="post"
                @csrf --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Module Name</label>
                    <input type="text" wire:model="module_name"
                        class="block w-full p-2 mt-1 border border-gray-300 rounded-md">
                    @error('module_name')
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
                        class="px-4 py-2 text-white bg-green-500 rounded">Submit
                        <i wire:loading="create" class="fa fa-spinner fa-spin"></i></button>
                </div>
            </form>
        </div>
    </div>


</div>
