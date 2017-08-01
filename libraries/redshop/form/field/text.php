<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Form.Field
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once JPATH_LIBRARIES . '/redshop/library.php';

/**
 * Text field override
 *
 * @since  1.0
 */
class RedshopFormFieldText extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $type = 'Text';

	/**
	 * Input field attributes
	 *
	 * @var  array
	 */
	protected $attribs = array();

	/**
	 * Attributes not allowed to use in field definition
	 *
	 * @var  array
	 */
	protected $forbiddenAttributes = array(
		'id', 'default', 'description', 'disabled', 'name', 'multiple', 'placeholder', 'type', 'value'
	);

	/**
	 * Add an attribute to the input field
	 *
	 * @param   string  $name   Name of the attribute
	 * @param   string  $value  Value for the attribute
	 *
	 * @return  void
	 */
	protected function addAttribute($name, $value)
	{
		if (!is_null($value))
		{
			$name = strtolower($name);

			$this->attribs[$name] = (string) $value;
		}
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		// Manually handled attributes
		$this->attribs['id'] = $this->id;
		$this->attribs['name'] = $this->name;
		$this->attribs['type'] = 'text';
		$this->attribs['readonly'] = ($this->element['readonly'] == 'true') ? 'readonly' : null;
		$this->attribs['disabled'] = ($this->element['disabled'] == 'true') ? 'disabled' : null;
		$this->attribs['placeholder'] = $this->element['placeholder'] ? JText::_($this->element['placeholder']) : null;

		if (isset($this->element['filter']) && ($this->element['filter'] == 'float' || $this->element['filter'] == 'integer'))
		{
			$this->attribs['type'] = 'number';
		}

		// Automatically insert any other attribute inserted
		if ($elementAttribs = $this->element->attributes())
		{
			foreach ($elementAttribs as $name => $value)
			{
				if (!in_array($name, $this->forbiddenAttributes))
				{
					$this->addAttribute($name, $value);
				}
			}
		}

		$html = '';

		if ($this->multiple == 1 && is_array($this->value))
		{
			foreach ($this->value AS $value)
			{
				$this->attribs['value'] = htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
				$html .= '<div class="multiple_input_wrapper"><input ' . RedshopHelperUtility::toAttributes($this->attribs) . ' /></div>';
			}

			return $html;
		}

		$this->attribs['value'] = htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8');

		return '<input ' . RedshopHelperUtility::toAttributes($this->attribs) . ' />';
	}
}
