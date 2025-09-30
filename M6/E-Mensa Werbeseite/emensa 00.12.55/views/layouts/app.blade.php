<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Mensa</title>
</head>
<body>
<header>
    <!-- Your navigation menu or logo here -->
    <nav>
        <ul>
            <li><a href="/bewertung">Reviews</a></li>
            <li><a href="/emensa">Home</a></li>
        </ul>
    </nav>
</header>

<main>
    @yield('content')  <!-- Dynamic content will be injected here -->
</main>

<footer>
    <!-- Your footer content here -->
</footer>
</body>
</html>
