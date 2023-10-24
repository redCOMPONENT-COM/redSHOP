<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/**
 * Table Tax Rate
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       3.0.2
 */
class RedshopTableTax_Rate extends RedshopTable
{
	/**
	 * The table name without the prefix. Ex: cursos_courses
	 *
	 * @var  string
	 */
	protected $_tableName = 'redshop_tax_rate';

	/**
	 * Checks that the object is valid and able to be stored.
	 *
	 * This method checks that the parent_id is non-zero and exists in the database.
	 * Note that the root node (parent_id = 0) cannot be manipulated with this class.
	 *
	 * @return  boolean  True if all checks pass.
	 */
	protected function doCheck()
	{
		if (empty($this->name)) {
			return false;
		}

		if (empty($this->tax_group_id)) {
			return false;
		}

		if (!parent::doCheck()) {
			return false;
		}

		if ($this->tax_rate < 0) {
			$this->setError(Text::_('COM_REDSHOP_TAX_RATE_INVALID_INPUT_MSG'));

			return false;
		}

		return true;
	}

	/**
	 * Do the database store.
	 *
	 * @param   boolean  $updateNulls  True to update null values as well.
	 *
	 * @return  boolean
	 */
	protected function doStore($updateNulls = false)
	{
		$db = JFactory::getDbo();

		if (!parent::doStore($updateNulls)) {
			return false;
		}

		$data = JFactory::getApplication()->input->post->get('jform', [], 'array');

		if (!$data['id']) {
			$taxid = $this->id;
		} else {
			$taxid = $data['id'];

			$deleteQuery = $db->getQuery(true)
				->delete($db->qn('#__redshop_tax_shoppergroup_xref'))
				->where($db->qn('tax_rate_id') . ' = ' . $db->q($taxid));

			$db->setQuery($deleteQuery)->execute();
		}

		$shopperGroups = array_unique($data['shopper_group']);

		foreach ($shopperGroups as $index => $shopperGroup) {
			$query = $db->getQuery(true)
				->insert($db->qn('#__redshop_tax_shoppergroup_xref'))
				->set($db->qn('tax_rate_id') . ' = ' . $db->q($taxid))
				->set($db->qn('shopper_group_id') . ' = ' . $db->q($shopperGroup));

			$result = $db->setQuery($query)->execute();
		}

		return $result;
	}

	/**
	 * Delete one or more registers
	 *
	 * @param   mixed $pk The string/array of id's to delete
	 *
	 * @return  boolean  Deleted successfuly?
	 */
	protected function doDelete($pk = null)
	{
		$db         = JFactory::getDbo();
		$taxRateIds = $pk;

		if (!is_array($taxRateIds)) {
			$taxRateIds = array($taxRateIds);
		}

		foreach ($taxRateIds as $taxRateId) {
			//Delete stock of product stock
			$queryProduct = $db->getQuery(true)
				->delete($db->qn('#__redshop_tax_shoppergroup_xref'))
				->where($db->qn('tax_rate_id') . ' = ' . $db->q($taxRateId));

			if (!$db->setQuery($queryProduct)->execute()) {
				/** @scrutinizer ignore-deprecated */$this->setError(Text::_('COM_REDSHOP_TAX_RATE_DELETE_UNSUCCESSFULY'));
				return false;
			}
		}

		return parent::doDelete($pk);
	}
}