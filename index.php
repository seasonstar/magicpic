<?php
session_start();

include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );

$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );

$code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>魔术头像</title>
</head>

<body>
<div>
  <div style="background:url(./download.png) no-repeat;width:700px;height:500px;margin:0 auto">
    </div>
    <div style="text-align:center">
        <p><a href="<?=$code_url?>"><img src="weibo_login.png" title="点击进入授权页面" alt="点击进入授权页面" border="0" /></a></p>
        <font size="1">开发者：<a href="http://weibo.com/seasonxing">唐耀星SeasonXIng</a></font>
    </div>
</div>    

</body>
</html>
