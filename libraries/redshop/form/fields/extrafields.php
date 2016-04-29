<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Form.Field
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Field to create extra field list dynamically
 * based on `field_section` and `field_show_in_front` etc...
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

	/**
	 * Get Extra field info as an option
	 *
	 * @return  array  Extra Field list
	 */
	protected function getOptions()
	{
		// Initialiase variables.
		$db = JFactory::getDbo();

		$this->fieldSection     = isset($this->element['field_section']) ? (int) $this->element['field_section'] : 1;
		$this->fieldShowInFront = isset($this->element['field_show_in_front']) ? (int) $this->element['field_show_in_front'] : 1;
		$this->published        = isset($this->element['published']) ? (int) $this->element['published'] : 1;

		// Dynamic query select options
		$this->valueField = isset($this->element['value_field']) ? (string) $this->element['value_field'] : 'field_name';
		$this->textField  = isset($this->element['text_field']) ? (string) $this->element['text_field'] : 'field_title';

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
			->where($db->qn('field_show_in_front') . ' = ' . (int) $this->fieldShowInFront)
			->where($db->qn('field_section') . ' = ' . (int) $this->fieldSection)
			->order($db->qn('ordering') . ' ASC');

		// Set the query and load the result.
		$db->setQuery($query);

		return array_merge(
			parent::getOptions(),
			$db->loadObjectList()
		);
	}
}
