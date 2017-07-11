<?php

// class Captcha {

//     private $width = 80, $height = 31, $codenum = 4;
//     public $checkcode;     //产生的验证码  
//     private $checkimage;    //验证码图片   
//     private $disturbColor = ''; //干扰像素  
//     private $session_flag = 'captcha'; //存到session中的索引  

//     function __construct() {
//     }

//     function config($width = '80', $height = '31', $codenum = '4', $session_flag = 'captcha') {
//         $this->width = $width;
//         $this->height = $height;
//         $this->codenum = $codenum;
//         $this->session_flag = $session_flag;
//     }

//     function create() {
//         //输出头  
//         $this->outFileHeader();
//         //产生验证码  
//         $this->createCode();

//         //产生图片  
//         $this->createImage();
//         //设置干扰像素  
//         $this->setDisturbColor();
//         //往图片上写验证码  
//         $this->writeCheckCodeToImage();
//         imagepng($this->checkimage);
//         imagedestroy($this->checkimage);
//     }

//     private function outFileHeader() {
//         header("Content-type: image/png");
//     }

//     private function createCode() {
//         $this->checkcode = strtoupper(substr(md5(rand()), 0, $this->codenum));
//     }

//     /**
//      * 产生验证码图片 
//      */
//     private function createImage() {
//         $this->checkimage = imagecreate($this->width, $this->height);
//         $back = imagecolorallocate($this->checkimage, 255, 255, 255);
//         $border = imagecolorallocate($this->checkimage, 0, 0, 0);
//         imagefilledrectangle($this->checkimage, 0, 0, $this->width - 1, $this->height - 1, $back); // 白色底  
//         imagerectangle($this->checkimage, 0, 0, $this->width - 1, $this->height - 1, $border);   // 黑色边框  
//     }

//     /**
//      * 设置图片的干扰像素  
//      */
//     private function setDisturbColor() {
//         for ($i = 0; $i <= 200; $i++) {
//             $this->disturbColor = imagecolorallocate($this->checkimage, rand(0, 255), rand(0, 255), rand(0, 255));
//             imagesetpixel($this->checkimage, rand(2, 128), rand(2, 38), $this->disturbColor);
//         }
//     }

//     /**
//      * 
//      * 在验证码图片上逐个画上验证码 
//      * 
//      */
//     private function writeCheckCodeToImage() {
//         for ($i = 0; $i < $this->codenum; $i++) {
//             $bg_color = imagecolorallocate($this->checkimage, rand(0, 255), rand(0, 128), rand(0, 255));
//             $x = floor($this->width / $this->codenum) * $i;
//             $y = rand(0, $this->height - 15);
//             imagechar($this->checkimage, rand(5, 8), $x + 5, $y, $this->checkcode[$i], $bg_color);
//         }
//     }

//     function __destruct() {
//         unset($this->width, $this->height, $this->codenum, $this->session_flag);
//     }

// }

class Captcha {
 private $charset = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789';//随机因子
 private $code;//验证码
 private $codelen = 4;//验证码长度
 private $width = 130;//宽度
 private $height = 50;//高度
 private $img;//图形资源句柄
 private $font;//指定的字体
 private $fontsize = 20;//指定字体大小
 private $fontcolor;//指定字体颜色
 //构造方法初始化
 public function __construct() {
  $this->font = APPPATH.'../front/elephant.ttf';//注意字体路径要写对，否则显示不了图片
  // error_log($this->font,3,'error.log');
 }
 //生成随机码
 private function createCode() {
  $_len = strlen($this->charset)-1;
  for ($i=0;$i<$this->codelen;$i++) {
   $this->code .= $this->charset[mt_rand(0,$_len)];
  }
 }
 //生成背景
 private function createBg() {
  $this->img = imagecreatetruecolor($this->width, $this->height);
  $color = imagecolorallocate($this->img, mt_rand(157,255), mt_rand(157,255), mt_rand(157,255));
  imagefilledrectangle($this->img,0,$this->height,$this->width,0,$color);
 }
 //生成文字
 private function createFont() {
  $_x = $this->width / $this->codelen;
  for ($i=0;$i<$this->codelen;$i++) {
   $this->fontcolor = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
   imagettftext($this->img,$this->fontsize,mt_rand(-30,30),$_x*$i+mt_rand(1,5),$this->height / 1.4,$this->fontcolor,$this->font,$this->code[$i]);
  }
 }
 //生成线条、雪花
 private function createLine() {
  //线条
  for ($i=0;$i<6;$i++) {
   $color = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
   imageline($this->img,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$color);
  }
  //雪花
  for ($i=0;$i<100;$i++) {
   $color = imagecolorallocate($this->img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
   imagestring($this->img,mt_rand(1,5),mt_rand(0,$this->width),mt_rand(0,$this->height),'*',$color);
  }
 }
 //输出
 private function outPut() {
  header('Content-type:image/png');
  imagepng($this->img);
  imagedestroy($this->img);
 }
 //对外生成
 public function doimg() {
  $this->createBg();
  $this->createCode();
  $this->createLine();
  $this->createFont();
  $this->outPut();
 }
 //获取验证码
 public function getCode() {
  return strtolower($this->code);
 }
}
