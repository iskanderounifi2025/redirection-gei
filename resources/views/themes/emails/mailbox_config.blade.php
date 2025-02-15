<form action="{{ route('mailboxes.store') }}" method="POST">
    @csrf
    <div>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>
    </div>
    <div>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
    </div>
    <div>
        <label for="host">Host</label>
        <input type="text" name="host" id="host" required>
    </div>
    <div>
        <label for="port">Port</label>
        <input type="number" name="port" id="port" required>
    </div>
    <div>
        <label for="ssl">SSL</label>
        <input type="checkbox" name="ssl" id="ssl" checked>
    </div>
    <button type="submit">Save Configuration</button>
</form>
