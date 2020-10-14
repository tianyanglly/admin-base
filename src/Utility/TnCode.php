<?php

namespace AdminBase\Utility;


/**
 * 滑动验证码
 * Class TnCodeUtility
 * @package App\Admin\Utility
 */
class TnCode
{
    //容错象素 越大体验越好，越小破解难道越高
    const FAULT = 5;
    const SESSION_OFFSET = 'offset';
    const SESSION_VERIFY = 'verify_check';

    private $im = null;
    private $im_fullbg = null;
    private $im_bg = null;
    private $im_slide = null;
    private $bg_width = 240;
    private $bg_height = 150;
    private $mark_width = 50;
    private $mark_height = 50;
    private $_x = 0;
    private $_y = 0;
    private $rootPath;

    public function __construct($rootPath = '')
    {
        $rootPath == '' && $this->rootPath = public_path();
    }

    public function make($num = 18)
    {
        $this->_init($num);
        $this->_createSlide();
        $this->_createBg();
        $this->_merge();
        $this->_imgout();
        $this->_destroy();
    }

    private function _init($num)
    {
        $bg = random_int(1, $num);
        $file_bg = $this->rootPath . '/verify/bg/' . $bg . '.png';
        $this->im_fullbg = imagecreatefrompng($file_bg);
        $this->im_bg = imagecreatetruecolor($this->bg_width, $this->bg_height);
        imagecopy($this->im_bg, $this->im_fullbg, 0, 0, 0, 0, $this->bg_width, $this->bg_height);
        $this->im_slide = imagecreatetruecolor($this->mark_width, $this->bg_height);
        $this->_x = random_int(50, $this->bg_width - $this->mark_width - 1);

        session([self::SESSION_OFFSET => $this->_x]);
        $this->_y = random_int(0, $this->bg_height - $this->mark_height - 1);
    }

    private function _destroy()
    {
        imagedestroy($this->im);
        imagedestroy($this->im_fullbg);
        imagedestroy($this->im_bg);
        imagedestroy($this->im_slide);
    }

    private function _imgout()
    {
        if (function_exists('imagewebp')) {//优先webp格式，超高压缩率
            $type = 'webp';
            $quality = 40;//图片质量 0-100
        } else {
            $type = 'png';
            $quality = 7;//图片质量 0-9
        }
        header('Content-Type: image/' . $type);
        $func = "image" . $type;
        $func($this->im, null, $quality);
    }

    private function _merge()
    {
        $this->im = imagecreatetruecolor($this->bg_width, $this->bg_height * 3);
        imagecopy($this->im, $this->im_bg, 0, 0, 0, 0, $this->bg_width, $this->bg_height);
        imagecopy($this->im, $this->im_slide, 0, $this->bg_height, 0, 0, $this->mark_width, $this->bg_height);
        imagecopy($this->im, $this->im_fullbg, 0, $this->bg_height * 2, 0, 0, $this->bg_width, $this->bg_height);
        imagecolortransparent($this->im, 0);
    }

    private function _createBg()
    {
        $file_mark = $this->rootPath . '/verify/img/mark.png';
        $im = imagecreatefrompng($file_mark);
        header('Content-Type: image/png');
        imagecolortransparent($im, 0);
        imagecopy($this->im_bg, $im, $this->_x, $this->_y, 0, 0, $this->mark_width, $this->mark_height);
        imagedestroy($im);
    }

    private function _createSlide()
    {
        $file_mark = $this->rootPath . '/verify/img/mark2.png';
        $img_mark = imagecreatefrompng($file_mark);
        imagecopy($this->im_slide, $this->im_fullbg, 0, $this->_y, $this->_x, $this->_y, $this->mark_width, $this->mark_height);
        imagecopy($this->im_slide, $img_mark, 0, $this->_y, 0, 0, $this->mark_width, $this->mark_height);
        imagecolortransparent($this->im_slide, 0);
        imagedestroy($img_mark);
    }

}