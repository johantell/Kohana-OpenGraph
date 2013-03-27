<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Opengraph_Settings {
	
	private $title;
	private $site_name;
	private $url;
	private $image;
	private $type;
	private $locale;
	
	private $description;
	private $fb_admins = array();
	
	public function __construct($settings)
	{	
		foreach($settings as $name => $content)
		{
			$this->set($name, $content);
		}
		
		return $this;
	}
	
	/**
	 *
	 */
	public static function Factory($group = 'default', Array $settings = array())
	{
		$settings = new Opengraph_Settings(Arr::merge(Kohana::$config->load("opengraph.$group"), $settings));
		$settings->url = URL::site(Request::initial()->uri(), 'http');
		
		return $settings;
	}
	
	/**
	 *
	 */
	public function set($name, $value)
	{
		if(!property_exists($this, $name))
		{
			throw new Opengraph_Exception(':name does not exist in settings', array(':name' => $name));
		}
		
		$this->{$name} = $value;
		
		return $this;
	}
	
	/**
	 *
	 */
	public function add_type($type, $settings = array())
	{
		$this->type = Opengraph_Settings_Type::factory($type, $settings);
	}
	
	/**
	 *
	 */
	public function prefix($value)
	{
		return 'og:'.$value;
	}
	
	/**
	 *
	 */
	private function _build_meta($name, $content = null)
	{
		if(is_string($content))
		{
			return '<meta name="'.$this->prefix($name).'" content="'.$content.'" />';
		}
	}
	
	/**
	 *
	 */
	public function render($array = null)
	{
		$string = '';
		
		if(!$array)
		{
			$array = $this;
		}
		
		foreach($array as $name => $content)
		{
			if($content instanceof Opengraph_Settings_Type)
			{
				$string .= $content->render();
			}
			
			if(is_string($content))
			{		
				$string .= $this->_build_meta($name, $content)."\n\t";
			}
		}		
		
		return $string;
	}

}