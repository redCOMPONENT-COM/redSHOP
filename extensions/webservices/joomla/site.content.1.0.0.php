<?php
/**
 * @package     Redcore
 * @subpackage  Api
 *
 * @copyright   Copyright (C) 2008 - 2021 redWEB.dk. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('JPATH_BASE') or die;

/**
 * Api Helper class for overriding default methods
 *
 * @package     Redcore
 * @subpackage  Api Helper
 * @since       1.8
 */
class RApiHalHelperSiteContent
{
	/**
	 * Service for creating content.
	 *
	 * @param   string  $data  content
	 *
	 * @return  boolean         True on success. False otherwise.
	 */
	public function save($data)
	{
		if (version_compare(JVERSION, '3.0', 'lt')) {
			JTable::addIncludePath(JPATH_PLATFORM . 'joomla/database/table');
		}
		
		$data = (object) $data;
		$article = JTable::getInstance('content');
		$article->title            = $data->title;
		// $article->alias            = JFilterOutput::stringURLSafe(time());
		$article->alias            = JFilterOutput::stringURLSafe($data->title);
		$article->introtext        = '<p>'.$data->description.'</p>';
		$article->created          = JFactory::getDate()->toSQL();;
		$article->created_by_alias = $data->user;
		$article->state            = 1;
		$article->access           = 1;
		$article->metadata         = '{"page_title":"'.$data->title.'","author":"'.$data->user.'","robots":""}';
		$article->language         = '*';
//		$article->catid            = 1;
		
		if (!$article->check()) {
			throw new Exception($article->getError());
			return FALSE;
		}
		
		if (!$article->store(TRUE)) {
			throw new Exception($article->getError());
			return FALSE;
		}
	}
}
