<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-6">
    <div class="max-w-md w-full text-center">
        {{-- Illustration --}}
        <div class="mb-8 flex justify-center">
            <div class="relative">
                <div class="w-32 h-32 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="absolute top-0 right-0 block h-8 w-8 rounded-full bg-white border-4 border-gray-50 text-blue-600 font-bold flex items-center justify-center shadow-sm">?</span>
            </div>
        </div>

        <h1 class="text-6xl font-bold text-gray-900 mb-2">404</h1>
        <h2 class="text-xl font-bold text-gray-800 mb-4">Oops! Page not found.</h2>
        <p class="text-gray-500 mb-8 leading-relaxed">
            The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
        </p>

        <div class="flex flex-col space-y-3">
            <a href="{{ url('/') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-blue-100">
                Back to Home
            </a>
            <button onclick="window.history.back()" class="w-full bg-white border border-gray-200 text-gray-600 font-bold py-3 rounded-xl hover:bg-gray-50 transition">
                Go Back
            </button>
        </div>
    </div>
</body>
</html>