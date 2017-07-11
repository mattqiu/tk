<?php
/*
 * @desc 登陆界面生成图片验证码
 * @author JacksonZheng
 * @date 2017-06-02
 */
class ImgCaptcha {
 //private $charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';//随机因子
 private $charset = '012345678901234567890123456789';//随机因子
 private $code;//验证码
 private $codelen = 4;//验证码长度
 private $width = 140;//宽度
 private $height = 40;//高度
 private $img;//图形资源句柄
 private $font;//指定的字体
 private $fontsize = 18;//指定字体大小
 private $fontcolor;//指定字体颜色
 
 //构造方法初始化
 public function __construct() {
       $this->font = APPPATH.'../front/elephant.ttf';//注意字体路径要写对，否则显示不了图片
 }
 
 //生成随机码
 private function createCode() {
         $vcode = "";
         $str = array();
        for($i = 0;$i < 4;$i++){
           $str[$i] = $this->charset[mt_rand(0,29)];
           $vcode .= $str[$i];
        }
      $this->code = $str;  
 }
 
 //生成背景
 private function createBg() {
        $im = imagecreatetruecolor($this->width,$this->height);
        $white = imagecolorallocate($im,255,255,255); //第一次调用设置背景色
        $black = imagecolorallocate($im,229,229,229); //边框颜色
        imagefilledrectangle($im,0,0,$this->width,$this->height,$white); //画一矩形填充
        imagerectangle($im,0,0,$this->width-1,$this->height-1,$black); //画一矩形框
        //生成雪花背景
        for($i = 1;$i < 400;$i++){
           $x = mt_rand(1,$this->width-9);
           $y = mt_rand(1,$this->height-9);
          // $color = imagecolorallocate($im,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
           $color = imagecolorallocate($im,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
           imagechar($im,1,$x,$y,"*##",$color);
        }
        $this->img = $im;
    
       
 }
 
 
 //生成干扰线条
 private function createLine() {
     
  for ($i=0;$i<6;$i++) {
   $color = imagecolorallocate($this->img,mt_rand(0,225),mt_rand(0,150),mt_rand(0,225));
   imageline($this->img,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$color);
  }

 }
 
 //将验证码写入图案
 private function createFont() {
      
        $_x = $this->width / $this->codelen;
        for ($i=0;$i<$this->codelen;$i++) {
        $this->fontcolor = imagecolorallocate($this->img,mt_rand(0,225),mt_rand(0,150),mt_rand(0,225));
        imagettftext($this->img,$this->fontsize,mt_rand(-30,30),$_x*$i+mt_rand(1,5),$this->height / 1.4,$this->fontcolor,$this->font,$this->code[$i]);
       }
 }
 
      private function setDisturbColor() {
          
         for ($i = 0; $i <= 200; $i++) {
             $this->disturbColor = imagecolorallocate($this->img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
             imagesetpixel($this->img, mt_rand(2, 128), mt_rand(2, 38), $this->disturbColor);
         }
     }
     
 
 //输出
 private function outPut() {
     
  header('Content-type:image/png');
  imagepng($this->img);
  imagedestroy($this->img);
 }
 
 //生成图片
 public  function doimg() {
  $this->createBg();
  $this->createCode();
 //$this->createLine();
  $this->createFont();
  $this->setDisturbColor();
  $this->outPut();
 }
 
    //获取验证码
    public function getCode() {
        return strtolower(implode("",$this->code));
     }
}