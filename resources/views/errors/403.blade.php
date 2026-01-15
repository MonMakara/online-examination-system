<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Forbidden</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-6">
    <div class="max-w-md w-full text-center">
        <div class="mb-8 flex justify-center">
            <div class="w-32 h-32 bg-red-50 rounded-full flex items-center justify-center border border-red-100">
                <svg class="w-16 h-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0-8V3m0 0l-4 4m4-4l4 4m-4 8a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                </svg>
            </div>
        </div>

        <h1 class="text-4xl font-bold text-gray-900 mb-2">Access Denied</h1>
        <p class="text-gray-500 mb-8">
            Sorry, you don't have the permission to access this page. Please contact your instructor if you think this is a mistake.
        </p>

        <a href="{{ route('student.dashboard') }}" class="inline-flex items-center text-blue-600 font-bold hover:underline">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Return to Dashboard
        </a>
    </div>
</body>
</html>