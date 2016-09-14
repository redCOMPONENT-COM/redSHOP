<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Shipping.Rate
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Tags replacer abstract class
 *
 * @todo Improve this class follow PSR
 *
 * @since  2.0
 */
abstract class RedshopTagsAbstract implements RedshopTagsInterface
{
	/**
	 * Available tags
	 *
	 * @var   array
	 */
	public $tags = array();
	
	protected $search = array();
	protected $replace = array();
	/**
	 * @return   array
	 */
	public function getTags()
	{
		return $this->tags;
	}
	
	/**
	 * @param   string  $content
	 * @return  string
	 */
	public function replace($content)
	{
		$content = str_replace($this->search, $this->replace, $content);
		
		$generalReplacer = new RedshopTagsReplacerGeneral();
		// Replace general tags
		$content = $generalReplacer->replace($content);
		return $content;
	}
	
	protected function _addReplace ($content, $tag, $value)
	{
		if (strpos($content, $tag) !== false) {
			$this->search [] = $tag;
			$this->replace[] = $value;
		}
	}
}