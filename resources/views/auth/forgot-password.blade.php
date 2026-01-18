<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Forgot Password</title>
</head>
<body>
    <div class="min-h-screen bg-gray-100 flex flex-col justify-center py-12 px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
            <h2 class="text-3xl font-extrabold text-gray-900">Reset Password</h2>
            <p class="mt-2 text-sm text-gray-600">
                Enter your email to receive a code.
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            @if (session('error'))
                <div class="mb-4 flex items-center p-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50">
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <div class="bg-white py-8 px-6 shadow-xl rounded-lg sm:px-10 border border-gray-100">
                <form class="space-y-6" action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="your.email@example.com">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                            Send OTP
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Remembered your password?
                        <a href="{{ route('show-login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Sign in</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>