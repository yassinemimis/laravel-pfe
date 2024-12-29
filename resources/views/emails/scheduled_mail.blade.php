<!-- resources/views/emails/scheduled_mail.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>{{ $details['subject'] }}</title>
</head>
<body>
    <h1>{{ $details['title'] }}</h1>
    <p>{{ $details['body'] }}</p>
</body>
</html>
