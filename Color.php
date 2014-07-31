<?php

namespace Intaro\ColorInterpolator;

class Color
{
    protected $r;
    protected $g;
    protected $b;

    protected $h;
    protected $s;
    protected $v;

    public function __construct($rgb = null)
    {
        if ($rgb) {
            $this->setRgb($rgb);
        }
    }

    public function setRgb($rgb)
    {
        if (strlen($rgb) < 6) {
            for ($i = 0; $i < 7 - strlen($rgb); $i++)
                $rgb = "0" . $rgb;
        }

        if (strlen($rgb) > 6) {
            $rgb = substr($rgb, strlen($rgb)-6, 6);
        }

        $this->r = hexdec(substr($rgb, 0, 2)) / 255;
        $this->g = hexdec(substr($rgb, 2, 2)) / 255;
        $this->b = hexdec(substr($rgb, 4, 2)) / 255;

        $this->convertRgbToHsv();
    }

    public function getRgb()
    {
        return dechex($this->r*255) . dechex($this->g*255) . dechex($this->b*255);
    }

    public function setHsv(array $hsv)
    {
        $this->h = $hsv['h'];
        $this->s = $hsv['s'];
        $this->v = $hsv['v'];

        $this->convertHsvToRgb();
    }

    public function getHsv()
    {
        return [
            'h' => $this->h,
            's' => $this->s,
            'v' => $this->v,
        ];
    }

    protected function convertHsvToRgb()
    {
        //1
        $h = $this->h * 6;
        //2
        $i = floor($h);
        $f = $h - $i;
        //3
        $m = $this->v * (1 - $this->s);
        $n = $this->v * (1 - $this->s * $f);
        $k = $this->v * (1 - $this->s * (1 - $f));
        //4
        switch ($i) {
            case 0:
                list($this->r,$this->g,$this->b) = array($this->v,$k,$m);
                break;
            case 1:
                list($this->r,$this->g,$this->b) = array($n,$this->v,$m);
                break;
            case 2:
                list($this->r,$this->g,$this->b) = array($m,$this->v,$k);
                break;
            case 3:
                list($this->r,$this->g,$this->b) = array($m,$n,$this->v);
                break;
            case 4:
                list($this->r,$this->g,$this->b) = array($k,$m,$this->v);
                break;
            case 5:
            case 6: //for when $H=1 is given
                list($this->r,$this->g,$this->b) = array($this->v,$m,$n);
                break;
        }

        return $this;
    }

    protected function convertRgbToHsv()
    {

       $min = min($this->r, $this->g, $this->b);
       $max = max($this->r, $this->g, $this->b);
       $del = $max - $min;

       $v = $max;

       if ($del == 0) {
          $h = 0;
          $s = 0;
       } else {
          $s = $del / $max;

          $delR = ( ( ( $max - $this->r ) / 6 ) + ( $del / 2 ) ) / $del;
          $delG = ( ( ( $max - $this->g ) / 6 ) + ( $del / 2 ) ) / $del;
          $delB = ( ( ( $max - $this->b ) / 6 ) + ( $del / 2 ) ) / $del;

          if      ($this->r == $max) $h = $delB - $delG;
          else if ($this->g == $max) $h = ( 1 / 3 ) + $delR - $delB;
          else if ($this->b == $max) $h = ( 2 / 3 ) + $delG - $delR;

          if ($h < 0) $h++;
          if ($h > 1) $h--;
       }

       $this->h = $h;
       $this->s = $s;
       $this->v = $v;

       return $this;
    }
}
