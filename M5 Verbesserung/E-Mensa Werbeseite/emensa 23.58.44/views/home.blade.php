@extends('layouts.main')

@section('content')

    <header>
        <img src="/img/E-Mensa-Logo.png" alt="E-Mensa Logo">
        <ul>
            <li><a href="#Ankündigung">Ankündigung</a></li>
            <li><a href="#Speisen">Speisen</a></li>
            <li><a href="#Zahlen">Zahlen</a></li>
            <li><a href="#Kontakt">Kontakt</a></li>
            <li><a href="#Wichtig">Wichtig für uns</a></li>
        </ul>

        <div class="header">
            @if($username) <!-- Check if the username is set in the session -->
            <p>Angemeldet als {{ $username }}</p>
            <a href="/abmeldung" class="btn btn-secondary">Abmelden</a>
            @else
                <p>Sie sind nicht angemeldet.</p>
                <a href="/anmeldung" class="btn btn-primary">Login</a> <!-- Login button -->
            @endif
        </div>
    </header>

    <main>
        <div id="menupic">
            <img src="/img/RINDFLEISCH.jpg" alt="Rindfleisch Gerichte">
        </div>
        <h1 id="Ankündigung">Bald gibt es Essen auch online;)</h1>
        <div id="text">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
        </div>

        <h1 id="Speisen">Köstlichkeiten, die Sie erwarten</h1>
        <table>
            <tr>
                <th>Gericht</th>
                <th>Preis intern</th>
                <th>Preis extern</th>
                <th>ALLERGENS</th>
            </tr>
            @foreach($dishes as $dish)
                <tr>
                    <td>
                        @if(!empty($dish['image']))
                            <img src="{{ $dish['image'] }}" alt="{{ $dish['name'] }}" style="width:100px;"><br>
                        @endif
                        {{ $dish['name'] }}
                    </td>
                    <td class="Preis">{{ $dish['preisintern'] }} &euro;</td>
                    <td class="Preis">{{ $dish['preisextern'] }} &euro;</td>
                    <td>
                        @if(!empty($dish['allergens']))
                            {{ implode(", ", $dish['allergens']) }}
                        @else
                            Keine Allergene!!!
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>

        <a href="?sort={{ $newSortOrder }}">Sortiere nach Name ({{ strtoupper($newSortOrder) }})</a>

        <h2>Liste der verwendeten Allergene:</h2>
        <ul>
            @foreach($unique_allergens as $allergen)
                <li>{{ $allergen }}</li>
            @endforeach
        </ul>

        <h1>Kategorien mit mehr als 2 Gerichten</h1>
        <table>
            <tr>
                <th>Kategorie</th>
                <th>Anzahl der Gerichte</th>
            </tr>
            @foreach($popular_categories as $category => $count)
                <tr>
                    <td>{{ htmlspecialchars($category) }}</td>
                    <td>{{ $count }}</td>
                </tr>
            @endforeach
        </table>

        <h1 id="Zahlen">E-mensa in Zahlen</h1>
        <ul id="inzahlen">
            <li>{{ $visitor_count }}</li>
            <li>Besuche</li>
            <li>{{ $signup_count }}</li>
            <li>Anmeldungen zum Newsletter</li>
            <li>{{ $dish_count }}</li>
            <li>Speisen</li>
        </ul>

        <h1 id="Kontakt">Interesse geweckt? Wir informieren Sie!</h1>
        <form action="/newsletter_signup" method="post">
            <div id="form">
                <div id="formname">
                    <label for="namen">Ihr Name</label>
                    <input type="text" id="namen" name="namen" placeholder="Vorname" required>
                </div>
                <div id="formaddress">
                    <label for="email">Ihre E-Mail</label>
                    <input type="email" id="email" name="emails" required>
                </div>
                <div id="formlanguage">
                    <label for="newsletter">Newsletter bitte in</label>
                    <select id="newsletter" name="sprache">
                        <option value="deutsch" selected>Deutsch</option>
                        <option value="englisch">Englisch</option>
                    </select><br>
                </div>
            </div>
            <input type="checkbox" required id="checkbox" name="datenschutz">
            <label for="checkbox">Den Datenschutzbestimmungen stimme ich zu</label>
            <input type="submit" value="Zum Newsletter anmelden" id="button">
        </form>

        <h1 id="Wichtig">Das ist uns Wichtig</h1>
        <div id="mid">
            <ul>
                <li>Beste frische saisonale Zutaten</li>
                <li>Ausgewogene abwechslungsreiche Gerichte</li>
                <li>Sauberkeit</li>
            </ul>
        </div>

        <h1 id="Besuch">Wir freuen uns auf Ihren Besuch!</h1>
    </main>

    <footer>
        <ul>
            <li>(c) E-Mensa GmbH</li>
            <li>Adriano F. Gunawan & Michael X. Espranata</li>
            <li><a href="index.html">Impressum</a></li>
            <a href="../werbeseite/wunschgericht.php"> Wunschgericht melden</a>
        </ul>
    </footer>
@endsection
