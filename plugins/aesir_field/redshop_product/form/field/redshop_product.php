<?php
/**
 * @package     Aesir.Plugin
 * @subpackage  Aesir_Field.Redshop_Product
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once JPATH_LIBRARIES . '/reditem/library.php';

use Aesir\Field\CustomField;
use Joomla\Utilities\ArrayHelper;

/**
 * Item Related field.
 *
 * @since  1.0.0
 */
class PlgAesir_FieldRedshop_ProductFormFieldRedshop_Product extends CustomField
{
	/**
	 * The form field type.
	 *
	 * @var  string
	 */
	protected $type = 'Redshop_Product';

	/**
	 * Cached array of options.
	 *
	 * @var    array
	 * @since  4.0.3
	 */
	protected static $options = array();

	/**
	 * Cached layout data.
	 *
	 * @var  1.0.6
	 */
	private $layoutData;

	/**
	 * Empty option text
	 *
	 * @var    string
	 * @since  1.0.8
	 */
	protected $emptyOptionText = 'PLG_AESIR_FIELD_REDSHOP_PRODUCT_EMPTY_OPTION';

	/**
	 * Get the data that is going to be passed to the layout
	 *
	 * @return  array
	 */
	protected function getLayoutData()
	{
		if (null === $this->layoutData || 1 == 1)
		{
			$data = parent::getLayoutData();
			$data['data'] = $this->getOptions($data['value']);

			// Attributes data
			if ($this->multiple === true)
			{
				$data['attribs']['multiple'] = 'true';
			}

			$data['attributes'] = \JArrayHelper::toString($data['attribs']);

			$this->layoutData = $data;
		}

		return $this->layoutData;
	}

	/**
	 * Method for get options of this field.
	 * 
	 * @param   array  $values  List of selected values
	 *
	 * @return  array           List of options
	 */
	private function getOptions($values)
	{
		$hash = md5($this->name . $this->element);
		$db = JFactory::getDBO();
		$query = $db->getQuery(true)
			->select($db->qn('product_id'))
			->select($db->qn('product_name'))
			->select($db->qn('product_number'))
			->from($db->qn('#__redshop_product'))
			->order($db->qn('product_name'));

		$items = $db->setQuery($query)->loadObjectList();

		$data = array();

		if ($this->addSelectOption)
		{
			$selected = (!empty($values) && in_array('', $values)) ? true : false;

			$data[] = array(
				'text' => \JText::_($this->emptyOptionText),
				'value' => '',
				'selected' => $selected
			);
		}

		if (empty($items))
		{
			return $data;
		}

		foreach ($items as $key => $item)
		{
			$selected = (!empty($values) && in_array($item->product_id, $values)) ? true : false;

			$data[] = array(
				'text'     => htmlspecialchars(trim($item->product_name . '(' . $item->product_number . ')'), ENT_COMPAT, 'UTF-8'),
				'value'    => htmlspecialchars(trim($item->product_id), ENT_COMPAT, 'UTF-8'),
				'selected' => $selected
			);
		}

		static::$options[$hash] = $data;

		return static::$options[$hash];
	}

	/**
	 * Method to attach a JForm object to the field.
	 *
	 * @param   \SimpleXMLElement  $element  The SimpleXMLElement object representing the <field /> tag for the form field object.
	 * @param   mixed              $value    The form field value to validate.
	 * @param   string             $group    The field name group control value. This acts as as an array container for the field.
	 *                                      For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                      full field name would end up being "bar[foo]".
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   11.1
	 */
	public function setup(\SimpleXMLElement $element, $value, $group = null)
	{
		if (!parent::setup($element, $value, $group))
		{
			return false;
		}

		if (!$this->multiple)
		{
			$this->__set('addSelectOption', $this->getAttribute('addSelectOption', 'false') === 'true');
		}

		return true;
	}
}
