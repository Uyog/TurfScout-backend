<style>
    body {
        background-color: black; 
        color: limegreen; 
        font-family: Arial, sans-serif; 
        display: flex; 
        justify-content: center; 
        align-items: center; 
        height: 100vh; 
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
        color: limegreen; 
    }

    input[type="password"] {
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
                event.preventDefault(); 
            }
        });


        function validatePassword() {
            let isValid = true;

            
            passwordError.textContent = '';
            confirmPasswordError.textContent = '';

            
            if (passwordInput.value.length < 8) {
                passwordError.textContent = 'Password must be at least 8 characters long';
                isValid = false;
            }

           
            if (passwordInput.value !== confirmPasswordInput.value) {
                confirmPasswordError.textContent = 'Passwords do not match';
                isValid = false;
            }

            return isValid;
        }

        
        passwordInput.addEventListener('input', validatePassword);
        confirmPasswordInput.addEventListener('input', validatePassword);
    });
</script>
