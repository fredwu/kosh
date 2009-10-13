<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Sass helper class for Kohana
 *
 * @todo       TODO: KO3 compatibility check
 * @package    Kosh
 * @subpackage Sass
 * @author     Justin Hernandez <justin@transphorm.com>
 * @author     Fred Wu <fred@wuit.com>
 * @author     Fred Wu [Envato] <fred@envato.com>
 * @version    based on Kohaml 1.0.2
 * @version    0.1
 * @license    http://www.opensource.org/licenses/mit-license.php
 */
class Sass_Helper
{

	// files array
	private static $files = array();
	// cache files array
	private static $cache_files = array();
	// compiled ouput
	private static $output = array();
	// cache directory
	private static $cache_folder;

	/**
	 * Render sass files.
	 *
	 * @param   mixed    $files
	 * @param   boolean  $production
	 * @param   boolean  $inline
	 * @return  string
	 */
	public static function stylesheet($files, $production = FALSE, $inline = FALSE)
	{
		return self::handler($files, $production, $inline);
	}
	
	/**
	 * Handler
	 *
	 * @param   mixed    $files
	 * @param   boolean  $production
	 * @param   boolean  $inline
	 * @return  string
	 */
	private static function handler($files, $production, $inline)
	{
		// set variables
		self::$cache_folder = Kohana::config('sass.cache_folder');
		
		// if files is not an array convert to one
		if ( ! is_array($files)) $files = array($files);
		
		// set files 
		self::$files = $files;
		
		// add absolute path to file names
		self::get_files_path();
		
		// load cache library
		$cache =  new Haml_Cache('sass');
		// check for debug
		$debug = TRUE; //Kohana::config('sass.debug');
		// init Sass
		$sass = new Sass($debug);
		
		// loop files
		foreach (self::$files as $file)
		{
			// add cache file name
			self::$cache_files[] = $cache->check($file);
			$name = basename($file, '.'.Kohana::config('sass.ext'));
		
			// if cache file does not exists then cache output from Haml
			 if ( ! $cache->skip() || $debug)
			{
				// put file contents into an array then pass to render
				$output = $sass->compile(file($file), $name);
				// cache output
				if ( ! $debug) $cache->cache($output);
			}
		}
		
		// destroy static variables
		self::destruct();
	}
	
	/**
	 * Add absolute paths to sass files
	 */
	private static function get_files_path()
	{
		$base = Kohana::config('sass.base_folder');
		$sub = Kohana::config('sass.sub_folder');
		// find each sass file
		foreach (self::$files as &$file)
				$file = Kohana::find_file($base, $sub.'/'.$file, TRUE, 'sass');
	}
	
	/**
	 * Static destroyer - watchout be afraid!!!! LOL man I crack myself up!
	 *
	 * @param   param
	 * @return  return
	 */
	private static function destruct()
	{
		
	}

}
