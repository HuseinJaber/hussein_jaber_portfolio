@component('mail.layout', [
    'siteName' => $siteName,
    'headline' => 'Reply from '.$ownerName,
    'footerNote' => 'You can reply directly to this email to continue the conversation.',
])
    <p style="margin:0 0 16px;font-size:16px;line-height:1.7;color:#3b4255;">
        Hi {{ $contactMessage->name }},
    </p>

    <div style="margin:0 0 24px;font-size:16px;line-height:1.7;color:#3b4255;white-space:pre-line;">{{ $replyBody }}</div>

    @if ($contactMessage->subject || $contactMessage->message)
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 24px;background:#f8f9fd;border:1px solid #e7e9f3;border-radius:12px;">
            <tr>
                <td style="padding:20px;">
                    <p style="margin:0 0 12px;font-size:13px;color:#7b8195;text-transform:uppercase;letter-spacing:0.06em;">Your original message</p>
                    @if ($contactMessage->subject)
                        <p style="margin:0 0 8px;font-size:15px;font-weight:600;color:#1f2430;">{{ $contactMessage->subject }}</p>
                    @endif
                    <p style="margin:0;font-size:14px;line-height:1.7;color:#5c6370;white-space:pre-line;">{{ $contactMessage->message }}</p>
                </td>
            </tr>
        </table>
    @endif

    <p style="margin:0;font-size:16px;line-height:1.7;color:#3b4255;">
        Best regards,<br>
        <strong>{{ $ownerName }}</strong>
    </p>
@endcomponent
