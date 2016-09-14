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
	public $tags = array();
	
	public function getTags()
	{
		return $this->tags;
	}
	
	public function replace($content)
	{
		$generalReplacer = new RedshopTagsReplacerGeneral();
		// Replace general tags
		$content = $generalReplacer->replace($content);
		return $content;
	}
}