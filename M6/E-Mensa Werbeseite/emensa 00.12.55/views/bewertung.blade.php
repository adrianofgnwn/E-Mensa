<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bewertung abgeben</title>
    <link rel="stylesheet" href="/css/bewertung.css">
</head>
<body>
<h1>Bewertung abgeben</h1>
<form action="/bewertung_submit" method="POST">
    <!-- Hidden Field for Gericht ID -->
    <input type="hidden" name="gericht_id" value="{{ $gericht['id'] }}">

    <!-- Dish Name and Image -->
    <p>Gericht: {{ $gericht['name'] }}</p>
    <img src="{{ $gericht['image_url'] }}" alt="{{ $gericht['name'] }}" style="width:120px;">

    <!-- Bemerkung Field -->
    <div class="form-group">
        <label for="bemerkung">Bemerkung:</label>
        <textarea name="bemerkung" id="bemerkung" required minlength="5"></textarea>
    </div>

    <!-- Sterne Field -->
    <div class="form-group">
        <label for="sterne">Sterne:</label>
        <select name="sterne" id="sterne" required>
            <option value="sehr gut">Sehr Gut</option>
            <option value="gut">Gut</option>
            <option value="schlecht">Schlecht</option>
            <option value="sehr schlecht">Sehr Schlecht</option>
        </select>
    </div>

    <!-- Submit Button -->
    <div class="form-group">
        <button type="submit">Bewertung speichern</button>
    </div>
</form>
</body>
</html>
