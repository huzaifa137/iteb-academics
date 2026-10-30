<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New website message</title>
</head>
<body style="margin:0;padding:24px;background:#f1f8f3;font-family:Arial,Helvetica,sans-serif;color:#33463b;line-height:1.6;">
    <div style="max-width:620px;margin:0 auto;background:#ffffff;border-radius:14px;overflow:hidden;border:1px solid #e5eae6;">
        <div style="background:#0b3d1a;color:#ffffff;padding:20px 26px;">
            <div style="font-size:12px;letter-spacing:.12em;text-transform:uppercase;color:#f1d97a;font-weight:bold;">ITEB website</div>
            <div style="font-size:20px;font-weight:bold;margin-top:4px;">New message from the contact form</div>
        </div>
        <div style="padding:26px;">
            @if ($reference)
                <p style="margin:0 0 18px;"><strong>Reference:</strong> {{ $reference }}</p>
            @endif
            <table style="width:100%;border-collapse:collapse;font-size:15px;">
                <tr><td style="padding:7px 0;width:110px;color:#5f7066;">Name</td><td style="padding:7px 0;"><strong>{{ $data['name'] }}</strong></td></tr>
                <tr><td style="padding:7px 0;color:#5f7066;">Email</td><td style="padding:7px 0;"><a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a></td></tr>
                @if (!empty($data['phone']))
                    <tr><td style="padding:7px 0;color:#5f7066;">Phone</td><td style="padding:7px 0;">{{ $data['phone'] }}</td></tr>
                @endif
                @if (!empty($data['school']))
                    <tr><td style="padding:7px 0;color:#5f7066;">School</td><td style="padding:7px 0;">{{ $data['school'] }}</td></tr>
                @endif
                <tr><td style="padding:7px 0;color:#5f7066;">Topic</td><td style="padding:7px 0;">{{ $data['topic_label'] }}</td></tr>
            </table>
            <p style="margin:22px 0 8px;color:#5f7066;">Message</p>
            <div style="border-left:4px solid #1e7a3d;background:#f1f8f3;padding:14px 18px;border-radius:0 10px 10px 0;white-space:pre-wrap;">{{ $data['message'] }}</div>
            <p style="margin:24px 0 0;font-size:13px;color:#8a978f;">Reply to this email to answer {{ $data['name'] }} directly.</p>
        </div>
    </div>
</body>
</html>
