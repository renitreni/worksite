<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <title>Employer Registration Update</title>
</head>

<body style="margin:0;padding:0;background-color:#f4f6f8;font-family:Arial, Helvetica, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f8;padding:40px 0;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:white;border-radius:10px;overflow:hidden;box-shadow:0 6px 18px rgba(0,0,0,0.06);">

                    <!-- HEADER -->

                    <tr>
                        <td style="background:#dc2626;padding:22px;text-align:center;color:white;">
                            <h1 style="margin:0;font-size:22px;">JobAbroad</h1>
                            <p style="margin:5px 0 0;font-size:13px;opacity:0.9;">
                                Employer Registration Review
                            </p>
                        </td>
                    </tr>

                    <!-- BODY -->

                    <tr>
                        <td style="padding:35px;color:#374151;">

                            <h2 style="margin-top:0;color:#111827;font-size:20px;">
                                Employer Registration Not Approved
                            </h2>

                            <p style="font-size:15px;line-height:1.7;">
                                Hello <strong>{{ $employerName }}</strong>,
                            </p>

                            <p style="font-size:15px;line-height:1.7;">
                                Thank you for registering your company <strong>{{ $companyName }}</strong> on
                                <strong>JobAbroad</strong>.
                            </p>

                            <p style="font-size:15px;line-height:1.7;">
                                After reviewing your employer registration, we regret to inform you that
                                the application could not be approved at this time.
                            </p>

                            <p style="font-size:15px;line-height:1.7;">
                                <strong>Reason for rejection:</strong>
                            </p>

                            <div
                                style="background:#fef2f2;border:1px solid #fecaca;padding:15px;border-radius:6px;font-size:14px;color:#7f1d1d;">
                                {{ $reason }}
                            </div>

                            <p style="font-size:15px;line-height:1.7;margin-top:20px;">
                                You may update your company information and submit a new registration if necessary.
                                If you believe this decision was made in error, please contact our support team for
                                assistance.
                            </p>

                            <p style="font-size:15px;margin-top:25px;">
                                Sincerely,<br>
                                <strong>JobAbroad Administration Team</strong>
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
                                This message was sent regarding the employer registration for
                                <strong>{{ $companyName }}</strong>.
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
