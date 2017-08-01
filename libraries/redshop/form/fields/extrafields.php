<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Form.Field
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Field to create extra field list dynamically
 * based on `field_section` and `field_show_in_front` etc...
 *
 * Example:
 *
 * <field
 *		name="privatePNO"
 *		type="extrafields"
 *		field_section="7"
 *		value_field="field_id"
 *		text_field="CONCAT(field_title, ' (', field_name, ')')"
 *		required="true"
 *		label="PLG_REDSHOP_PAYMENT_KLARNA_PRIVATE_BILLING"
 *		description="PLG_REDSHOP_PAYMENT_KLARNA_PRIVATE_BILLING_DESC"
 *	/>
 *
 * @package     RedSHOP.Library
 * @subpackage  Form.Field
 * @since       1.5
 */
class JFormFieldExtraFields extends JFormFieldList
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	protected $type = 'extrafields';

	/**
	 * Extra Field Section Id for field_section database column
	 *
	 * @access    protected
	 * @var       integer
	 */
	protected $fieldSection;

	/**
	 * Extra Field Show In Front flag value for field_show_in_front database column
	 *
	 * @access    protected
	 * @var       integer
	 */
	protected $fieldShowInFront;

	/**
	 * Extra Field Publishing flag value for published database column
	 *
	 * @access    protected
	 * @var       integer
	 */
	protected $published;

	/**
	 * Database Query Select value to set as element value
	 *
	 * @access    protected
	 * @var       integer
	 */
	protected $valueField;

	/**
	 * Database Query Select value to set as element text
	 *
	 * @access    protected
	 * @var       integer
	 */
	protected $textField;

	protected $extraFields = array();

	/**
	 * Get Extra field info as an option
	 *
	 * @return  array  Extra Field list
	 */
	protected function getOptions()
	{
		$this->fieldSection     = isset($this->element['section']) ? (int) $this->element['section'] : 1;
		$this->fieldShowInFront = isset($this->element['show_in_front']) ? (int) $this->element['show_in_front'] : 1;
		$this->published        = isset($this->element['published']) ? (int) $this->element['published'] : 1;

		// Dynamic query select options
		$this->valueField = isset($this->element['value_field']) ? (string) $this->element['value_field'] : 'name';
		$this->textField  = isset($this->element['text_field']) ? (string) $this->element['text_field'] : 'title';

		return array_merge(
			parent::getOptions(),
			$this->getExtraFields()
		);
	}

	/**
	 * Get Extra Fields using sections.
	 *
	 * @return  array  Extra Fields list
	 */
	protected function getExtraFields()
	{
		$db = JFactory::getDbo();

		$key = $this->fieldSection . $this->fieldShowInFront . $this->published;

		if (array_key_exists($key, $this->extraFields))
		{
			return $this->extraFields[$key];
		}

		// Create the base select statement.
		$query = $db->getQuery(true)
			->select(
				array(
					$this->valueField . ' as value',
					$this->textField . ' as text'
				)
			)
			->from($db->qn('#__redshop_fields'))
			->where($db->qn('published') . ' = ' . (int) $this->published)
			->where($db->qn('show_in_front') . ' = ' . (int) $this->fieldShowInFront)
			->where($db->qn('section') . ' = ' . (int) $this->fieldSection)
			->order($db->qn('ordering') . ' ASC');

		// Set the query and load the result.
		$db->setQuery($query);

		$this->extraFields[$key] = $db->loadObjectList();

		return $this->extraFields[$key];
	}
}
