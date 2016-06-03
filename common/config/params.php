<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'instagram.numberOfPastPostsToCrawl' => 20,
    'instagram.globalHourlyRateLimit' => 500, //500 per hour for sandbox, 5000 per hour for live
    'instagram.endpointHourlyRateLimit' => 30, //30 per hour for sandbox, 60 per hour for live
    'user.passwordResetTokenExpire' => 3600,
    'user.rememberMeDuration' => 3600 * 24 * 30,
];
