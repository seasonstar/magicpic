<?php
session_start();

include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );

$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
$uid_get = $c->get_uid();
$uid = $uid_get['uid'];
$msg = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
//$pic = $msg['avatar_large'];// 获取180px头像地址
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<title>魔术头像</title>
</head>

<body>
<div>
        <img src="<?php echo $msg['avatar_large'] ?>" >
        <h2>Hi，<?=$msg['screen_name']?> <br>试试你的头像会变成什么?</h2>
	<form action="output.php" method="POST">
        <table width="400" border="1" cellpadding="1" cellspacing="1">
		<table>
		<tr>
			<td><font color="#000">注意咯：请输入你要转换成<font color="#FF6633">中文</font>字体，前5个字体尽量要字体笔画较多的，</br>效果会更好，下面字体输出颜色<font color="#FF6633">由深到浅</font>，请自行把握哦 o(∩_∩)o ~~</font>
			</td>
		</tr>
		</table>
		<table>
		<tr>
			<td>
			<input type="text" size="2" maxlength="1" name="ch1" value="酷">
			</td>
			<td>
				<input type="text" size="2" maxlength="1" name="ch2" value="魔">
			</td>
			<td>
				<input type="text" size="2" maxlength="1" name="ch3" value="术">
			</td>
			<td>
				<input type="text" size="2" maxlength="1" name="ch4" value="头">
			</td>
			<td>
				<input type="text" size="2" maxlength="1" name="ch5" value="像">
			</td>
			<td>
				<input type="text" size="2" maxlength="1" name="ch6" value="魔">
			</td>
			<td>
				<input type="text" size="2" maxlength="1" name="ch7" value="术">
			</td>
			<td>
				<input type="text" size="2" maxlength="1" name="ch8" value="头">
			</td>
			<td>
				<input type="text" size="2" maxlength="1" name="ch9" value="像">
			</td>
			<td>
				<input type="hidden" name="flip_h" value="true">
			</td>
			<td>
				<input type="hidden" name="flip_v" value="true">
			</td>
		</tr>
		</table>
        <tr>

                <td><textarea name="weibo" rows="3" cols="60" onpropertychange="if(this.scrollHeight>60) this.style.posHeight=this.scrollHeight+5">这字体拼图有点意思<?php  $n=rand(2,4);for($i=1;$i<$n;$i++){ echo '!';} ?>!![嘻嘻] http://magicpic.season.im</textarea>
		</td>
        </tr>
		<tr><br>
                        <td>
                        	<input class="inputsubmit" type="Submit"  value=" 生成字体拼图 ">
                        </td>
			<td>
                          <input type="hidden" name="image" value="<?php echo $msg['avatar_large'] ?>">
                        </td>
                        <td>
                            	<input type="hidden" name="resolution" value="3">
                        </td>
                        <td>
                                <input type="hidden" name="mode" value="2">
                        </td>
                        <td>
                        	<input type="hidden" name="font-size" value="11" size="3" maxlength="3">
                        </td>
                        <td>
                                <input type="hidden" name="line-height" value="11" size="3" maxlength="3">
                        </td>
                        <td>
                                <input type="hidden" name="letter-spacing" value="0" size="3" maxlength="3">
                        </td>
                        <td>
                                <input type="hidden" name="fixed_char" value="W" size="3" maxlength="1">
                        </td>
                </tr>

</table>
</form>

</body>
</html>
