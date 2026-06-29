@component('mail.layout', [
    'siteName' => $siteName,
    'headline' => 'Thanks for getting in touch',
    'footerNote' => 'If you did not send this message, you can safely ignore this email.',
])
    <p style="margin:0 0 16px;font-size:16px;line-height:1.7;color:#3b4255;">
        Hi {{ $contactMessage->name }},
    </p>

    <p style="margin:0 0 16px;font-size:16px;line-height:1.7;color:#3b4255;">
        Thank you for reaching out through my portfolio. I&apos;ve received your message
        and will review it shortly — I typically respond within one business day.
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:24px 0;background:#f8f9fd;border:1px solid #e7e9f3;border-radius:12px;">
        <tr>
            <td style="padding:20px;">
                @if ($contactMessage->subject)
                    <p style="margin:0 0 8px;font-size:13px;color:#7b8195;text-transform:uppercase;letter-spacing:0.06em;">Subject</p>
                    <p style="margin:0 0 16px;font-size:15px;font-weight:600;color:#1f2430;">{{ $contactMessage->subject }}</p>
                @endif
                <p style="margin:0 0 8px;font-size:13px;color:#7b8195;text-transform:uppercase;letter-spacing:0.06em;">Your message</p>
                <p style="margin:0;font-size:15px;line-height:1.7;color:#3b4255;white-space:pre-line;">{{ $contactMessage->message }}</p>
            </td>
        </tr>
    </table>

    <p style="margin:0 0 16px;font-size:16px;line-height:1.7;color:#3b4255;">
        In the meantime, feel free to explore more of my work on the portfolio or connect with me on LinkedIn.
    </p>

    <p style="margin:0 0 24px;">
        <a href="{{ $frontendUrl }}" style="display:inline-block;padding:12px 22px;border-radius:10px;background:#6366f1;color:#ffffff;text-decoration:none;font-size:14px;font-weight:600;">
            Visit the portfolio
        </a>
    </p>

    <p style="margin:0;font-size:16px;line-height:1.7;color:#3b4255;">
        Best regards,<br>
        <strong>{{ $ownerName }}</strong>
    </p>
@endcomponent
