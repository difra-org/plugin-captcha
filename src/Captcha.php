<?php

declare(strict_types=1);

namespace Difra;

use Difra\Envi\Session;

class Captcha
{
    /** @var string|null Last displayed key */
    private ?string $key = null;
    /** @var int Width */
    private int $sizeX = 140;
    /** @var int Height */
    private int $sizeY = 40;
    /** @var int Key length */
    private int $keyLength = 5;

    /**
     * Constructor
     */
    private function __construct()
    {
        // Load captcha key from session
        Session::start();
        $this->key = $_SESSION['captcha_key'] ?? null;
    }

    /**
     * Singleton
     * @return Captcha
     */
    public static function getInstance(): Captcha
    {
        static $instance = null;
        return $instance ?: $instance = new self();
    }

    /**
     * Verify entered key
     * @param string $inKey
     * @return bool
     */
    public function verifyKey(string $inKey): bool
    {
        return $this->key and strtoupper($this->key) == strtoupper($inKey);
    }

    /**
     * Captcha generators
     */

    /** Gray blurred text */
    const METHOD_GRAYBLUR = 'grayblur';
    /** Gray noised text */
    const METHOD_GRAYNOISE = 'graynoise';
    /** Default method */
    const METHOD_DEFAULT = self::METHOD_GRAYBLUR;

    /**
     * Creates image with text
     * @param int $sizeX
     * @param int $sizeY
     * @param string $text
     * @param string $generator
     * @return \Imagick
     * @throws \ImagickException|\ImagickDrawException
     */
    public function mkCapcha(int $sizeX, int $sizeY, string $text, string $generator = self::METHOD_DEFAULT)
    {
        // init image
        $image = new \Imagick();
        $image->newImage($sizeX, $sizeY, new \ImagickPixel('white'));
        $image->setImageFormat('png');

        switch ($generator) {
            case self::METHOD_GRAYNOISE:
                $draw = new \ImagickDraw();
                $draw->setFontSize(35);
                $draw->setFontWeight(900);
                $draw->setGravity(\imagick::GRAVITY_CENTER);
                $image->addNoiseImage(\imagick::NOISE_LAPLACIAN);
                $image->annotateImage($draw, 0, 0, 0, $text);
                $image->charcoalImage(2, 1.5);
                $image->addNoiseImage(\imagick::NOISE_LAPLACIAN);
                $image->gaussianBlurImage(1, 1);
                break;
            case self::METHOD_GRAYBLUR:
                $draw = new \ImagickDraw();
                $order = [];
                for ($i = 0; $i < strlen($text); $i++) {
                    $order[$i] = $i;
                }
                shuffle($order);
                for ($j = 0; $j < 2; $j++) {
                    shuffle($order);
                    $image->gaussianBlurImage(15, 3);
                    for ($n = 0; $n < strlen($text); $n++) {
                        $i = $order[$n];
                        $draw->setFont(__DIR__ . '/fonts/DejaVuSans.ttf');
                        $draw->setFontSize(
                            $j ? rand($sizeY * 3 / 5, $sizeY * 5 / 6) : rand($sizeY * 4 / 6, $sizeY * 5 / 6)
                        );
                        $draw->setFontWeight(rand(100, 900));
                        $draw->setGravity(\imagick::GRAVITY_CENTER);
                        $image->annotateImage(
                            $draw,
                            ($i - strlen($text) / 2) * $sizeX / (strlen($text) + 2.3),
                            0,
                            rand(-25, 25),
                            $text[$i]
                        );
                        $image->gaussianBlurImage(1, 1);
                    }
                }
                break;
        }
        return $image;
    }

    /**
     * Generates random key
     * @param int $len
     * @return string
     */
    public function genKey(int $len): string
    {
        $a = '';
        $chars = 'ACDEFGHJKLNPRUVXYacdhknpsuvxyz3467';
        for ($i = 0; $i < $len; $i++) {
            $a .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $a;
    }

    /**
     * Create captcha image with new key
     * @return \Imagick
     * @throws \ImagickException|\ImagickDrawException
     */
    public function viewCaptcha(): \Imagick
    {
        $this->key = $this->genKey($this->keyLength);
        $data = $this->mkCapcha($this->sizeX, $this->sizeY, $this->key);
        Session::start();
        $_SESSION['captcha_key'] = $this->key;
        return $data;
    }

    /**
     * Set image size for $this->viewCaptcha()
     * @param int $sizeX
     * @param int $sizeY
     */
    public function setSize(int $sizeX, int $sizeY): void
    {
        $this->sizeX = $sizeX;
        $this->sizeY = $sizeY;
    }

    /**
     * Set key length for $this->viewCaptcha()
     * @param int $n
     */
    public function setKeyLength(int $n): void
    {
        $this->keyLength = $n;
    }
}
