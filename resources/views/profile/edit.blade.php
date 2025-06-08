<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <a href="{{ route('profile.token') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition">
                {{ __('Generate Token') }}
            </a>

            @if (session('token'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                    <div class="flex items-center justify-between">
                        <div>
                            <strong>{{ __('Generated Token') }}:</strong><br>
                            <pre id="apiToken" class="break-words whitespace-pre-wrap">{{ session('token') }}</pre>
                        </div>
                        <button id="copyButton" onclick="copyToken()" class="ml-4 px-3 py-1 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
                            {{ __('Copy') }}
                        </button>
                    </div>
                </div>

                <script>
                    function copyToken() {
                        const tokenElement = document.getElementById('apiToken');
                        const copyButton = document.getElementById('copyButton');
                        const tokenText = tokenElement.textContent || tokenElement.innerText;

                        const textarea = document.createElement('textarea');
                        textarea.value = tokenText;
                        document.body.appendChild(textarea);
                        textarea.select();
                        document.execCommand('copy');
                        document.body.removeChild(textarea);

                        copyButton.textContent = {{ __('Copied') }};
                        copyButton.classList.remove('bg-green-600', 'hover:bg-green-700');
                        copyButton.classList.add('bg-green-700');

                        setTimeout(() => {
                            copyButton.textContent = {{ __('Copy') }};
                            copyButton.classList.remove('bg-green-700');
                            copyButton.classList.add('bg-green-600', 'hover:bg-green-700');
                        }, 2000);
                    }
                </script>
            @endif

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
