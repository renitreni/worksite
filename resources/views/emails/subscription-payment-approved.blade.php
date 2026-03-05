<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Payment Confirmation</title>
</head>

<body style="margin:0;padding:0;background:#f4f6f8;font-family:Arial">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:white;border-radius:10px;overflow:hidden">

                    <tr>
                        <td style="background:#059669;color:white;padding:25px;text-align:center">
                            <h2 style="margin:0">JobAbroad</h2>
                            <p style="margin:5px 0 0;font-size:14px">Subscription Payment Confirmation</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:35px">

                            <p>Hello <strong>{{ $payment->employer->name }}</strong>,</p>

                            <p>Your payment has been successfully verified. Your subscription plan is now active.</p>

                            <h3 style="margin-top:30px">Invoice</h3>

                            <table width="100%" cellpadding="6" cellspacing="0" style="border-collapse:collapse">

                                <tr style="background:#f3f4f6">
                                    <td><strong>Invoice ID</strong></td>
                                    <td>#{{ $payment->id }}</td>
                                </tr>

                                <tr>
                                    <td><strong>Plan</strong></td>
                                    <td>{{ $payment->plan->name }}</td>
                                </tr>

                                <tr>
                                    <td><strong>Amount</strong></td>
                                    <td>{{ number_format($payment->amount, 2) }}</td>
                                </tr>

                                <tr>
                                    <td><strong>Payment Method</strong></td>
                                    <td>{{ strtoupper($payment->method) }}</td>
                                </tr>

                                <tr>
                                    <td><strong>Reference</strong></td>
                                    <td>{{ $payment->reference }}</td>
                                </tr>

                                <tr>
                                    <td><strong>Date Verified</strong></td>
                                    <td>{{ $payment->verified_at?->format('M d, Y') }}</td>
                                </tr>

                            </table>

                            <p style="margin-top:30px">
                                You may now start posting job opportunities using your active subscription.
                            </p>

                            <p>
                                Best regards,<br>
                                <strong>JobAbroad Team</strong>
                            </p>

                        </td>
                    </tr>

                    <tr>
                        <td style="background:#f9fafb;padding:20px;text-align:center;font-size:12px;color:#6b7280">
                            © {{ date('Y') }} JobAbroad
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
