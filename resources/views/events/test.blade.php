<form method="POST" action="/events/store">
    @csrf
    <input type="text" name="title" value="Deneme etkinliği"><br>
    <input type="datetime-local" name="start"><br>
    <input type="datetime-local" name="end"><br>
    <button type="submit">Gönder</button>
</form>