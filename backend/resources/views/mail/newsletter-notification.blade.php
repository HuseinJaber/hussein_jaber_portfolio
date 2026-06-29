@component('mail.layout', [
    'siteName' => $siteName,
    'headline' => 'New newsletter subscriber',
])
    <p style="margin:0 0 16px;font-size:16px;line-height:1.7;color:#3b4255;">
        A new visitor subscribed to your newsletter.
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 24px;background:#f8f9fd;border:1px solid #e7e9f3;border-radius:12px;">
        <tr>
            <td style="padding:20px;">
                <p style="margin:0 0 8px;font-size:13px;color:#7b8195;text-transform:uppercase;letter-spacing:0.06em;">Email</p>
                <p style="margin:0;font-size:18px;font-weight:600;color:#1f2430;">
                    <a href="mailto:{{ $subscriber->email }}" style="color:#6366f1;text-decoration:none;">{{ $subscriber->email }}</a>
                </p>
            </td>
        </tr>
    </table>

    <p style="margin:0;font-size:13px;line-height:1.6;color:#7b8195;">
        Subscribed {{ $subscriber->created_at->format('M j, Y g:i A') }}
        @if ($subscriber->ip_address)
            · IP {{ $subscriber->ip_address }}
        @endif
        <br>
        View all subscribers in your admin dashboard under Newsletter.
    </p>
@endcomponent
