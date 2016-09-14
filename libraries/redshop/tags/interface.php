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
 * Tags replacer
 *
 * @todo Improve this class follow PSR
 *
 * @since  2.0
 */
interface RedshopTagsInterface
{
	public function getTags();
	public function replace($content);
}