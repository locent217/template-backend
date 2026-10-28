<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Password changed</title></head>
<body style="margin:0;padding:0;background:#f4f6fa;font-family:Arial,Helvetica,sans-serif;color:#182238;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#f4f6fa;"><tr><td align="center" style="padding:40px 16px;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:560px;background:#fff;border:1px solid #e6eaf1;border-radius:14px;">
<tr><td style="padding:34px 38px 24px;border-bottom:1px solid #eef0f4;">
<div style="font-size:12px;font-weight:700;letter-spacing:1.2px;color:#4057ad;text-transform:uppercase;">{{ config('app.name') }} · Account security</div>
<h1 style="font-size:25px;line-height:1.3;margin:22px 0 12px;color:#17233b;">Your password was changed</h1>
<p style="font-size:15px;line-height:1.7;color:#526079;margin:0;">Hello {{ $recipientName }}, this is a confirmation that the password for your account has been changed successfully.</p>
</td></tr>
<tr><td style="padding:28px 38px;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#f7f9fc;border:1px solid #e8ecf3;border-radius:10px;">
<tr><td style="padding:18px 20px 8px;font-size:12px;color:#6b7890;letter-spacing:.6px;">SECURITY ACTIVITY</td></tr>
<tr><td style="padding:0 20px 6px;font-size:16px;font-weight:700;color:#1d2b46;">Password updated</td></tr>
<tr><td style="padding:0 20px 20px;font-size:13px;color:#526079;">{{ $method === 'recovery' ? 'Password recovery' : ($method === 'first_use' ? 'Initial account setup' : 'Account settings') }}</td></tr>
</table>
<p style="font-size:14px;line-height:1.7;color:#526079;margin:24px 0 10px;">If you made this change, no further action is required.</p>
<p style="font-size:14px;line-height:1.7;color:#273650;margin:0;"><strong>Didn't change your password?</strong> Contact your system administrator immediately and ask them to secure your account.</p>
<table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:26px 0 18px;"><tr><td bgcolor="#4057ad" style="border-radius:8px;"><a href="{{ $loginUrl }}" style="display:inline-block;padding:14px 26px;color:#fff;text-decoration:none;font-size:14px;font-weight:700;">Go to sign in</a></td></tr></table>
<p style="font-size:12px;line-height:1.6;color:#6b7890;margin:0;word-break:break-all;">Or visit <a href="{{ $loginUrl }}" style="color:#4057ad;">{{ $loginUrl }}</a></p>
</td></tr>
<tr><td style="padding:20px 38px;border-top:1px solid #eef0f4;font-size:12px;line-height:1.6;color:#7a8497;">This is an automated security notice from {{ config('app.name') }}. We never include your password in these notifications.</td></tr>
</table></td></tr></table>
</body></html>
