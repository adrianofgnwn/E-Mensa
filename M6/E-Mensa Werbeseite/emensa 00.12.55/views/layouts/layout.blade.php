<!-- resources/views/layouts/app.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review System</title>
</head>
<body>
<nav>
    <a href="/">Home</a>
    <a href="/bewertungen">Latest Reviews</a>
    <a href="/meinebewertungen">My Reviews</a>
</nav>
<div class="container">
    @yield('content')
</div>
</body>
</html>
