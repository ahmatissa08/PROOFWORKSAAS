<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reset your ProofWork password</title>
</head>
<body style="margin:0;background:#0c0c0e;color:#f2f0eb;font-family:Arial,sans-serif;line-height:1.6;padding:32px">
  <div style="max-width:560px;margin:0 auto;background:#131316;border:1px solid #242428;border-radius:10px;overflow:hidden">
    <div style="height:3px;background:#e8a325"></div>
    <div style="padding:28px">
      <h1 style="margin:0 0 12px;font-size:24px;font-weight:600;color:#f2f0eb">Reset your password</h1>
      <p style="margin:0 0 18px;color:#a09e9a">Hi {{ $user->name }},</p>
      <p style="margin:0 0 24px;color:#a09e9a">Use the button below to reset your ProofWork password.</p>
      <p style="margin:0 0 24px">
        <a href="{{ $url }}" style="display:inline-block;background:#e8a325;color:#000;text-decoration:none;font-weight:700;padding:12px 18px;border-radius:6px">Reset password</a>
      </p>
      <p style="margin:0;color:#5a5855;font-size:12px">If the button does not work, copy and paste this link into your browser:<br>{{ $url }}</p>
    </div>
  </div>
</body>
</html>
