<?php
/* @var $this yii\web\View */
/* @var $comments common\models\Comment */
/* @var $activities common\models\Activity */
/* @var $numComments integer */
/* @var $accountName string */

use yii\helpers\Url;

$unsubscribeUrl = "https://agent.plugn.io/email/index";
$agentPortalUrl = "https://agent.plugn.io/app";
?>

<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<style type="text/css">
  #outlook a { padding: 0; }
  .ReadMsgBody { width: 100%; }
  .ExternalClass { width: 100%; }
  .ExternalClass * { line-height:100%; }
  body { margin: 0; padding: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
  table, td { border-collapse:collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
  img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
  p { display: block; margin: 13px 0; }
</style>
<!--[if !mso]><!-->
<style type="text/css">

      @import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);

  </style>
<style type="text/css">
  @media only screen and (max-width:480px) {
    @-ms-viewport { width:320px; }
    @viewport { width:320px; }
  }
</style>
<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700" rel="stylesheet" type="text/css">
<!--<![endif]-->
<!--[if mso]>
<xml>
  <o:OfficeDocumentSettings>
    <o:AllowPNG/>
    <o:PixelsPerInch>96</o:PixelsPerInch>
  </o:OfficeDocumentSettings>
</xml>
<![endif]-->
<style type="text/css">
  @media only screen and (min-width:480px) {
    .mj-column-per-100, * [aria-labelledby="mj-column-per-100"] { width:100%!important; }
.mj-column-per-50, * [aria-labelledby="mj-column-per-50"] { width:50%!important; }
  }
</style>
<style type="text/css">
    @media only screen and (max-width:480px) {
      .mj-hero-content {
        width: 100% !important;
      }
    }
  </style></head>
<body style="background: #eceff4;">
  <div style="background-color:#eceff4;"><!--[if mso | IE]>
      <table border="0" cellpadding="0" cellspacing="0" width="700" align="center" style="width:700px;">
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]--><div style="margin:0 auto;max-width:700px;background:white;"><table cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:white;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;font-size:0px;padding:20px 0px;padding-bottom:0px;padding-top:10px;"><!--[if mso | IE]>
      <table border="0" cellpadding="0" cellspacing="0"><tr><td style="vertical-align:top;width:700px;">
      <![endif]--><div aria-labelledby="mj-column-per-100" class="mj-column-per-100" style="vertical-align:top;display:inline-block;font-size:13px;text-align:left;width:100%;"><table cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-break:break-word;font-size:0px;padding:10px 25px;" align="center"><table cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;" align="center" border="0"><tbody><tr><td style="width:180px;"><a href="http://plugn.io" target="_blank"><img alt="" height="auto" src="<?= $message->embed(Url::to("@web/img/logo-trans.png", true)); ?>" style="border:none;display:block;outline:none;text-decoration:none;width:100%;height:auto;" width="180"></a></td></tr></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]>
      </td></tr></table>
      <![endif]--></td></tr></tbody></table></div><!--[if mso | IE]>
      </td></tr></table>
      <![endif]-->
      <!--[if mso | IE]>
      <table border="0" cellpadding="0" cellspacing="0" width="700" align="center" style="width:700px;">
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]--><div style="margin:0 auto;max-width:700px;background:#fcfcfc;"><table cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:#fcfcfc;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;font-size:0px;padding:20px 0px;padding-bottom:0px;"><!--[if mso | IE]>
      <table border="0" cellpadding="0" cellspacing="0"><tr><td style="vertical-align:top;width:700px;">
      <![endif]--><div aria-labelledby="mj-column-per-100" class="mj-column-per-100" style="vertical-align:top;display:inline-block;font-size:13px;text-align:left;width:100%;"><table cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-break:break-word;font-size:0px;padding:10px 25px;" align="center"><div style="cursor:auto;color:black;font-family:Helvetica Neue;font-size:24px;font-weight:200;line-height:22px;">
                <?= \Yii::t('frontend', 'You have {n,plural,=1{a new comment} other{# new comments}} on @{accountName}', ['n' => $numComments, 'accountName' => $accountName]); ?>
            </div></td></tr><tr><td style="word-break:break-word;font-size:0px;padding:8px 16px 10px;" align="center"><table cellpadding="0" cellspacing="0" style="border:none;border-radius:3px;" align="center" border="0"><tbody><tr><td style="background:#00a8ff;border-radius:3px;color:white;cursor:auto;" align="center" valign="middle" bgcolor="#00a8ff"><a href="<?= $agentPortalUrl ?>" style="display:inline-block;text-decoration:none;background:#00a8ff;border:1px solid #00a8ff;border-radius:3px;color:white;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;font-weight:400;padding:8px 16px 10px;" target="_blank">
                  View and Handle Comments
              </a></td></tr></tbody></table></td></tr><tr><td style="word-break:break-word;font-size:0px;padding:10px 25px;padding-top:20px;padding-bottom:0px;padding-right:0px;padding-left:0px;"><p style="font-size:1px;margin:0 auto;border-top:1px solid #f8f8f8;width:100%;"></p><!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" style="font-size:1px;margin:0 auto;border-top:1px solid #f8f8f8;width:100%;" width="700"><tr><td style="height:0;line-height:0;">&nbsp;</td></tr></table><![endif]--></td></tr></tbody></table></div><!--[if mso | IE]>
      </td></tr></table>
      <![endif]--></td></tr></tbody></table></div><!--[if mso | IE]>
      </td></tr></table>
      <![endif]-->
      <!--[if mso | IE]>
      <table border="0" cellpadding="0" cellspacing="0" width="700" align="center" style="width:700px;">
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]--><div style="margin:0 auto;max-width:700px;background:white;"><table cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:white;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;font-size:0px;padding:20px 0px;"><!--[if mso | IE]>
      <table border="0" cellpadding="0" cellspacing="0"><tr><td style="vertical-align:top;width:350px;">
      <![endif]--><div aria-labelledby="mj-column-per-50" class="mj-column-per-50" style="vertical-align:top;display:inline-block;font-size:13px;text-align:left;width:100%;"><table cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-break:break-word;font-size:0px;padding:10px 25px;padding-bottom:0px;" align="left"><div style="cursor:auto;color:#333;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:20px;line-height:22px;">
                Recent Comments
              </div></td></tr><tr><td style="word-break:break-word;font-size:0px;padding:10px 25px;" align="left"><ul style="display:inline-block;padding-left:20px;text-align:left;color:grey;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:22px;">
                  <?php
                  $i = 0;
                  foreach($comments as $comment){ $i++; ?>
                      <li><b>@<?= $comment['comment_by_username'].":</b> ".$comment['comment_text'] ?></li>
                  <?php
                    if($i == 5) break;
                    } ?>
                  <li><a href="<?= $agentPortalUrl ?>" style="text-decoration: none; color: inherit;">
                    more on <span style="border-bottom: solid 1px #b3bac1">Plugn.io</span>
                    </a>
                    </li></ul></td></tr></tbody></table></div><!--[if mso | IE]>
      </td><td style="vertical-align:top;width:350px;">
      <![endif]--><div aria-labelledby="mj-column-per-50" class="mj-column-per-50" style="vertical-align:top;display:inline-block;font-size:13px;text-align:left;width:100%;"><table cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-break:break-word;font-size:0px;padding:10px 25px;padding-bottom:0px;" align="left"><div style="cursor:auto;color:#333;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:20px;line-height:22px;">
                Recent Agent Activity
              </div></td></tr><tr><td style="word-break:break-word;font-size:0px;padding:10px 25px;" align="left"><ul style="display:inline-block;padding-left:20px;text-align:left;color:grey;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:22px;">
                  <?php foreach($activities as $activity){ ?>
                      <li><b><?= $activity['agent']['agent_name'].":</b> ".$activity['activity_detail'] ?></li>
                  <?php } ?>
                  <li><a href="<?= $agentPortalUrl ?>" style="text-decoration: none; color: inherit;">
                    more on <span style="border-bottom: solid 1px #b3bac1">Plugn.io</span>
                    </a>
                    </li></ul></td></tr></tbody></table></div><!--[if mso | IE]>
      </td></tr></table>
      <![endif]--></td></tr></tbody></table></div><!--[if mso | IE]>
      </td></tr></table>
      <![endif]-->
      <!--[if mso | IE]>
      <table border="0" cellpadding="0" cellspacing="0" width="700" align="center" style="width:700px;">
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]--><div style="margin:0 auto;max-width:700px;"><table cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;font-size:0px;padding:20px 0px 0px;"><!--[if mso | IE]>
      <table border="0" cellpadding="0" cellspacing="0"><tr><td style="vertical-align:top;width:700px;">
      <![endif]--><div aria-labelledby="mj-column-per-100" class="mj-column-per-100" style="vertical-align:top;display:inline-block;font-size:13px;text-align:left;width:100%;"><table cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-break:break-word;font-size:0px;padding:0px;" align="center"><div style="cursor:auto;color:#6b7a85;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;">
                      Don't like these emails?
                      <a href="<?= $unsubscribeUrl ?>" style="text-decoration: none; color: inherit;">
                          <span style="border-bottom: solid 1px #b3bac1">Unsubscribe</span>
                      </a>
                  </div></td></tr></tbody></table></div><!--[if mso | IE]>
      </td></tr></table>
      <![endif]--></td></tr></tbody></table></div><!--[if mso | IE]>
      </td></tr></table>
      <![endif]-->
      <!--[if mso | IE]>
      <table border="0" cellpadding="0" cellspacing="0" width="700" align="center" style="width:700px;">
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]--><div style="margin:0 auto;max-width:700px;"><table cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;font-size:0px;padding:20px 0px 0px;"><!--[if mso | IE]>
      <table border="0" cellpadding="0" cellspacing="0"><tr><td style="vertical-align:top;width:700px;">
      <![endif]--><div aria-labelledby="mj-column-per-100" class="mj-column-per-100" style="vertical-align:top;display:inline-block;font-size:13px;text-align:left;width:100%;"><table cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-break:break-word;font-size:0px;padding:0px;" align="center"><div style="cursor:auto;color:#6b7a85;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:12px;line-height:22px;">
                      A
                      <a href="http://bawes.net" style="text-decoration: none; color: inherit;">
                          <span style="border-bottom: solid 1px #b3bac1">BAWES - Built Awesome</span>
                      </a>
                      Product.
                  </div></td></tr></tbody></table></div><!--[if mso | IE]>
      </td></tr></table>
      <![endif]--></td></tr></tbody></table></div><!--[if mso | IE]>
      </td></tr></table>
      <![endif]-->
      <!--[if mso | IE]>
      <table border="0" cellpadding="0" cellspacing="0" width="700" align="center" style="width:700px;">
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]--><div style="margin:0 auto;max-width:700px;"><table cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;font-size:0px;padding:20px 0px;padding-bottom:24px;padding-top:0px;"></td></tr></tbody></table></div><!--[if mso | IE]>
      </td></tr></table>
      <![endif]--></div>
</body>
</html>
