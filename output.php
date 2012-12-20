<?php

session_start();
require ('ASCIIArtist.php');
include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );

$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token']);
$uid_get = $c->get_uid();
$uid = $uid_get['uid'];
$msg = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
$id = $msg['id'];
?>


<html>
<style>
body {
	margin:0 auto;
	margin-top:30px;
        background-color: #fff;
    }
</style>
<head>
    <title>魔术头像</title>
</head>
<body>
<div align="center">
<?php

//图像文件载入
if (empty($_REQUEST['image'])) {
    echo '<p style="font-family: Verdana, sans-serif; font-size: 11px;">请上传图片！！！<a href="weibolist.php">返回</a></p>';
} else {

    $ASCIIArtist = new ASCIIArtist;

	if (!$ASCIIArtist->setFile($_REQUEST['image'])) {

        echo '无法取得图片';
    } else {

		$ASCIIArtist->_replaceCharacters=
		array(
		1 =>$_REQUEST['ch1'],
		2 =>$_REQUEST['ch2'],
		3 =>$_REQUEST['ch3'],
		4 =>$_REQUEST['ch4'],
		5 =>$_REQUEST['ch5'],
		6 =>$_REQUEST['ch6'],
		7 =>$_REQUEST['ch7'],
		8 =>$_REQUEST['ch8'],
		9 =>$_REQUEST['ch9']
		);
        if ($_REQUEST['flip_h']) {
           $flip_h = true;
        } else {
            $flip_h = false;
        }
        
        if ($_REQUEST['flip_v']) {
            $flip_v = true;
        } else {
            $flip_v = false;
        }
		
		// 创建画布
        $ASCIIArtist->setImageFile();
		
        // 设置CSS效果
        $ASCIIArtist->setImageCSS('
            color           : '.$_REQUEST['color'].';
            background-color: transparent;
            font-size       : '.$_REQUEST['font-size'].'px;
            font-family     : "Courier New", Courier, mono;
            line-height     : '.$_REQUEST['line-height']."px;
            letter-spacing  : ".$_REQUEST['letter-spacing'].'px;
        ');
        
        // 使图像转换成HTML        
        $ASCIIArtist->renderHTMLImage($_REQUEST['mode'], $_REQUEST['resolution'], $_REQUEST['fixed_char'], $flip_h, $flip_v);
		
	$ASCIIArtist->renderpic();
		
        // 输出HTML成像
        echo $ASCIIArtist->getHTMLImage();
		
		// 生成画布
        $new_pic = $ASCIIArtist->getImageFile($id);
        }
}
if( isset($_REQUEST['weibo']) ) {
	$ret = $c ->upload( $_REQUEST['weibo'] , $new_pic );	//发送微博
	if ( isset($ret['error_code']) && $ret['error_code'] > 0 ) {
		echo "<p>发送失败，错误：{$ret['error_code']}:{$ret['error']}</p>";
	} else {
		echo "发送成功";
	}
}
echo "<script>alert('已发送，快到你微博上看看！')</script>";
?>
<!--
<form action="" method="POST">
        <table width="400" border="0" cellpadding="1" cellspacing="1">
        <tr>
                                
                <td><textarea name="weibo" rows="3" cols="60" onpropertychange="if(this.scrollHeight>60) this.style.posHeight=this.scrollHeight+5">test<?php  $n=rand(2,4);for($i=1;$i<$n;$i++){ echo '!';} ?>!![嘻嘻] http://magicpic.sinaapp.com</textarea>
		</td>
        </tr>
        <tr>
        	<td><input class="inputsubmit" type="submit"  value=" 生成图片并发送到微波 ">
                </td>
          </tr>
        </table>
</form>-->
</div>
</body>
</html>
