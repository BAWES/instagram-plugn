<?php
/* @var $this yii\web\View */
/* @var $agent common\models\Agent */

use yii\helpers\Url;
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
  @media only screen and (max-width:480px) {
    @-ms-viewport { width:320px; }
    @viewport { width:320px; }
  }
</style>
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
.mj-column-px-270, * [aria-labelledby="mj-column-px-270"] { width:270px!important; }
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
      <![endif]--><div style="margin:0 auto;max-width:700px;"><table cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;font-size:0px;padding:20px 0px;padding-bottom:10px;padding-top:10px;"><!--[if mso | IE]>
      <table border="0" cellpadding="0" cellspacing="0"><tr><td style="vertical-align:top;width:270px;">
      <![endif]--><div aria-labelledby="mj-column-px-270" class="mj-column-px-270" style="vertical-align:top;display:inline-block;font-size:13px;text-align:left;width:100%;"><table cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-break:break-word;font-size:0px;padding:10px 25px;" align="center"><table cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;" align="center" border="0"><tbody><tr><td style="width:180px;"><img alt="" height="auto" src="<?= $message->embed(Url::to("@web/img/logo-trans.png", true)); ?>" style="border:none;display:block;outline:none;text-decoration:none;width:100%;height:auto;" width="180"></td></tr></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]>
      </td></tr></table>
      <![endif]--></td></tr></tbody></table></div><!--[if mso | IE]>
      </td></tr></table>
      <![endif]-->
      <!--[if mso | IE]>
      <table border="0" cellpadding="0" cellspacing="0" width="700" align="center" style="width:700px;">
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]--><div style="margin:0 auto;max-width:700px;background:#d8e2e7;"><table cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:#d8e2e7;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;font-size:0px;padding:1px;"><!--[if mso | IE]>
      <table border="0" cellpadding="0" cellspacing="0"><tr><td style="vertical-align:top;width:700px;">
      <![endif]--><div aria-labelledby="mj-column-per-100" class="mj-column-per-100" style="vertical-align:top;display:inline-block;font-size:13px;text-align:left;width:100%;"><table cellpadding="0" cellspacing="0" style="background:white;" width="100%" border="0"><tbody><tr><td style="word-break:break-word;font-size:0px;padding:30px 30px 16px;" align="left"><div style="cursor:auto;color:#000000;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:17px;line-height:22px;">
                        Hello <?= $agent->agent_name ?>,
                    </div></td></tr><tr><td style="word-break:break-word;font-size:0px;padding:0px 30px 6px;" align="left"><div style="cursor:auto;color:#000000;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:22px;">
                        Please confirm your email address by clicking the link below.
                    </div></td></tr><tr><td style="word-break:break-word;font-size:0px;padding:0px 30px 6px;" align="left"><div style="cursor:auto;color:#000000;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:22px;">
                        We may need to send you critical information about our service and it is important that we have an accurate email address.
                    </div></td></tr><tr><td style="word-break:break-word;font-size:0px;padding:8px 16px 10px;padding-bottom:16px;padding-right:30px;padding-left:30px;" align="left"><table cellpadding="0" cellspacing="0" style="border:none;border-radius:25px;" align="left" border="0"><tbody><tr><td style="background:#00a8ff;border-radius:25px;color:white;cursor:auto;" align="center" valign="middle" bgcolor="#00a8ff"><a href="<?= $verificationUrl ?>" style="display:inline-block;text-decoration:none;background:#00a8ff;border:1px solid #00a8ff;border-radius:25px;color:white;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;font-weight:400;padding:8px 16px 10px;" target="_blank">
                        Confirm E-Mail Adress
                    </a></td></tr></tbody></table></td></tr><tr><td style="word-break:break-word;font-size:0px;padding:0px 30px 30px 30px;" align="left"><div style="cursor:auto;color:#000000;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:22px;">
                        — Plugn Team
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
      <![endif]--><div aria-labelledby="mj-column-per-100" class="mj-column-per-100" style="vertical-align:top;display:inline-block;font-size:13px;text-align:left;width:100%;"><table cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-break:break-word;font-size:0px;padding:0px;" align="center"><div style="cursor:auto;color:#6b7a85;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:22px;">
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
