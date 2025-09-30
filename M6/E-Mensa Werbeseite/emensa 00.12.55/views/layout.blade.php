{{-- layout.blade.php --}}
        <!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bewertungssystem</title>
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
<header>
    <h1>Willkommen beim Bewertungssystem</h1>
    <nav>
        <ul>
            <li><a href="/">Startseite</a></li>
            <li><a href="/bewertung">Bewertung abgeben</a></li>
            <li><a href="/bewertungen">Alle Bewertungen</a></li>
            <li><a href="/meinebewertungen">Meine Bewertungen</a></li>
            <li><a href="/abmeldung">Abmelden</a></li>
        </ul>
    </nav>
</header>

<div class="content">
    @yield('content')
</div>

<footer>
    <p>&copy; 2025 Bewertungssystem</p>
</footer>
</body>
</html>
