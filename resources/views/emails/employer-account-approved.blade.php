<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <title>Employer Account Approved</title>
</head>

<body style="margin:0;padding:0;background-color:#f4f6f8;font-family:Arial, Helvetica, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f8;padding:40px 0;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:white;border-radius:10px;overflow:hidden;box-shadow:0 6px 18px rgba(0,0,0,0.06);">

                    <!-- HEADER -->

                    <tr>
                        <td style="background:#059669;padding:22px;text-align:center;color:white;">
                            <h1 style="margin:0;font-size:22px;">JobAbroad</h1>
                            <p style="margin:5px 0 0;font-size:13px;opacity:0.9;">
                                Overseas Job Opportunities Platform
                            </p>
                        </td>
                    </tr>

                    <!-- BODY -->

                    <tr>
                        <td style="padding:35px;color:#374151;">

                            <h2 style="margin-top:0;color:#111827;font-size:20px;">
                                Employer Registration Approved
                            </h2>

                            <p style="font-size:15px;line-height:1.7;">
                                Hello <strong>{{ $employerName }}</strong>,
                            </p>

                            <p style="font-size:15px;line-height:1.7;">
                                We are pleased to inform you that the employer account for the company
                                <strong>{{ $companyName }}</strong> has been successfully reviewed and approved
                                by the JobAbroad administration team.
                            </p>

                            <p style="font-size:15px;line-height:1.7;">
                                Your company profile is now active and you may begin posting overseas job
                                opportunities and connecting with qualified job seekers through the platform.
                            </p>

                            <table cellpadding="0" cellspacing="0" style="margin:28px 0;">
                                <tr>
                                    <td>
                                        <a href="{{ url('/employer/login') }}"
                                            style="background:#059669;color:white;padding:12px 26px;text-decoration:none;border-radius:6px;font-size:14px;font-weight:600;">
                                            Login to Employer Dashboard
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size:15px;line-height:1.7;">
                                If you have any questions or need assistance, please feel free to contact our support
                                team.
                            </p>

                            <p style="font-size:15px;margin-top:25px;">
                                Best regards,<br>
                                <strong>JobAbroad Team</strong>
                            </p>

                        </td>
                    </tr>

                    <!-- FOOTER -->

                    <tr>
                        <td style="background:#f9fafb;padding:20px;text-align:center;font-size:12px;color:#6b7280;">

                            <p style="margin:0 0 6px;">
                                © {{ date('Y') }} JobAbroad. All rights reserved.
                            </p>

                            <p style="margin:0;">
                                This notification confirms that the company profile <strong>{{ $companyName }}</strong>
                                has been approved.
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
