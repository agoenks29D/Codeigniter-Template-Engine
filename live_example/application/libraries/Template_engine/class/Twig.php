<?php
/**
 * @package Codeigniter
 * @subpackage Twig 
 * @category Library
 * @author Agung Dirgantara <agungmasda29@gmail.com>
 */

namespace CI\TemplateEngine;

class Twig
{
	/**
	 * Codeigniter Instance
	 * @var object
	 */
	private $ci;

	/**
	 * Twig
	 * @var object
	 */
	private $twig;

	/**
	 * Twig file system loader
	 * @var object
	 */
	private $twig_loader;

	/**
	 * Twig template directories
	 * @var array
	 */
	private $template_directories = array();

	/**
	 * Twig local vars
	 * @var array
	 */
	private $local_vars = array();

	/**
	 * Twig global vars
	 * @var array
	 */
	private $global_vars = array();

	public function __construct($viewPaths, string $cachePath)
	{
		$this->ci =& get_instance();
		
		/**
		 * load config file
		 */
		$this->ci->config->load('template_engine_twig',  FALSE, TRUE);

		/**
		 * add & set template locations
		 */
		$this->add_template_location($viewPaths);
		$this->set_template_locations();

		/**
		 * register twig loader
		 * @var object
		 */
		$this->twig_loader = new \Twig\Loader\FilesystemLoader($viewPaths);

		/**
		 * get twig environment from config file
		 * @var string
		 */
		$environment = $this->ci->config->item('twig.environment');

		$environment['cache'] = ($environment['cache_status']) ? $cachePath : FALSE;
		$twig_environment = array(
			'cache' 		=> $environment['cache'],
			'debug'			=> $environment['debug_mode'],
			'auto_reload'	=> $environment['auto_reload'],
			'autoescape'	=> $environment['autoescape'],
			'optimizations'	=> $environment['optimizations']
		);

		/**
		 * register twig environment
		 * @var object
		 */
		$this->twig = new \Twig\Environment($this->twig_loader, $twig_environment);

		/**
		 * register functions
		 */
		foreach ($this->ci->config->item('twig.functions') as $function)
		{
			$this->register_function($function);
		}

		/**
		 * register filter
		 */
		foreach ($this->ci->config->item('twig.filters') as $filter)
		{
			$this->register_filter($filter);
		}
	}

	/**
	 * Render
	 * 
	 * @param  string  $page file name
	 * @param  array   $data     variable to render
	 * @param  boolean $return   return compiled data (return as string)
	 * @return string
	 */
	public function render($page, $data = array())
	{
		if (stripos($page, '.') === FALSE)
		{
			$page .= $this->ci->config->item('twig.extension');
		}

		$page = $this->twig->loadTemplate($page);
		
		return $page->render($data);
	}

	/**
	 * Parse File (compile twig code from file)
	 *
	 * note :
	 * - the default file extension set in config file.
	 * - if file extension not set, will use default file extension.
	 * 
	 * @param  string  $template file name
	 * @param  array   $data     variable to render
	 * @param  boolean $return   return compiled data (return as string)
	 * @return string
	 */
	public function parse_file($template, $data = array(), $return = false)
	{
		if (stripos($template, '.') === FALSE)
		{
			$template .= $this->ci->config->item('twig.extension');
		}

		$template = $this->twig->loadTemplate($template);
		
		return ($return)?$template->render($data):$template->display($data);
	}

	/**
	 * Parse String (compile twig code from string)
	 * 
	 * @param  string  $twig_string
	 * @param  array   $data
	 * @param  boolean $return
	 * @return string
	 */
	public function parse_string($twig_string = null, $data = array(), $return = false)
	{
		$template = uniqid();
		$this->twig->setLoader(new \Twig\Loader\ArrayLoader([$template => $twig_string]));

		return ($return)?$this->twig->render($template, $data):$this->twig->display($template, $data);
	}

	/**
	 * Register Function
	 * 
	 * @param  function $function callable function
	 * @return object
	 */
	public function register_function($function)
	{
		if (is_callable($function))
		{
			$this->twig->addFunction(new \Twig\TwigFunction($function, $function));
		}

		return $this;
	}

	/**
	 * Register Twig Filter
	 * 
	 * @param  function $filter callable function
	 * @return object
	 */
	public function register_filter($filter)
	{
		if (is_callable($filter))
		{
			$this->twig->addFilter(new \Twig\TwigFilter($filter, $filter));
		}

		return $this;
	}

	/**
	 * Add Template Location
	 * 
	 * @param string $location path to template directory
	 */
	public function add_template_location($location)
	{
		if (is_array($location))
		{
			foreach ($location as $path => $offset)
			{
				if (is_dir($path))
				{
					$this->template_directories[] = $path;
				}
			}
		}
		else
		{
			if (is_dir($location))
			{
				$this->template_directories[] = $location;
			}
		}
	}

	/**
	 * Set Template Location
	 *
	 * supported for hmvc extension
	 */
	private function set_template_locations()
	{
		if (method_exists($this->ci->router,'fetch_module'))
		{
			$this->_module = $this->ci->router->fetch_module();
			if ($this->_module)
			{
				$module_locations = \Modules::$locations;
				foreach ($module_locations AS $location => $offset)
				{
					if (is_dir($location . $this->_module . '/views'))
					{
						$this->template_directories[] = $location . $this->_module . '/views';
					}
				}
			}
		}

		if ($this->twig_loader)
		{
			$this->twig_loader->setPaths($this->template_directories);
		}
	}
}

/* End of file Twig.php */
/* Location : ./application/libraries/Template_Engine/class/Twig.php */