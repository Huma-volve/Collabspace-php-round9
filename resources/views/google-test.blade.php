<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google OAuth Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    
    <div class="bg-white rounded-xl shadow-lg p-8 max-w-md w-full">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Google OAuth Test</h1>
            <p class="text-gray-600 text-sm">Click the button below to test Google login</p>
        </div>

        <!-- Google Login Button -->
        <a href="http://localhost:8000/api/auth/google/redirect" 
            class="flex items-center justify-center gap-3 w-full px-6 py-3.5 bg-white border-2 border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 hover:border-blue-500 hover:text-blue-600 transition-all duration-200 shadow-sm hover:shadow-md">
            <!-- Google Icon -->
            <svg class="w-5 h-5" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                <g fill="none" fill-rule="evenodd">
                    <path d="M9 3.48c1.69 0 2.83.73 3.48 1.34l2.54-2.48C13.46.89 11.43 0 9 0 5.48 0 2.44 2.02.96 4.96l2.91 2.26C4.6 5.05 6.62 3.48 9 3.48z" fill="#EA4335"/>
                    <path d="M17.64 9.2c0-.74-.06-1.28-.19-1.84H9v3.34h4.96c-.1.83-.64 2.08-1.84 2.92l2.84 2.2c1.7-1.57 2.68-3.88 2.68-6.62z" fill="#4285F4"/>
                    <path d="M3.88 10.78A5.54 5.54 0 0 1 3.58 9c0-.62.11-1.22.29-1.78L.96 4.96A9.008 9.008 0 0 0 0 9c0 1.45.35 2.82.96 4.04l2.92-2.26z" fill="#FBBC05"/>
                    <path d="M9 18c2.43 0 4.47-.8 5.96-2.18l-2.84-2.2c-.76.53-1.78.9-3.12.9-2.38 0-4.4-1.57-5.12-3.74L.97 13.04C2.45 15.98 5.48 18 9 18z" fill="#34A853"/>
                </g>
            </svg>
            Continue with Google
        </a>

        <!-- Response Display -->
        <div id="response" class="mt-6 hidden">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h3 class="font-semibold text-green-800 mb-2">✓ Authentication Successful!</h3>
                <div class="text-sm text-green-700 space-y-1">
                    <p><strong>User:</strong> <span id="user-name"></span></p>
                    <p><strong>Email:</strong> <span id="user-email"></span></p>
                    <p><strong>Google ID:</strong> <span id="google-id"></span></p>
                    <div class="mt-3">
                        <p class="font-medium mb-1">Token:</p>
                        <code id="token" class="block bg-white p-2 rounded text-xs break-all border border-green-300"></code>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Parse URL for response data (if redirected back)
        window.addEventListener('load', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const response = urlParams.get('response');
            
            // If callback returns data as query param
            if (response) {
                try {
                    const data = JSON.parse(decodeURIComponent(response));
                    displayResponse(data);
                } catch (e) {
                    console.error('Failed to parse response:', e);
                }
            }
        });

        function displayResponse(data) {
            document.getElementById('response').classList.remove('hidden');
            document.getElementById('user-name').textContent = data.user.full_name || 'N/A';
            document.getElementById('user-email').textContent = data.user.email || 'N/A';
            document.getElementById('google-id').textContent = data.user.google_id || 'N/A';
            document.getElementById('token').textContent = data.token || 'N/A';
        }
    </script>
</body>
</html>
