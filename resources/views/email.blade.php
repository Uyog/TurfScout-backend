<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>
<body>
    <h1>Password Reset</h1>
    <p>Hello!</p>
    <p>You are receiving this email because we received a password reset request for your account.</p>
    <p>If you did not request a password reset, no further action is required.</p>
    <p>To reset your password, click the following link:</p>
    <a href="{{ $resetLink }}">Reset Password</a>
    <p>If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:</p>
    <p>{{ $resetLink }}</p>
    <p>Thank you!</p>
</body>
</html>