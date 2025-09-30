<h1>Meine Bewertungen</h1>
<a href="/bewertungen" class="btn btn-secondary">Alle Bewertungen</a> <!-- Link to Alle Bewertungen -->
<a href="/emensa" class="btn btn-primary">Zurück zu E-Mensa</a> <!-- Link to E-Mensa -->

<ul>
    @foreach($bewertungen as $bewertung)
        <li>
            @if(!empty($bewertung['gericht_image']))
                <img src="{{ $bewertung['gericht_image'] }}" alt="{{ $bewertung['gericht_name'] }}" style="width:120px;"><br>
            @endif
            <strong>{{ $bewertung['gericht_name'] }}</strong>: {{ $bewertung['bemerkung'] }} - {{ $bewertung['sterne'] }} Sterne - {{ $bewertung['bewertungszeitpunkt'] }}
            <!-- Deletion Form -->
            <form action="/bewertungloeschen" method="POST">
                <input type="hidden" name="delete" value="{{ $bewertung['id'] }}">
                <button type="submit">Löschen</button>
            </form>
        </li>
    @endforeach
</ul>
