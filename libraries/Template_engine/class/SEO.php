<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @package Codeigniter
 * @subpackage SEO 
 * @category Library
 * @author Agung Dirgantara <agungmasda29@gmail.com>
 * 
 * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/meta
 * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/link
 * @link https://dev.twitter.com/cards/overview
 * @link https://developers.facebook.com/docs/sharing/webmasters
 */

namespace CI\TemplateEngine;

class SEO
{
	protected $meta_tags = array();
	protected $link_tags = array();

	protected $meta_tag_template = "<meta {{ attributes }} />\n";
	protected $link_tag_template = "<link {{ attributes }} />\n";

	/**
	 * constructor
	 */
	public function __construct()
	{

	}

	/**
	 * Render meta tag & link tag
	 * 
	 * @return string
	 */
	public function render()
	{
		$output = '';
		$output .= $this->render_meta_tag();
		$output .= $this->render_link_tag();
		return $output;
	}

	/**
	 * Render meta tag
	 * 
	 * @return string
	 */
	public function render_meta_tag()
	{
		$output 	= '';

		foreach ($this->meta_tags as  $meta_tag)
		{
			$output .= str_replace('{{ attributes }}',  implode(' ', $meta_tag), $this->meta_tag_template);
		}

		return $output;
	}

	/**
	 * Render link tag
	 * 
	 * @return string
	 */
	public function render_link_tag()
	{
		$output 	= '';

		foreach ($this->link_tags as  $link_tag)
		{
			$output .= str_replace('{{ attributes }}',  implode(' ', $link_tag), $this->link_tag_template);
		}

		return $output;
	}

	/**
	 * Add meta tag
	 * 
	 * @param array   $global_attributes
	 * @param array   $additional_attributes
	 * @param boolean | CI\TemplateEngine\SEO $return
	 */
	public function add_meta_tag(array $global_attributes, $additional_attributes = array(), $return = TRUE)
	{
		$tag_attributes = array();

		foreach ($global_attributes as $global_attribute => $attribute_value) {
			if (in_array($global_attribute, array('charset', 'content', 'http-equiv', 'name', 'itemprop'))) {

				if ($global_attribute == 'charset') {
					$available_value = array('UTF-8', 'ISO-8859-1');

					if (in_array(strtoupper($attribute_value), $available_value)) {
						array_push($tag_attributes, $global_attribute.'="'.$attribute_value.'"');
					}
				}

				if (in_array($global_attribute, array('content', 'name'))) {
					$available_attributes = array('http-equiv', 'name');

					foreach (array_keys($global_attributes) as $value) {
						if (in_array($value, $available_attributes)) {
							array_push($tag_attributes, $global_attribute.'="'.$attribute_value.'"');
						}	
					}
				}

				if ($global_attribute == 'http-equiv') {
					$available_value = array('content-security-policy', 'content-type', 'default-style', 'x-ua-compatible', 'refresh');

					if (in_array(strtolower($attribute_value), $available_value)) {
						array_push($tag_attributes, $global_attribute.'="'.$attribute_value.'"');
					}
				}
			}
		}

		foreach ($additional_attributes as $additional_attribute => $attribute_value) {
			array_push($tag_attributes, $additional_attribute.'="'.$attribute_value.'"');
		}

		$this->meta_tags[sha1(json_encode($tag_attributes))] = $tag_attributes;

		return $return?$this:FALSE;
	}

	/**
	 * Add link tag
	 * 
	 * @param array   $global_attributes
	 * @param array   $additional_attributes
	 * @param boolean | CI\TemplateEngine\SEO $return
	 */
	public function add_link_tag(array $global_attributes, $additional_attributes = array(), $return = TRUE)
	{
		$tag_attributes = array();

		foreach ($global_attributes as $global_attribute => $attribute_value) {
			if (in_array($global_attribute, array('as', 'crossorigin', 'disabled', 'href', 'hreflang', 'importance', 'integrity', 'media', 'referrerpolicy', 'rel', 'sizes', 'title', 'type', 'methods', 'prefetch', 'target'))) {

				if ($global_attribute == 'as') {
					$available_value = array('audio', 'document', 'embed', 'fetch', 'font', 'image', 'object', 'script', 'style', 'track', 'video', 'worker');
					if (in_array(strtolower($attribute_value), $available_value)) {
						array_push($tag_attributes, $global_attribute.'="'.$attribute_value.'"');
					}
				}

				if ($global_attribute == 'crossorigin') {
					$available_value = array('anonymous', 'use-credentials');
					if (in_array(strtolower($attribute_value), $available_value)) {
						array_push($tag_attributes, $global_attribute.'="'.$attribute_value.'"');
					}
				}

				if ($global_attribute == 'importance') {
					$available_value = array('auto', 'high', 'low');
					if (in_array(strtolower($attribute_value), $available_value)) {
						array_push($tag_attributes, $global_attribute.'="'.$attribute_value.'"');
					}
				}

				if ($global_attribute == 'media') {
					$available_value = array('print', 'screen', 'aural', 'braille');
					if (in_array(strtolower($attribute_value), $available_value)) {
						array_push($tag_attributes, $global_attribute.'="'.$attribute_value.'"');
					}
				}

				if ($global_attribute == 'referrerpolicy') {
					$available_value = array('no-referrer', 'no-referrer-when-downgrade', 'origin', 'origin-when-cross-origin', 'unsafe-url');
					if (in_array(strtolower($attribute_value), $available_value)) {
						array_push($tag_attributes, $global_attribute.'="'.$attribute_value.'"');
					}
				}

				if ($global_attribute == 'type') {
					$available_value = array('text/html', 'text/css');
					if (in_array(strtolower($attribute_value), $available_value)) {
						array_push($tag_attributes, $global_attribute.'="'.$attribute_value.'"');
					}
				}

				if (in_array($global_attribute, ['disabled', 'href', 'hreflang', 'integrity', 'rel', 'sizes', 'title', 'methods', 'prefetch', 'target'])) {
					array_push($tag_attributes, $global_attribute.'="'.$attribute_value.'"');
				}
			}
		}

		foreach ($additional_attributes as $additional_attribute => $attribute_value) {
			array_push($tag_attributes, $additional_attribute.'="'.$attribute_value.'"');
		}

		$this->link_tags[sha1(json_encode($tag_attributes))] = $tag_attributes;

		return $return?$this:FALSE;
	}
}

/* End of file SEO.php */
/* Location : ./Template_Engine/libraries/Template_Engine/class/SEO.php */