<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Verify OTP | Exam System</title>
</head>

<body>
    <div class="min-h-screen bg-gray-100 flex flex-col justify-center py-12 px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
            <h2 class="text-3xl font-extrabold text-gray-900">Verify your identity</h2>
            <p class="mt-2 text-sm text-gray-600">
                We have sent a code to your email.
                <br>
                <a href="{{ route('show-login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                    &larr; Back to Login
                </a>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-6 shadow-xl rounded-lg sm:px-10 border border-gray-100">
                
                {{-- Success Message --}}
                @if (session('success'))
                    <div class="rounded-md bg-green-50 p-4 mb-4 border border-green-200" role="alert">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Global Error Message --}}
                @if (session('error'))
                    <div class="rounded-md bg-red-50 p-4 mb-4 border border-red-200" role="alert">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form class="space-y-6" action="{{ route('otp.verify.submit') }}" method="POST">
                    @csrf
                    
                    <div>
                        <label for="otp" class="block text-sm font-medium text-gray-700 text-center mb-2">
                            Enter the 6-digit code
                        </label>
                        <div class="mt-1">
                            {{-- 
                                Styling Note: 
                                - 'text-center': Centers the numbers 
                                - 'tracking-[1em]': Adds space between numbers for that "OTP" look
                                - 'text-lg': Makes the text slightly larger
                            --}}
                            <input id="otp" name="otp" type="text" inputmode="numeric" maxlength="6" required autofocus autocomplete="one-time-code"
                                class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-lg text-center tracking-[1em] font-bold text-gray-700"
                                placeholder="------">
                        </div>
                        @error('otp')
                            <p class="mt-2 text-center text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                            Verify Account
                        </button>
                    </div>
                </form>

                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Didn't receive the code?</span>
                        </div>
                    </div>

                    <div class="mt-6 text-center">
                        {{-- 
                           Note: If you have a resend route, change href to: route('otp.resend') 
                           For now, we can redirect to login to restart the process since we didn't build a dedicated resend endpoint yet.
                        --}}
                        <a href="{{ route('show-login') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                            Try logging in again
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = "opacity 0.5s ease";
                    alert.style.opacity = "0";
                    setTimeout(() => alert.remove(), 500);
                }, 3000);
            });
        });
    </script>
</body>

</html>