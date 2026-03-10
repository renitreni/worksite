<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $subjectLine ?? 'JobAbroad Notification' }}</title>
</head>

<body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,Helvetica,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6;padding:30px 0;">
<tr>
<td align="center">

<table width="100%" cellpadding="0" cellspacing="0" style="max-width:640px;background:#ffffff;border-radius:10px;overflow:hidden">

<tr>
<td style="background:#059669;color:white;padding:20px;font-size:22px;font-weight:bold">
JobAbroad
</td>
</tr>

<tr>
<td style="padding:30px">
{!! $contentHtml !!}
</td>
</tr>

<tr>
<td style="background:#f9fafb;padding:15px;font-size:12px;color:#6b7280">
This email was sent by JobAbroad.
</td>
</tr>

</table>

</td>
</tr>
</table>
</body>
</html>