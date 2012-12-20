<?php
//error_reporting(E_ALL ^ E_NOTICE);
class ASCIIArtist
{

    var $_version = "1.4";
    
    var $_errors = array();
        
    var $_replaceCharacters = array (
        1 => "天",
        2 => "唐",
        3 => "耀",
        4 => "星",
        5 => "冻",
        6 => "水",
        7 => "帮",
        8 => "魔",
        9 => "术"
    );
    var $_imageTypes = array (
        1 => "gif",
        2 => "jpeg",
        3 => "png"
    );
    var $_image = 0;
    
    var $_imageHeight = 0;
    
    var $_imageWidth = 0;
    
    var $_imageHTML = '';
   
    var $_imageCSS = '
        color               : #000000;
        background-color    : #FFFFFF;
        font-size           : 8px;
        font-family         : "Courier New", Courier, mono, 微软雅黑;
        line-height         : 5px;
        letter-spacing      : -1px;
    ';
    
    var $_errorCSS = '
        text-align          : center;
        color               : #000000;
        background-color    : #EFEFEF;
        font-size           : 11px;
        font-family         : Verdana, Arial, sans-serif;
        border-color        : #333333;
        border-style        : solid;
        border-width        : 1px;
        margin              : 4px;
        padding             : 4px;
    ';
    
    var $_lastRGB = array();

    var $_fontTagOpen = false;
    
	//返回十六进制颜色
    function _RGB2HEX($rgb)
    {
        return sprintf("%02X%02X%02X",$rgb["red"],$rgb["green"],$rgb["blue"]);
    }

	//渲染像素
    function _renderPixel2($mode, $x, $y, $p, $q, $fixedChar)
    {
        // RGB值
        $rgb = imagecolorsforindex($this->_image, imagecolorat($this->_image, $x, $y));
        $_SESSION[$x][$y]=imagecolorallocate($_SESSION[magic],$rgb["red"],$rgb["green"],$rgb["blue"]);
        $brightness = $rgb["red"] + $rgb["green"] + $rgb["blue"];
        $replaceCharacterNo = round($brightness / 100) + 1;
	$char=$this->_replaceCharacters[$replaceCharacterNo];
	$_SESSION[$p][$q] = $char;
    }
                                
    function _renderPixel($mode, $x, $y, $fixedChar)
    {
        // RGB值
        $rgb = imagecolorsforindex($this->_image, imagecolorat($this->_image, $x, $y));
        // 选择模式
        switch ($mode) {
            case 2:

                $brightness = $rgb["red"] + $rgb["green"] + $rgb["blue"];
                $replaceCharacterNo = round($brightness / 100) + 1;
                if ($this->_lastRGB == $rgb) {
                    $this->_imageHTML .= $this->_replaceCharacters[$replaceCharacterNo];
					
                } else {
                    
                    if ($this->_fontTagOpen) {
                        $this->_imageHTML .= "</font>";
                    }
                    
                    $this->_imageHTML .= "<font color=\"#".$this->_RGB2HEX($rgb)."\">".$this->_replaceCharacters[$replaceCharacterNo];
                    $this->_fontTagOpen = true;
                }
				
                break;
            case 3:
                if ($this->_lastRGB == $rgb) {
                    $this->_imageHTML .= $fixedChar;
                } else {
                    
                    if ($this->_fontTagOpen) {
                        $this->_imageHTML .= "</font>";
                    }
                    
                    $this->_imageHTML .= "<font color=\"#".$this->_RGB2HEX($rgb)."\">".$fixedChar;
                    $this->_fontTagOpen = true;
                }
                break;

        }
        
        $this->_lastRGB = $rgb;
    }
    
    function getVersion ()
    {
        return $this->_version;
    }
    
	
	//定义输出字CSS格式
    function setImageCSS ($css)
    {
        $this->_imageCSS = $css;
    }
    
	//错误时输出的CSS格式
    function setErrorCSS ($css)
    {
        $this->_errorCSS = $css;
    }
    
	//转换图像成字体
    function renderHTMLImage($mode = 1, $resolution = 2, $fixedChar = 'W')
    {
        $this->_imageHTML = '';
        
        if ($resolution < 1) {
            $resolution = 1;
        }
        for ($y = 1; $y <= $this->_imageHeight; $y += $resolution)
        {
            for ($x = 1; $x <= $this->_imageWidth; $x += $resolution)
            {
                $this->_renderPixel($mode, $x, $y, $fixedChar);
            }
            $this->_imageHTML .= "<br>\n";
            }
    }
	
	//渲染出图像效果
	function renderpic($mode = 1, $resolution = 2, $fixedChar = 'W')
	{
	    $font = "jkt.ttf";
            $q = 0; 
            $p = 0;
	    for ($y = 1,$h = 0; $y < 180,$h < 900; $y +=2,$h+=10)
            {
                $q+=2;
                for ($x = 1,$v = 0; $x < 180,$v < 900; $x +=2,$v+=10)
                {
                	$p+=2;
			$this->_renderPixel2($mode, $x, $y, $p, $q, $fixedChar);
			$text = $_SESSION[$p][$q];
			imageTTFText($_SESSION[magic],9,0,$v, $h,$_SESSION[$x][$y], $font ,$text);
		}
	    }
	}
 
	//输出ASCII格式图像
    function getHTMLImage()
    {
       return '<style type="text/css">'
                .'.asciiimage{'
                .$this->_imageCSS
                .'}</style>'
                .'<span class="asciiimage">'
                .$this->_imageHTML
                .'</span>';
    }
    

	//创建图像文件
    function setFile($filename)
    {
        if (!$imagesize = getimagesize($filename)) {
            $this->_errors[] = 'Cannot open "'.$filename.'" for reading.';
            return false;
        }
        
	//储存图像数据
        list($width,$height,$type) = $imagesize;
        switch ($type) {
            case 1:
            case 2:
            case 3:
                $imagefunction = "imagecreatefrom".$this->_imageTypes[$type];
                
                if (!function_exists($imagefunction) || !$this->_image = $imagefunction($filename)) {
                    $this->_errors[] = 'Unable to create images from '.$this->_imageTypes[$type].'. See http://de.php.net/manual/en/ref.image.php for more info.';
                    return false;
                }
                
                $this->_imageHeight = $height;
                $this->_imageWidth  = $width;
                
                break;
            default:
                $this->_errors[] = 'Cannot determine image type of "'.$filename.'".';
                return false;
        }
        
        return true;
    }
    
	//返回图像高度
    function getImageHeight()
    {
        return $this->_imageHeight;
    }
    
	//返回图像宽度
    function getImageWidth()
    {
        return $this->_imageWidth;
    }
    
    function setImageFile()
	{
		$_SESSION[magic]= imagecreatetruecolor(900, 900);
		$_SESSION[white] = imagecolorallocate($_SESSION[magic], 255, 255, 255);
		imagefill($_SESSION[magic], 11,11,$_SESSION[white]);
	}
    function getImageFile($name)
	{
        	$s = new SaeStorage();
        	ob_start();
		imagepng($_SESSION[magic]);
		$contents =  ob_get_contents();
		ob_end_clean();
		$url = $s->write ('pic' ,$name.'.png' , $contents );
		imagedestroy($_SESSION[magic]);
                return $url;
	}
}
?>
