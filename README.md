# Color Interpolator

Interpolate color in the spectral band.

## Usage

```php
use Intaro\ColorInterpolator\Color;
use Intaro\ColorInterpolator\ColorInterpolator;

// start rgb color
$startColor = new Color('5fb8df');
// ending rgb color
$endingColor = new Color('3b9bcf');

$color = ColorInterpolator::interpolate($startColor, $endingColor);
```
