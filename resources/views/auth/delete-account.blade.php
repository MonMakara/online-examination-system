<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Delete Account</title>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen px-6">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-xl border border-red-100">

        <div class="text-center mb-6">
            <div class="mx-auto h-16 w-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900">Delete Account</h2>
            <p class="mt-2 text-sm text-gray-500">
                Are you sure? This action is <strong>permanent</strong>. All your exam data and history will be wiped.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('account.delete') }}" method="POST" class="space-y-6">
            @csrf
            @method('DELETE')

            {{-- Only show password field if user actually has a password --}}
            @if (auth()->user()->password)
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Confirm your password to delete
                    </label>
                    <input type="password" name="password" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm"
                        placeholder="Enter your password">
                </div>
            @else
                <div class="bg-yellow-50 p-4 rounded-md border border-yellow-200">
                    <p class="text-sm text-yellow-700">
                        Since you logged in with Google, you do not need a password to delete your account.
                    </p>
                </div>
            @endif

            <button type="submit"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Yes, Delete My Account
            </button>
        </form>

        <div class="mt-4 text-center">
            <a href="{{ route('student.dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                Cancel, keep my account
            </a>
        </div>
    </div>
</body>

</html>
