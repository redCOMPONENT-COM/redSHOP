<?php
/**
 * @package     Redshop
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Companies View
 *
 * @package     Redshop
 * @subpackage  Views
 * @since       1.0
 */
class RedshopViewQuotation extends RedshopViewCsv
{
	/**
	 * Delimiter character for CSV columns
	 *
	 * @var string
	 */
	public $delimiter = ';';

	/**
	 * Get the columns for the csv file.
	 *
	 * @return  array  An associative array of column names as key and the title as value.
	 */
	protected function getColumns()
	{
		$model = $this->getModel();

		return $model->getCsvColumns();
	}
}
