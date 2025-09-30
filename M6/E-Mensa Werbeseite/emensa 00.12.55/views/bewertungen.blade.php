<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alle Bewertungen</title>
    <link rel="stylesheet" href="/css/bewertungen.css">
</head>
<body>

<h1>Alle Bewertungen</h1>
<a href="/meinebewertungen" class="btn btn-secondary">Meine Bewertungen</a> <!-- Link to Meine Bewertungen -->
<a href="/emensa" class="btn btn-primary">Zurück zu E-Mensa</a> <!-- Link to E-Mensa -->

<ul>
    @foreach($bewertungen as $bewertung)
        <li class="{{ $bewertung['hervorgehoben'] ? 'highlighted-review' : '' }}">
            @if(!empty($bewertung['gericht_image']))
                <img src="{{ $bewertung['gericht_image'] }}" alt="{{ $bewertung['gericht_name'] }}" style="width:120px;"><br>
            @endif
            <strong>{{ $bewertung['gericht_name'] }}</strong>:
            {{ $bewertung['bemerkung'] }} - {{ $bewertung['sterne'] }} Sterne - {{ $bewertung['bewertungszeitpunkt'] }}

            <!-- Show highlight/unhighlight button only for admin users -->
            @if(isset($_SESSION['user']) && $_SESSION['user']['admin'] == true)
                <form method="POST" action="{{ $bewertung['hervorgehoben'] ? '/bewertungen_unhighlight' : '/bewertungen_highlight' }}" style="display:inline;">
                    @csrf
                    <input type="hidden" name="bewertung_id" value="{{ $bewertung['id'] }}">
                    <button type="submit" class="btn {{ $bewertung['hervorgehoben'] ? 'btn-warning' : 'btn-success' }}">
                        {{ $bewertung['hervorgehoben'] ? 'Hervorhebung abwählen' : 'Hervorheben' }}
                    </button>
                </form>
            @endif
        </li>
    @endforeach
</ul>

</body>
</html>
