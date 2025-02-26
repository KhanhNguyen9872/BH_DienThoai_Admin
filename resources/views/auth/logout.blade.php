<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Logging Out...</title>
</head>
<body>
  <script>
    // Remove the accessToken from localStorage
    localStorage.removeItem('accessToken');
    // Optionally, clear all localStorage:
    // localStorage.clear();
    // Redirect to the /login page
    window.location.href = '/login';
  </script>
</body>
</html>
