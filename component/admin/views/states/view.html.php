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
 * The states view
 *
 * @package     RedSHOP.Backend
 * @subpackage  States.View
 * @since       2.0.0.4
 */
class RedshopViewStates extends RedshopViewList
{
	/**
	 * Column for render published state.
	 *
	 * @var    array
	 * @since  __DEPLOY_VERSION__
	 */
	protected $stateColumns = array();

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
		$value = $row->{$config['dataCol']};

		if ($config['dataCol'] === 'country_id')
		{
			return RedshopEntityCountry::getInstance($value)->get('country_name');
		}

		if ($config['dataCol'] === 'show_state')
		{
			if ($value === 3)
			{
				return JText::_('COM_REDSHOP_THREE_LETTER_ABBRIVATION');
			}

			return JText::_('COM_REDSHOP_TWO_LETTER_ABBRIVATION');
		}

		return parent::onRenderColumn($config, $index, $row);
	}
}
