<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; color: #1f2937;">
    <p>Hello,</p>

    <p>Thank you for submitting your enrollment application. Please keep the reference code below to track your application status and to submit your required documents.</p>

    <p style="font-size: 24px; font-weight: bold; letter-spacing: 2px; background-color: #f3f4f6; padding: 12px 16px; border-radius: 6px; display: inline-block;">
        {{ $referenceCode }}
    </p>

    <p>If you have any questions, please contact the school registrar's office.</p>

    <p>— {{ config('app.name') }}</p>
</body>
</html>
