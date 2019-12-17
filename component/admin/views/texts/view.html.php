<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * View Texts
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.1.0
 */
class RedshopViewTexts extends RedshopViewList
{
	/**
	 * Display duplicate button or not.
	 *
	 * @var    boolean
	 * @since  2.1.0
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
	 * @since   2.1.0
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
