<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Your account is ready</title></head>
<body style="margin:0;padding:0;background:#f4f6fa;font-family:Arial,Helvetica,sans-serif;color:#182238;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#f4f6fa;"><tr><td align="center" style="padding:40px 16px;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:560px;background:#ffffff;border:1px solid #e6eaf1;border-radius:14px;">
<tr><td style="padding:34px 38px 18px;border-bottom:1px solid #eef0f4;">
<div style="font-size:13px;font-weight:700;letter-spacing:1px;color:#4256a4;text-transform:uppercase;">{{ config('app.name') }}</div>
<h1 style="font-size:25px;line-height:1.3;margin:20px 0 8px;color:#17233b;">Welcome aboard, {{ $recipientName }}!</h1>
<p style="font-size:15px;line-height:1.7;color:#526079;margin:0;">Your account has been created. Use the credentials below to sign in for the first time.</p>
</td></tr>
<tr><td style="padding:28px 38px;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#f7f9fc;border:1px solid #e8ecf3;border-radius:10px;">
<tr><td style="padding:18px 20px 8px;font-size:12px;color:#6b7890;">USERNAME</td></tr>
<tr><td style="padding:0 20px 14px;font-size:16px;font-weight:700;color:#1d2b46;">{{ $username }}</td></tr>
<tr><td style="padding:0 20px 8px;font-size:12px;color:#6b7890;">TEMPORARY PASSWORD</td></tr>
<tr><td style="padding:0 20px 20px;font-size:16px;font-weight:700;color:#1d2b46;">{{ $temporaryPassword }}</td></tr>
</table>
<table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:26px 0 20px;"><tr><td bgcolor="#4057ad" style="border-radius:8px;"><a href="{{ $loginUrl }}" style="display:inline-block;padding:14px 26px;color:#ffffff;text-decoration:none;font-size:14px;font-weight:700;">Sign in to your account</a></td></tr></table>
<p style="font-size:13px;line-height:1.7;color:#526079;margin:0;">For your security, you'll be asked to choose a new username and password on your first sign-in. Do not forward this email or share your temporary password.</p>
<p style="font-size:12px;line-height:1.6;color:#6b7890;word-break:break-all;">If the button doesn't work, visit: <a href="{{ $loginUrl }}" style="color:#4057ad;">{{ $loginUrl }}</a></p>
</td></tr>
<tr><td style="padding:20px 38px;border-top:1px solid #eef0f4;font-size:12px;line-height:1.6;color:#7a8497;">If you didn't expect this account, contact your administrator. This is an automated message from {{ config('app.name') }}.</td></tr>
</table></td></tr></table>
</body></html>
