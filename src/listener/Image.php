<?php

namespace nadar\quill\listener;

use nadar\quill\Line;
use nadar\quill\Lexer;
use nadar\quill\InlineListener;

/**
 * Convert Image attributes into image element.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.2
 */
class Image extends InlineListener
{
    public $wrapper = '<img src="{src}" alt="" class="img-responsive img-fluid" {width} />';
    
    /**
     * {@inheritDoc}
     */
    public function process(Line $line)
    {
        $embedUrl = $line->insertJsonKey('image');
		$width = '';
		if ($widthpx = $line->getAttribute('width')) {
			$width = "style=\"width: ".$widthpx."px;\"";
		}
		
        if ($embedUrl) {
            $this->updateInput($line, str_replace(['{src}', '{width}'], [$line->getLexer()->escape($embedUrl), $width], $this->wrapper));
        }
    }
}
