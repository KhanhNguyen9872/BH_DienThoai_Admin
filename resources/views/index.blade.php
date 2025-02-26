<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Phone Store Management</title>
    <script>
        // Wait for the DOM to load
        document.addEventListener("DOMContentLoaded", function() {
            // Check if 'accessToken' is in localStorage
            if (localStorage.getItem('accessToken')) {
                window.location.href = '/dashboard';
            } else {
                // If not found, redirect to the login page
                window.location.href = '/login';
            }
        });
    </script>
</head>
<body>
    <div class="container">
    </div>
</body>
</html>
