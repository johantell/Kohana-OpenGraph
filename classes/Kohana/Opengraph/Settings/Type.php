<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Opengraph_Settings_Type {
	
	/**
	 *
	 */
	public static function Factory($type, $settings)
	{
		$class = 'Opengraph_Settings_Type_'.ucfirst($type);
				
		return new $class($settings);
	}
	
	/**
	 *
	 */
	public function settings($settings)
	{
		foreach($settings as $name => $content)
		{
			if(!property_exists($this, $name))
			{
				throw new Opengraph_Exception(':name is not a property in :class', array(':name' => $name, ':class' => $this));
			}
			
			$this->{$name} = $content;
		}
	}
	
	/**
	 *
	 */
	public function render()
	{
		$string = '';
		foreach($this as $name => $content)
		{
			if(is_string($content))
			{
				$string .= "<meta name=\"$this->type:$name\" content=\"$content\" />\n\t";
			}
		}
		
		return $string;
	}
}