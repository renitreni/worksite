<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <title>Job Post Disabled</title>
</head>

<body style="margin:0;padding:0;background:#f4f6f8;font-family:Arial, Helvetica, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;background:#f4f6f8;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:white;border-radius:8px;overflow:hidden;">

                    <tr>
                        <td style="background:#dc2626;color:white;padding:20px;text-align:center;">
                            <h2 style="margin:0;">JobAbroad</h2>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px;color:#374151;">

                            <p>Dear <strong>{{ $companyName }}</strong>,</p>

                            <p>
                                Your job posting titled <strong>{{ $jobTitle }}</strong> has been
                                <strong>disabled</strong> by the JobAbroad administration team.
                            </p>

                            <p>
                                This means the job listing is no longer visible to candidates on the platform.
                            </p>

                            <p><strong>Reason for disabling:</strong></p>

                            <div
                                style="background:#fef2f2;border:1px solid #fecaca;padding:15px;border-radius:5px;color:#7f1d1d;">
                                {{ $reason }}
                            </div>

                            <p style="margin-top:20px;">
                                If you believe this action was taken in error, please contact the JobAbroad
                                administration team for assistance.
                            </p>

                            <p style="margin-top:25px;">
                                Regards,<br>
                                <strong>JobAbroad Administration</strong>
                            </p>

                        </td>
                    </tr>

                    <tr>
                        <td style="background:#f9fafb;text-align:center;padding:15px;font-size:12px;color:#6b7280;">
                            © {{ date('Y') }} JobAbroad
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
