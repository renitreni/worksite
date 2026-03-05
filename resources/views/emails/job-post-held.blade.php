<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <title>Job Post Under Review</title>
</head>

<body style="margin:0;padding:0;background:#f4f6f8;font-family:Arial, Helvetica, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;background:#f4f6f8;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:white;border-radius:8px;overflow:hidden;">

                    <tr>
                        <td style="background:#f59e0b;color:white;padding:20px;text-align:center;">
                            <h2 style="margin:0;">JobAbroad</h2>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px;color:#374151;">

                            <p>Dear <strong>{{ $companyName }}</strong>,</p>

                            <p>
                                Your job posting titled <strong>{{ $jobTitle }}</strong> has been placed on
                                <strong>temporary hold</strong> by the JobAbroad administration team.
                            </p>

                            <p>
                                The job post is currently under administrative review but may still be visible
                                to candidates during this period.
                            </p>

                            <p><strong>Reason for hold:</strong></p>

                            <div style="background:#fff7ed;border:1px solid #fed7aa;padding:15px;border-radius:5px;">
                                {{ $reason }}
                            </div>

                            <p style="margin-top:20px;">
                                Please review your job posting and make any necessary updates if required.
                            </p>

                            <p>
                                If you have questions, you may contact the JobAbroad support team.
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
