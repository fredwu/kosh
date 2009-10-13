<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Haml View class for Kohana
 *
 * @package    Kosh
 * @subpackage Haml
 * @author     Justin Hernandez <justin@transphorm.com>
 * @author     Fred Wu <fred@wuit.com>
 * @author     Fred Wu [Envato] <fred@envato.com>
 * @version    based on Kohaml 1.0.2
 * @version    0.1
 * @license    http://www.opensource.org/licenses/mit-license.php
 */
class Haml_View extends View
{
	/**
	 * Set debug from config file and handle passed file name
	 *
	 * @todo   TODO: move some of the caching logic to Haml_Cache 
	 * @param  string  $name
	 */
	public function __construct($name = NULL, $type = NULL)
	{
		// attempt to autoload template if name is empty
		if ( ! $name) $name = $this->haml_autoload();
		// haml file type
		if ( ! $type) $type = Kohana::config('haml.ext');
		
		// load the haml file
		$file = Kohana::find_file('views', $name, $type);
		
		// load cache library
		$cache = new Kosh_Cache('haml');
		$this->_file = $cache->check($file);
		
		// if cache file does not exists then cache output from Haml
		$debug = Kohana::config('haml.debug');
		if ( ! $cache->skip() || $debug)
		{
			$haml = new Haml($debug);
			// put file contents into an array then pass to render
			$output = $haml->compile(file($file), $name);
			// cache output
			if ( ! $debug) $cache->cache($output);
		}
		
	}

	/**
	 * Set autoload variables. Folder is the name of the controller class.
	 * File is the name of the calling function within the class.
	 */
	public function haml_autoload()
	{
		return Kohana::config('haml.controller_sub_folder')
				? Request::instance()->uri()
				: Request::instance()->action;
	}

}
