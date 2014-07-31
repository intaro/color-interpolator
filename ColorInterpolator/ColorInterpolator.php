<?php

namespace Intaro\ColorInterpolator;

class ColorInterpolator
{
    public static function interpolate(Color $color1, Color $color2, $part = 0.5)
    {
        $color = new Color();
        $hsv = [
            'h' => null,
            's' => null,
            'v' => null,
        ];
        $color1Hsv = $color1->getHsv();
        $color2Hsv = $color2->getHsv();

        foreach ($hsv as $key => $value) {
            if ($color1Hsv[$key] < $color2Hsv[$key]) {
                $outLength = 1 - $color2Hsv[$key] + $color1Hsv[$key];
                if ($key == 'h' && $outLength < 0.5) {
                    $hsv[$key] = $color2Hsv[$key] + $outLength * $part;
                    if ($hsv[$key] > 1)
                        $hsv[$key]--;
                } else {
                    $hsv[$key] = ($color2Hsv[$key] - $color1Hsv[$key]) * $part + $color1Hsv[$key];
                }
            } else {
                $outLength = 1 - $color1Hsv[$key] + $color2Hsv[$key];
                if ($key == 'h' && $outLength < 0.5) {
                    $hsv[$key] = $color1Hsv[$key] + $outLength * $part;
                    if ($hsv[$key] > 1)
                        $hsv[$key]--;
                } else {
                    $hsv[$key] = ($color1Hsv[$key] - $color2Hsv[$key]) * $part + $color2Hsv[$key];
                }
            }
        }
        $color->setHsv($hsv);

        return $color;
    }
}
