<?php

namespace nadar\quill\listener;

use nadar\quill\Line;
use nadar\quill\InlineListener;

/**
 * Convert color attributes into span tag.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.1.0
 */
 
class Colore extends InlineListener
{
    /**
     * @var boolean If ignore is enabled, the colors won't apply. This can be use full if coloring is disabled in your quill editor
     * but people copy past content from somewhere else which will then generate the color attribute.
     */
    public $ignore = false;
    
    /**
     * {@inheritDoc}
     */
    public function process(Line $line)
    {
        if (($color = $line->getAttribute('color'))) {
			$rgb = $this->HTMLToRGB($color);
			$hsl = $this->RGBToHSL($rgb);
			if($hsl->lightness > 200 || $hsl->lightness < 55) {
				$this->updateInput($line, $line->input);
			} else {
				$this->updateInput($line, $this->ignore ? $line->input : '<span style="color:'.$line->getLexer()->escape($color).'">'.$line->getInput().'</span>');
			}
        }
    }
	function HTMLToRGB($htmlCode)
	  {
		if($htmlCode[0] == '#')
		  $htmlCode = substr($htmlCode, 1);

		if (strlen($htmlCode) == 3)
		{
		  $htmlCode = $htmlCode[0] . $htmlCode[0] . $htmlCode[1] . $htmlCode[1] . $htmlCode[2] . $htmlCode[2];
		}

		$r = hexdec($htmlCode[0] . $htmlCode[1]);
		$g = hexdec($htmlCode[2] . $htmlCode[3]);
		$b = hexdec($htmlCode[4] . $htmlCode[5]);

		return $b + ($g << 0x8) + ($r << 0x10);
	  }

	function RGBToHSL($RGB) {
		$r = 0xFF & ($RGB >> 0x10);
		$g = 0xFF & ($RGB >> 0x8);
		$b = 0xFF & $RGB;

		$r = ((float)$r) / 255.0;
		$g = ((float)$g) / 255.0;
		$b = ((float)$b) / 255.0;

		$maxC = max($r, $g, $b);
		$minC = min($r, $g, $b);

		$l = ($maxC + $minC) / 2.0;

		if($maxC == $minC)
		{
		  $s = 0;
		  $h = 0;
		}
		else
		{
		  if($l < .5)
		  {
			$s = ($maxC - $minC) / ($maxC + $minC);
		  }
		  else
		  {
			$s = ($maxC - $minC) / (2.0 - $maxC - $minC);
		  }
		  if($r == $maxC)
			$h = ($g - $b) / ($maxC - $minC);
		  if($g == $maxC)
			$h = 2.0 + ($b - $r) / ($maxC - $minC);
		  if($b == $maxC)
			$h = 4.0 + ($r - $g) / ($maxC - $minC);

		  $h = $h / 6.0; 
		}

		$h = (int)round(255.0 * $h);
		$s = (int)round(255.0 * $s);
		$l = (int)round(255.0 * $l);

		return (object) Array('hue' => $h, 'saturation' => $s, 'lightness' => $l);
	  }
}
