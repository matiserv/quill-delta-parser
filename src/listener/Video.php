<?php
namespace nadar\quill\listener;

use nadar\quill\Line;
use nadar\quill\Lexer;
use nadar\quill\InlineListener;

/**
 * Convert Video attributes into image element.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.2
 */
class Video extends InlineListener
{
    public $wrapper = '<div class="embed-responsive embed-responsive-16by9" {width}><iframe class="embed-responsive-item" src="{src}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
    
    /**
     * {@inheritDoc}
     */
    public function process(Line $line)
    {
        $embedUrl = $line->insertJsonKey('video');
        $width = '';
		if ($widthpx = $line->getAttribute('width')) {
			$width = "style=\"width: ".$widthpx."px;\"";
		}
        if ($embedUrl) {
            $this->updateInput($line, str_replace(['{src}', '{width}'], [$line->getLexer()->escape($embedUrl), $width], $this->wrapper));
            $line->setDone();
        }
    }
}
