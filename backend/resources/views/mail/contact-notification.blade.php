@component('mail.layout', [
    'siteName' => $siteName,
    'headline' => 'New contact form submission',
])
    <p style="margin:0 0 16px;font-size:16px;line-height:1.7;color:#3b4255;">
        Someone just submitted the contact form on your portfolio.
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 24px;background:#f8f9fd;border:1px solid #e7e9f3;border-radius:12px;">
        <tr>
            <td style="padding:20px;">
                <p style="margin:0 0 12px;font-size:15px;color:#3b4255;"><strong>Name:</strong> {{ $contactMessage->name }}</p>
                <p style="margin:0 0 12px;font-size:15px;color:#3b4255;"><strong>Email:</strong>
                    <a href="mailto:{{ $contactMessage->email }}" style="color:#6366f1;">{{ $contactMessage->email }}</a>
                </p>
                @if ($contactMessage->subject)
                    <p style="margin:0 0 12px;font-size:15px;color:#3b4255;"><strong>Subject:</strong> {{ $contactMessage->subject }}</p>
                @endif
                <p style="margin:0 0 8px;font-size:13px;color:#7b8195;text-transform:uppercase;letter-spacing:0.06em;">Message</p>
                <p style="margin:0;font-size:15px;line-height:1.7;color:#3b4255;white-space:pre-line;">{{ $contactMessage->message }}</p>
            </td>
        </tr>
    </table>

    <p style="margin:0 0 24px;">
        <a href="mailto:{{ $contactMessage->email }}" style="display:inline-block;padding:12px 22px;border-radius:10px;background:#6366f1;color:#ffffff;text-decoration:none;font-size:14px;font-weight:600;">
            Reply to {{ $contactMessage->name }}
        </a>
    </p>

    <p style="margin:0;font-size:13px;line-height:1.6;color:#7b8195;">
        Submitted {{ $contactMessage->created_at->format('M j, Y g:i A') }}
        @if ($contactMessage->ip_address)
            · IP {{ $contactMessage->ip_address }}
        @endif
        <br>
        You can also view this message in your admin dashboard under Messages.
    </p>
@endcomponent
