<div class="container w-1/2 p-4 mx-auto">
    <div class="p-6 bg-white rounded-lg shadow-md">
        <form wire:submit.prevent="submit">
            <div class="mb-4">
                <label for="xtest_password" class="block mb-2 font-medium text-gray-700">
                    {{ __('Test Password') }}
                </label>
                <input type="password" id="xtest_password" wire:model.lazy="test_password" maxlength="255" required
                    placeholder="Please enter the password for the test"
                    class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300" />
                @error('test_password')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <input type="hidden" name="testpswaction" value="login">
            <input type="hidden" name="testid" value="{{ $test_id }}">
            <div class="mb-4">
                <button type="submit" title="{{ __('Enter the test password to log in') }}"
                    class="w-full py-2 font-semibold text-white transition duration-200 bg-blue-500 rounded-md hover:bg-blue-600">
                    {{ __('Login') }}
                </button>
            </div>
        </form>
    </div>

</div>
