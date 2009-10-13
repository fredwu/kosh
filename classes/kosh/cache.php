<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Haml Cache class for Kohana
 *
 * @package    Kosh
 * @subpackage Kosh
 * @author     Justin Hernandez <justin@transphorm.com>
 * @author     Fred Wu <fred@wuit.com>
 * @author     Fred Wu [Envato] <fred@envato.com>
 * @version    based on Kohaml 1.0.2
 * @version    0.1
 * @license    http://www.opensource.org/licenses/mit-license.php
 */
class Kosh_Cache
{
	// skip caching?
	private $skip;
	// file name
	private $file;
	// file type
	private $type;
	// cache time to live
	private $cache_ttl;
	

	/**
	 * Set type, haml or sass
	 *
	 * @param  string  $type
	 */
	public function __construct($type = 'haml')
	{
		$this->type = $type;
		$this->cache_ttl = Kohana::config($this->type.'.cache_ttl');
	}

	/**
	 * Main function. Handle tasks
	 *
	 * @param  string  $file
	 */
	public function check($file)
	{
		$this->file = $file;
		// check for debug
		$this->check_directory();
		$this->clean_cache();
		
		return $this->check_cache();
	}
	
	/**
	 * Skip need for caching?
	 */
	public function skip()
	{
		return $this->skip;
	}
	
	/**
	 * Cache output from Haml.
	 *
	 * @param  string  $output
	 */
	public function cache($output)
	{
		$name = Kohana::config($this->type.'.cache_folder').'/'.md5($this->file).EXT;
		// add offset to cache file if using load() if not nested don't indent
		file_put_contents($name, $output);
		unset($output);
	}

	/**
	 * Check if cache directory exists. If not, create it.
	 */
	private function check_directory()
	{
		if ( ! is_dir(Kohana::config($this->type.'.cache_folder')))
			mkdir(Kohana::config($this->type.'.cache_folder'));
	}

	
	/**
	 * Check if a cached file exists. If it exists but is old then delete it.
	 */
	private function check_cache()
	{
		$cache_file = Kohana::config($this->type.'.cache_folder').'/'.md5($this->file).EXT;
		$this->skip = FALSE;
		
		if (is_file($cache_file))
		{
			// touch file. helps determine if template was modified
			// FIXME: I'm not sure why this was needed, 'touch'ing the file will make the cached file to always regenerate
			// touch($cache_file);
			
			// check if template has been mofilemtime($cache_file)dified and is newer than cache
			// allow $cache_ttl difference
			if ((filemtime($this->file)-$this->cache_ttl) > filemtime($cache_file))
			{
				echo time(), ' ', (filemtime($this->file)+$this->cache_ttl), ' ', (filemtime($cache_file));
				$this->skip = TRUE;
			}
		}
		
		return $cache_file;
	}

	/**
	 * Delete old cached files based on cache time and cache gc probability set
	 * in the config file.
	 */
	private function clean_cache()
	{
		//gc probability
		$gc = rand(1, Kohana::config($this->type.'.cache_gc'));
		if ($gc != 1) return FALSE;
		$cache = new DirectoryIterator(Kohana::config($this->type.'.cache_folder'));
		while ($cache->valid())
		{
			// if file is past maximum cache settings delete file
			$cached = date('U', $cache->getMTime());
			$max = time() + Kohana::config($this->type.'.cache_clean_time');
			if ($cache->isFile() AND ($cached > $max))
			{
				unlink($cache->getPathname());
			}
			$cache->next();
		}
	}

}
