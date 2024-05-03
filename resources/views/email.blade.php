<style>
    body {
        background-color: black; /* Set background color to black */
        color: limegreen; /* Set text color to lime green */
        font-family: Arial, sans-serif; /* Optional: Set font family */
    }

    form {
        margin: 0 auto; /* Center the form horizontally */
        width: 300px; /* Adjust width as needed */
        padding: 20px;
        border: 1px solid limegreen; /* Add border for visibility */
        border-radius: 10px; /* Optional: Add border radius */
        background-color: #111; /* Darker shade of black for better contrast */
    }

    label {
        display: block; /* Ensure labels are displayed on new lines */
        margin-bottom: 10px; /* Add some spacing between label and input */
    }

    input[type="email"] {
        width: 100%; /* Make input fields full width */
        padding: 10px; /* Add padding for better appearance */
        margin-bottom: 20px; /* Add some spacing between input fields */
        background-color: #333; /* Darker shade for input background */
        border: none; /* Remove default input border */
        color: limegreen; /* Set text color to lime green */
        border-radius: 5px; /* Optional: Add border radius */
    }

    button[type="submit"] {
        background-color: limegreen; /* Set button background color */
        color: black; /* Set button text color */
        padding: 10px 20px; /* Add padding for better appearance */
        border: none; /* Remove default button border */
        border-radius: 5px; /* Optional: Add border radius */
        cursor: pointer; /* Change cursor to pointer on hover */
    }

    button[type="submit"]:hover {
        background-color: #0f0; /* Change background color on hover */
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
