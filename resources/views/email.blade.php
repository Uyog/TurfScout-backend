<style>
    body {
        background-color: black; 
        color: limegreen; 
        font-family: Arial, sans-serif; 
    }

    form {
        margin: 0 auto;
        width: 300px; 
        padding: 20px;
        border: 1px solid limegreen; 
        border-radius: 10px; 
        background-color: #111; 
    }

    label {
        display: block; 
        margin-bottom: 10px; 
    }

    input[type="email"] {
        width: 100%;
        padding: 10px; 
        margin-bottom: 20px; 
        background-color: #333; 
        border: none;
        color: limegreen; 
        border-radius: 5px; 
    }

    button[type="submit"] {
        background-color: limegreen; 
        color: black; 
        padding: 10px 20px; 
        border: none; 
        border-radius: 5px; 
        cursor: pointer; 
    }

    button[type="submit"]:hover {
        background-color: #0f0; 
    }
</style>

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div>
        <label for="email">{{ __('TurfScout') }}</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
    </div>

    <div>
        <button type="submit">{{ __('Send Password Reset Link') }}</button>
    </div>
</form>
