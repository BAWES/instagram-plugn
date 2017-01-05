<?php
return [
    'adminEmail' => 'hello@plugn.io',
    'supportEmail' => 'hello@plugn.io',
    'instagram.numberOfPastPostsToCrawl' => 20,
    'instagram.endpointHourlyRateLimit' => 60, //30 per hour for sandbox, 60 per hour for live
    'user.passwordResetTokenExpire' => 3600,
    'user.rememberMeDuration' => 3600 * 24 * 30,

    // 2Checkout Is Sandbox or not?
    '2co.isSandbox' => true,

    // 2CO Live Info
    '2co.live.environment' => 'production',
    '2co.live.privateKey' => '1921AC14-0E8C-46CB-8FFE-943E039CD6FE',
    '2co.live.publishableKey' => '88992314-9CE9-4E91-96B8-66FB6845F51A',
    '2co.live.sellerId' => '103110406',
    '2co.live.username' => 'plugnapi',
    '2co.live.password' => 'WmdnN3rAm1O!',
    '2co.live.verifySSL' => true,

    // 2CO Sandbox Info
    '2co.sandbox.environment' => 'sandbox',
    '2co.sandbox.privateKey' => '032DFCA7-32DE-4EE2-95B0-C5F892C4198B',
    '2co.sandbox.publishableKey' => '9DA7066C-F76D-4BFB-AEAF-0009E4D664BC',
    '2co.sandbox.sellerId' => '901335342',
    '2co.sandbox.username' => 'plugnapi',
    '2co.sandbox.password' => 'WmdnN3rAm1O!',
    '2co.sandbox.verifySSL' => false,

];
