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
 * View Tax groups
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.4
 */
class RedshopViewTax_Groups extends RedshopViewList
{
	/**
	 * Method for prepare table.
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	protected function prepareTable()
	{
		parent::prepareTable();

		$this->columns[] = array(
			// This column is sortable?
			'sortable'  => false,
			// Text for column
			'text'      => JText::_('COM_REDSHOP_TAX_RATE'),
			// Name of property for get data.
			'dataCol'   => 'tax_rates',
			// Width of column
			'width'     => '10%',
			// Enable edit inline?
			'inline'    => false,
			// Display with edit link or not?
			'edit_link' => false,
			// Type of column
			'type'      => 'text',
		);
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
	 * @since   2.0.6
	 */
	public function onRenderColumn($config, $index, $row)
	{
		if ($config['dataCol'] !== 'tax_rates')
		{
			return parent::onRenderColumn($config, $index, $row);
		}

		$taxRates = RedshopEntityTax_Group::getInstance($row->id)->getTaxRates()->count();

		return '<a href="index.php?option=com_redshop&view=tax_rates&filter[tax_group]=' . $row->id . '" '
			. 'class="badge label-success">' . $taxRates . '</a>';
	}
}
