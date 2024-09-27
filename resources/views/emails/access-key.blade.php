<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Your Access Key</title>
    </head>

    <body style="background-color: #f7fafc; margin: 0; padding: 0;">
        <div
            style="max-width: 32rem; margin: 2rem auto; padding: 1.5rem; background-color: #ffffff; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <h1 style="font-size: 1.5rem; font-weight: 600; text-align: center; color: #2d3748;">Access Key Notification
            </h1>
            <p style="margin-top: 1rem; color: #4a5568;">Dear Supervisor,</p>
            <p style="margin-top: 0.5rem; color: #4a5568;">You have been issued an access key to our Internship
                Management System. This key will allow you to monitor and evaluate the performance of internship
                students.</p>

            <div
                style="margin-top: 1rem; width: 100%; padding: 0.5rem; border-radius: 0.375rem; background-color: #edf2f7; text-align: center; font-size: 1.125rem; font-weight: 700;">
                <span>{{ $accessKey }}</span>
            </div>

            <p style="margin-top: 1rem; color: #4a5568;">Please ensure that you keep this access key secure, as it is
                required to log in to the system. Click the button below to access your account:</p>

            <div style="margin-top: 1.5rem; text-align: center;">
                <a href="{{ url('/c/login?accessKey=' . $accessKey) }}"
                    style="display: inline-block; background-color: #3182ce; color: white; font-weight: 600; padding: 0.5rem 1rem; border-radius: 0.5rem; text-decoration: none; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); transition: background-color 0.3s;">
                    Login to Your Account
                </a>
            </div>

            <footer style="margin-top: 1.5rem; text-align: center; color: #a0aec0; font-size: 0.875rem;">
                <p>&copy; {{ date('Y') }} {{ $school->name }}. All rights reserved.</p>
            </footer>
        </div>
    </body>

</html>
