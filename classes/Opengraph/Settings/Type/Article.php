<?php defined('SYSPATH') or die('No direct script access.');

class Opengraph_Settings_Type_Article extends Opengraph_Settings_Type {
	
	public $type = 'article';
	
	public $author;
	
	public $expiration_time;
	public $modified_time;
	public $published_time;
	
	public $section;
	
	public $tag;
	
	public function __construct(Array& $settings)
	{
		$this->settings($settings);
	
		return $this;
	}
}