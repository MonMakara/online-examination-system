<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Verify Code</title>
</head>
<body>
    <div class="min-h-screen bg-gray-100 flex flex-col justify-center py-12 px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
            <h2 class="text-3xl font-extrabold text-gray-900">Check your Email</h2>
            <p class="mt-2 text-sm text-gray-600">
                We sent a code to {{ session('reset_email') }}
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            @if (session('success'))
                <div class="mb-4 flex items-center p-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50">
                    <div>{{ session('success') }}</div>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 flex items-center p-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50">
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <div class="bg-white py-8 px-6 shadow-xl rounded-lg sm:px-10 border border-gray-100">
                <form class="space-y-6" action="{{ route('password.otp.verify') }}" method="POST">
                    @csrf
                    <div>
                        <label for="otp" class="block text-sm font-medium text-gray-700 text-center">Enter 6-digit Code</label>
                        <div class="mt-1">
                            <input id="otp" name="otp" type="text" maxlength="6" required autofocus autocomplete="one-time-code"
                                class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-center text-2xl tracking-[0.5em] font-bold text-gray-700"
                                placeholder="------">
                        </div>
                        @error('otp')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                            Verify Code
                        </button>
                    </div>
                </form>
                
                <div class="mt-6 text-center">
                    <a href="{{ route('show-login') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>