<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? $siteName }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f5fb;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#1f2430;">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f4f5fb;padding:32px 16px;">
    <tr>
        <td align="center">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:600px;background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #e7e9f3;">
                <tr>
                    <td style="padding:28px 32px;background:linear-gradient(135deg,#6366f1 0%,#a855f7 100%);color:#ffffff;">
                        <p style="margin:0;font-size:13px;letter-spacing:0.08em;text-transform:uppercase;opacity:0.9;">{{ $siteName }}</p>
                        @isset($headline)
                            <h1 style="margin:12px 0 0;font-size:24px;line-height:1.3;font-weight:700;">{{ $headline }}</h1>
                        @endisset
                    </td>
                </tr>
                <tr>
                    <td style="padding:32px;">
                        {{ $slot }}
                    </td>
                </tr>
                <tr>
                    <td style="padding:20px 32px 28px;border-top:1px solid #eef0f7;background:#fafbff;">
                        <p style="margin:0;font-size:12px;line-height:1.6;color:#7b8195;">
                            © {{ date('Y') }} {{ $siteName }}. This message was sent from your portfolio website.
                            @if (!empty($footerNote))
                                <br>{{ $footerNote }}
                            @endif
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
