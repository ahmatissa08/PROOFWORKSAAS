<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Verify your ProofWork email</title>
</head>
<body style="margin:0;background:#0c0c0e;color:#f2f0eb;font-family:Arial,sans-serif;line-height:1.6;padding:32px">
  <div style="max-width:560px;margin:0 auto;background:#131316;border:1px solid #242428;border-radius:10px;overflow:hidden">
    <div style="height:3px;background:#e8a325"></div>
    <div style="padding:28px">
      <h1 style="margin:0 0 12px;font-size:24px;font-weight:600;color:#f2f0eb">Verify your email</h1>
      <p style="margin:0 0 18px;color:#a09e9a">Hi {{ $user->name }},</p>
      <p style="margin:0 0 24px;color:#a09e9a">Click the button below to activate your ProofWork account. This link expires in 60 minutes.</p>
      <p style="margin:0 0 24px">
        <a href="{{ $verificationUrl }}" style="display:inline-block;background:#e8a325;color:#000;text-decoration:none;font-weight:700;padding:12px 18px;border-radius:6px">Verify email</a>
      </p>
      <p style="margin:0;color:#5a5855;font-size:12px">If the button does not work, copy and paste this link into your browser:<br>{{ $verificationUrl }}</p>
    </div>
  </div>
</body>
</html>
