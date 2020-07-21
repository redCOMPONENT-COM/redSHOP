<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewStockrooms extends RedshopViewList
{
	/**
	 * Method for render column
	 *
	 * @param array  $config Row config.
	 * @param int    $index  Row index.
	 * @param object $row    Row data.
	 *
	 * @return  string
	 * @throws  Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function onRenderColumn($config, $index, $row)
	{
		switch ($config['dataCol']) {
			case 'show_in_front':
				return JHTML::_('grid.published', $row->show_in_front, $index, 'tick.png', 'publish_x.png', 'front');
			default:
				return parent::/** @scrutinizer ignore-call */ onRenderColumn($config, $index, $row);
		}
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		JToolbarHelper::custom('listing', 'copy.png', 'copy_f2.png', JText::_('COM_REDSHOP_LISTING'), false);
		parent::addToolbar();
	}
}
