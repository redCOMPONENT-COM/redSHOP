<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * View Texts
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       __DEPLOY_VERSION__
 */
class RedshopViewTexts extends RedshopViewList
{
	/**
	 * Method for get page title.
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getTitle()
	{
		return JText::_('COM_REDSHOP_TEXTLIBRARY_MANAGEMENT');
	}

	/**
	 * Method for add toolbar.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function addToolbar()
	{
		parent::addToolbar();

		$bar = JToolbar::getInstance('toolbar');

		$bar->appendButton('Standard', 'copy', JText::_('COM_REDSHOP_COPY'), 'texts.copy', true);
	}

	/**
	 * Method for render 'Published' column
	 *
	 * @param   array   $config  Row config.
	 * @param   int     $index   Row index.
	 * @param   object  $row     Row data.
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function onRenderColumn($config, $index, $row)
	{
		if ($config['dataCol'] == 'section')
		{
			$value = $row->{$config['dataCol']};

			if ($value == 'product')
			{
				return '<span class="btn label-danger" style="text-shadow: none;">' . JText::_('COM_REDSHOP_PRODUCT') . '</span>';
			}
			elseif ($value == 'category')
			{
				return '<span class="btn label-success" style="text-shadow: none;">' . JText::_('COM_REDSHOP_CATEGORY') . '</span>';
			}
			elseif ($value == 'newsletter')
			{
				return '<span class="btn label-info" style="text-shadow: none;">' . JText::_('COM_REDSHOP_NEWSLETTER') . '</span>';
			}
		}

		return parent::onRenderColumn($config, $index, $row);
	}
}
