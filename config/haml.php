<?php defined('SYSPATH') or die('No direct script access.');

return array(

	/**
	 * Use controller name as a sub-folder
	 *
	 * DEFAULT: TRUE
	 */

	'controller_sub_folder' => TRUE,

	/**
	 * Debug mode?
	 *
	 * DEFAULT: FALSE
	 */
	'debug' => FALSE,

	/**
	 * Single or double quotes for wrapping attribute values
	 *
	 * DEFAULT: 'double'
	 */
	'quotes' => 'double',

	/**
	 * Haml extension. Haml templates must reside within a views folder
	 *
	 * DEFAULT: haml
	 */
	'ext' => 'haml',

	/**
	 * Cache folder for compiled templates.
	 *
	 * DEFAULT: APPPATH.'/cache/kosh/haml'
	 */
	'cache_folder' => APPPATH.'cache/haml',

	/**
	 * Number of seconds before regenerating the cached files.
	 *
	 * DEFAULT: 86400
	 */
	'cache_ttl' => 86400,

	/**
	 * How long to keep cached templates. Default is one month. Good for removing
	 * view templates that are no longer being used.
	 *
	 * DEFAULT: 2592000
	 */
	'cache_clean_time' => 2592000,

	/**
	 * Define gc probability. Default is 30. So 1/30 is a ~3% of gc being run.
	 *
	 * DEFAULT: 30
	 */
	'cache_gc' => 30,
	
);