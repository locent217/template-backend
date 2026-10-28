<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="background:#f5f7fb;margin:0;padding:32px 12px;font-family:Arial,sans-serif;color:#1c2736">
<table role="presentation" cellspacing="0" cellpadding="0" style="max-width:540px;width:100%;margin:auto;background:#fff;border:1px solid #e8ecf2;border-radius:14px"><tr><td style="padding:36px">
<p style="font-size:13px;letter-spacing:1.4px;font-weight:700;color:#4263eb">ACCOUNT SECURITY</p>
<h1 style="font-size:24px;margin:20px 0">{{ $kind === 'otp' ? 'Reset your password' : 'Your username' }}</h1>
<p style="line-height:1.6">Hello {{ $recipientName }},</p>
@if($kind === 'otp')
<p style="line-height:1.6">Enter this one-time verification code in the password reset page:</p>
<div style="background:#f1f4fb;border-radius:9px;padding:18px;text-align:center;font-size:30px;font-weight:bold;letter-spacing:6px">{{ $otp }}</div>
<p style="color:#64748b;font-size:13px">This code expires in 5 minutes. If you did not request it, you can ignore this email.</p>
@else
<p style="line-height:1.6">The username registered to your account is:</p>
<div style="background:#f1f4fb;border-radius:9px;padding:18px;font-size:19px;font-weight:bold">{{ $username }}</div>
@endif
<p style="margin-top:28px"><a href="{{ $loginUrl }}" style="background:#4263eb;color:#fff;text-decoration:none;padding:12px 20px;border-radius:8px;display:inline-block">Go to sign in</a></p>
<p style="font-size:12px;color:#94a3b8;margin-top:36px">This is an automated security message. Please do not reply.</p>
</td></tr></table></body></html>
