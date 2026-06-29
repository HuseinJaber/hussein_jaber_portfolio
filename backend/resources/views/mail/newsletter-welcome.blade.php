@component('mail.layout', [
    'siteName' => $siteName,
    'headline' => 'Welcome to the newsletter',
    'footerNote' => 'You received this because you subscribed on the portfolio website.',
])
    <p style="margin:0 0 16px;font-size:16px;line-height:1.7;color:#3b4255;">
        Hi there,
    </p>

    <p style="margin:0 0 16px;font-size:16px;line-height:1.7;color:#3b4255;">
        Thanks for subscribing to updates from <strong>{{ $ownerName }}</strong>. You&apos;ll be among the first to hear about new projects, articles and availability for freelance or contract work.
    </p>

    <p style="margin:0 0 16px;font-size:16px;line-height:1.7;color:#3b4255;">
        I keep emails focused and infrequent — no spam, just useful highlights when there&apos;s something worth sharing.
    </p>

    <p style="margin:0 0 24px;">
        <a href="{{ $frontendUrl }}" style="display:inline-block;padding:12px 22px;border-radius:10px;background:#6366f1;color:#ffffff;text-decoration:none;font-size:14px;font-weight:600;">
            Explore the portfolio
        </a>
    </p>

    <p style="margin:0;font-size:16px;line-height:1.7;color:#3b4255;">
        Glad to have you here,<br>
        <strong>{{ $ownerName }}</strong>
    </p>
@endcomponent
