<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
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
	 * Display duplicate button or not.
	 *
	 * @var    boolean
	 * @since  __DEPLOY_VERSION__
	 */
	protected $enableDuplicate = true;

	/**
	 * Method for render 'Published' column
	 *
	 * @param   array   $config  Row config.
	 * @param   int     $index   Row index.
	 * @param   object  $row     Row data.
	 *
	 * @return  string
	 * @throws  Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function onRenderColumn($config, $index, $row)
	{
		if ($config['dataCol'] != 'section')
		{
			return parent::onRenderColumn($config, $index, $row);
		}

		$value = $row->{$config['dataCol']};

		if ($value == 'category')
		{
			return '<span class="badge label-success">' . JText::_('COM_REDSHOP_TEXT_SECTION_OPTION_CATEGORY') . '</span>';
		}
		elseif ($value == 'newsletter')
		{
			return '<span class="badge label-primary">' . JText::_('COM_REDSHOP_TEXT_SECTION_OPTION_NEWSLETTER') . '</span>';
		}

		return '<span class="badge label-danger">' . JText::_('COM_REDSHOP_TEXT_SECTION_OPTION_PRODUCT') . '</span>';
	}
}
