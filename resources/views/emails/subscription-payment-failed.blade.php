<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Payment Failed</title>
</head>

<body style="margin:0;padding:0;background:#f4f6f8;font-family:Arial">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0" style="background:white;border-radius:10px">

                    <tr>
                        <td style="background:#dc2626;color:white;padding:25px;text-align:center">
                            <h2 style="margin:0">Payment Verification Failed</h2>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:35px">

                            <p>Hello <strong>{{ $payment->employer->name }}</strong>,</p>

                            <p>
                                Unfortunately, we were unable to verify your subscription payment.
                            </p>

                            <p><strong>Reason:</strong></p>

                            <div style="background:#fef2f2;border:1px solid #fecaca;padding:10px;border-radius:6px">
                                {{ $payment->fail_reason }}
                            </div>

                            <p style="margin-top:20px">
                                Please submit a new payment or contact our support team if you believe this was an
                                error.
                            </p>

                            <p>
                                Best regards,<br>
                                <strong>JobAbroad Team</strong>
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
