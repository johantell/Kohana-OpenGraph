<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Opengraph {

	public $settings;
		
	private static $_instance;
	
	public function __construct($config)
	{
		$this->settings = Opengraph_Settings::factory($config);
		
		return $this;
	}
		
	public function __toString()
	{
		return $this->render();
	}
	
	/**
	 *
	 */
	public static function instance($group = 'default')
	{
		if(!self::$_instance)
		{
			self::$_instance = new Opengraph($group);
		}		
		
		return self::$_instance;
	}
	
	public function add_type($type, $settings = array())
	{
		$this->settings->add_type($type, $settings);
	}
	
	/**
	 *
	 */
	public function render()
	{
		return $this->settings->render();
	}	
}