<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <title>Account Disabled</title>
</head>

<body style="margin:0;padding:0;background:#f4f6f8;font-family:Arial, Helvetica, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;background:#f4f6f8;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:white;border-radius:10px;overflow:hidden;box-shadow:0 6px 18px rgba(0,0,0,0.06);">

                    <tr>
                        <td style="background:#374151;color:white;text-align:center;padding:22px;">
                            <h1 style="margin:0;font-size:22px;">JobAbroad</h1>
                            <p style="margin:4px 0 0;font-size:13px;opacity:0.9;">
                                Overseas Job Opportunities Platform
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:35px;color:#374151;">

                            <h2 style="margin-top:0;color:#111827;font-size:20px;">
                                Account Disabled
                            </h2>

                            <p style="font-size:15px;line-height:1.7;">
                                Hello <strong>{{ $name }}</strong>,
                            </p>

                            <p style="font-size:15px;line-height:1.7;">
                                We would like to inform you that your <strong>{{ ucfirst($role) }}</strong> account on
                                <strong>JobAbroad</strong> has been disabled by the administration team.
                            </p>

                            <p style="font-size:15px;line-height:1.7;">
                                As a result, you will no longer be able to access your account or use the platform.
                            </p>

                            <p style="font-size:15px;line-height:1.7;">
                                If you believe this action was taken in error or would like further clarification,
                                please contact our support team.
                            </p>

                            <p style="font-size:15px;margin-top:25px;">
                                Sincerely,<br>
                                <strong>JobAbroad Administration</strong>
                            </p>

                        </td>
                    </tr>

                    <tr>
                        <td style="background:#f9fafb;padding:20px;text-align:center;font-size:12px;color:#6b7280;">
                            © {{ date('Y') }} JobAbroad. All rights reserved.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
