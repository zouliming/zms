<?php

class IdentifyCode {

        public $image_width = 88;
        public $image_height = 30;
        public $characters_on_image = 4;
        public $font = 'STXINGKA.TTF';
        //以下字符将用于验证码中的字符 
        public $possible_letters = '0123456789'; //为了避免混淆去掉了数字1和字母i
        public $captcha_text_color = "0xe73261"; //验证码字体颜色

        const text_color_red = "0xe73261";
        const text_color_blue = "0x3366cc";

        public $captcha_noice_color = "0xe73261"; //干扰颜色
        public $random_dots = 10; //干扰噪点数量
        public $random_lines = 30; //干扰线条数量
        public $bool_noise = false;

        public function __construct() {
                $this->setFontPath();
        }

        //设置字体路径
        public function setFontPath() {
                $this->font = LIB_DIRECTORY . SEP . "fonts" . SEP . $this->font;
        }

        public function generateImg($color = "blue") {
                $code = '';

                $i = 0;
                while ($i < $this->characters_on_image) {
                        $code .= substr($this->possible_letters, mt_rand(0, strlen($this->possible_letters) - 1), 1);
                        $i++;
                }

                $font_size = $this->image_height * 0.75;
                $image = @imagecreate($this->image_width, $this->image_height);

                /* 设置背景、文本和干扰的噪点 */
                imagecolorallocate($image, 255, 255, 255);
                if ($color == "blue") {
                        $this->captcha_text_color = self::text_color_blue;
                } else {
                        $this->captcha_text_color = self::text_color_red;
                }
                $arr_text_color = $this->hexrgb($this->captcha_text_color);
                $text_color = imagecolorallocate($image, $arr_text_color['red'], $arr_text_color['green'], $arr_text_color['blue']);

                /* 增加干扰 */
                if ($this->bool_noise == true) {
                        $arr_noice_color = $this->hexrgb($this->captcha_noice_color);
                        $image_noise_color = imagecolorallocate($image, $arr_noice_color['red'], $arr_noice_color['green'], $arr_noice_color['blue']);
                        /* 在背景上随机的生成干扰噪点 */
                        for ($i = 0; $i < $this->random_dots; $i++) {
                                imagefilledellipse($image, mt_rand(0, $this->image_width), mt_rand(0, $this->image_height), 2, 3, $image_noise_color);
                        }
                        /* 在背景图片上，随机生成线条 */
                        for ($i = 0; $i < $this->random_lines; $i++) {
                                imageline($image, mt_rand(0, $this->image_width), mt_rand(0, $this->image_height), mt_rand(0, $this->image_width), mt_rand(0, $this->image_height), $image_noise_color);
                        }
                }

                /* 生成一个文本框，然后在里面写生6个字符 */
                $textbox = imagettfbbox($font_size, 0, $this->font, $code);
                $x = ($this->image_width - $textbox[4]) / 2;
                $y = ($this->image_height - $textbox[5]) / 2;
                imagettftext($image, $font_size, 0, $x, $y, $text_color, $this->font, $code);

                /* 将验证码图片在HTML页面上显示出来 */
                header('Content-Type: image/jpeg');
                // 设定图片输出的类型
                imagejpeg($image);
                //显示图片
                imagedestroy($image);
                //销毁图片实例

                $checkCook = self::encrypt($code);
                $secure = $_SERVER['SERVER_PORT'] == 443 ? 1 : 0;
                $cookieConfig = Bee::app()->getConfig('cookie');
                if (isset($cookieConfig["path"]) && isset($cookieConfig["domain"])) {
                        setcookie("loginVerifyCode", $checkCook, time() + 600, $cookieConfig["path"], $cookieConfig["domain"], $secure);
                } else {
                        setcookie("loginVerifyCode", $checkCook, time() + 600);
                }
        }

        public function hexrgb($hexstr) {
                $int = hexdec($hexstr);
                return array(
                        "red" => 0xFF & ($int >> 0x10),
                        "green" => 0xFF & ($int >> 0x8),
                        "blue" => 0xFF & $int
                );
        }

        public static function encrypt($code) {
                return md5('Vip' . $code . 'shop');
        }

}

?>