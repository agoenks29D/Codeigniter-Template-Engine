<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @package Codeigniter
 * @subpackage Template Engine Twig
 * @category Config
 * @author Agung Dirgantara <agungmasda29@gmail.com>
 */

$config['twig.extension'] 	= '.twig';
$config['twig.functions']	= array_merge(get_defined_functions()['internal'],get_defined_functions()['user']);
$config['twig.filters']		= get_defined_functions()['user'];
$config['twig.environment'] = array(
	'cache_status'      => FALSE,
	'auto_reload'       => NULL,
	'debug_mode'        => FALSE,
	'autoescape'        => FALSE,
	'optimizations'     => -1
);

/* End of file template_engine_twig.php */
/* Location : ./application/config/template_engine_twig.php */