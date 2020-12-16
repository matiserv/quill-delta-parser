<?php

namespace nadar\quill\listener;

use nadar\quill\Line;
use nadar\quill\InlineListener;
use nadar\quill\Lexer;

/**
 * Convert links into a inline elements.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class Link extends InlineListener
{
    /**
     * @var string The wrapper template which is used to generate the link tag
     * @since 2.3.0
     */
    public $wrapper = '<a href="{link}" target="_blank">{text}</a>';
    public $wrapperImg = '<a href="{link}" class="js-smartPhoto" data-caption="{caption}" data-id="{id}" data-group="mutli_view">{text}</a>';

    /**
     * {@inheritDoc}
     */
    public function process(Line $line)
    {
        $link = $line->getAttribute('link');
		
		if ($link) {
			if($this->isImage($link)) {
				$this->updateInput($line, str_replace(['{link}', '{text}', '{caption}', '{id}'], [$line->getLexer()->escape($link), $line->getInput(), $line->getLexer()->escape($link), $line->getLexer()->escape($link)], $this->wrapperImg));

			} else {
				$this->updateInput($line, str_replace(['{link}', '{text}'], [$line->getLexer()->escape($link), $line->getInput()], $this->wrapper));
			}
		}
    }
	function isImage($l) {
		$arr = explode("?", $l);
		return preg_match("#\.(jpg|jpeg|gif|png|webp|svg)$# i", $arr[0]);
	}

}
