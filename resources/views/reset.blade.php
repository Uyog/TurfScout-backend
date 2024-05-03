<style>
    body {
        background-color: black; /* Set background color to black */
        color: limegreen; /* Set text color to neon green */
        font-family: Arial, sans-serif; /* Optional: Set font family */
        display: flex; /* Use flexbox for centering */
        justify-content: center; /* Center horizontally */
        align-items: center; /* Center vertically */
        height: 100vh; /* Set height to full viewport height */
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
        color: limegreen; /* Set label text color to lime green */
    }

    input[type="password"] {
        width: 100%; /* Make input fields full width */
        padding: 10px; /* Add padding for better appearance */
        margin-bottom: 20px; /* Add some spacing between input fields */
        background-color: #333; /* Darker shade for input background */
        border: none; /* Remove default input border */
        color: limegreen; /* Set text color to neon green */
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

<form method="POST" action="{{ route('password.update') }}">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ $email }}">

    <div>
        <label for="password">{{ __('Password') }}</label>
        <input id="password" type="password" name="password" required>
    </div>

    <div>
        <label for="password_confirmation">{{ __('Confirm Password') }}</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required>
    </div>

    <div>
        <button type="submit">{{ __('Reset Password') }}</button>
    </div>
</form>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('resetPasswordForm');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const passwordError = document.getElementById('passwordError');
        const confirmPasswordError = document.getElementById('confirmPasswordError');
        const resetPasswordButton = document.getElementById('resetPasswordButton');

        form.addEventListener('submit', function (event) {
            if (!validatePassword()) {
                event.preventDefault(); // Prevent form submission if validation fails
            }
        });

        // Validate password and confirm password fields
        function validatePassword() {
            let isValid = true;

            // Reset error messages
            passwordError.textContent = '';
            confirmPasswordError.textContent = '';

            // Validate password
            if (passwordInput.value.length < 8) {
                passwordError.textContent = 'Password must be at least 8 characters long';
                isValid = false;
            }

            // Validate confirm password
            if (passwordInput.value !== confirmPasswordInput.value) {
                confirmPasswordError.textContent = 'Passwords do not match';
                isValid = false;
            }

            return isValid;
        }

        // Add event listeners to input fields for real-time validation
        passwordInput.addEventListener('input', validatePassword);
        confirmPasswordInput.addEventListener('input', validatePassword);
    });
</script>
