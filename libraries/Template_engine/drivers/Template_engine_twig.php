<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @package Codeigniter
 * @subpackage Template Engine Twig
 * @category Library
 * @author Agung Dirgantara <agungmasda29@gmail.com>
 */

require (__DIR__.'/../class/Twig.php');

class Template_engine_twig extends CI_Driver
{
	private $ci;

	private $engine;

	/**
	 * constructor
	 */
	public function __construct()
	{
		$this->ci =& get_instance();
	}

	/**
	 * Get Engine
	 * 
	 * @return object
	 */
	public function get_engine()
	{
		return $this->engine;
	}

	/**
	 * Render View
	 * 
	 * @param  string $page View File
	 * @param  array  $data Content Data
	 * @return Output View
	 */
	public function render($page, $data = array())
	{
		$this->ci->output->_display($this->engine->render($page, $data));
	}

	/**
	 * Decorate
	 */
	public function decorate($parent)
	{
		$this->engine = new \CI\TemplateEngine\Twig($parent->view_paths, $parent->cache_path);
	}
}

/* End of file Template_Engine_Twig.php */
/* Location : ./application/libraries/Template_Engine/drivers/Template_Engine_Twig.php */