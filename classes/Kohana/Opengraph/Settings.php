<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Opengraph_Settings {
	
	private $title;
	private $site_name;
	private $url;
	private $image;
	private $type;
	private $_type;
	private $locale;

	private $description;
	private $admins = array();
	private $app_id;
	
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
		$this->_type = Opengraph_Settings_Type::factory($type, $settings);
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
	private function _build_meta($name, $content = null, $use_prefix = true)
	{
		if(is_string($content) OR is_integer($content))
		{
			$name = ($use_prefix) ? $this->prefix($name) : $name;
					
			return '<meta property="'.$name.'" content="'.$content.'" />';
		}
		
		if(is_array($content))
		{
			$string = '';
			$string .= $this->_build_meta($name, $content, $use_prefix);
			return $string;
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
	
			if($name == 'admins')
			{
				$string .= $this->_build_meta("fb:$name", implode(',', $content), false)."\n\t";
			}
			
			else if($name == 'app_id')
			{
				$string .= $this->_build_meta("fb:$name", $content, false)."\n\t";
			}
			
			else if($name == 'alternative')
			{
				$string .= $this->_build_meta("locale:$name", implode(',', $content))."\n\t";
			}			
			
			else if(!is_array($content) AND !is_object($content))
			{		
				$string .= $this->_build_meta($name, $content)."\n\t";
			}
			
			else if(is_array($content))
			{
				$string .= $this->render($content);
			}
		}		
		
		return $string;
	}

}