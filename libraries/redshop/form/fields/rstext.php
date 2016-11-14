<?php
/**
 * @package     Redshop
 * @subpackage  Fields
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Text field.
 *
 * @package     Redshop
 * @subpackage  Fields
 * @since       __DEPLOY_VERSION__
 */
class JFormFieldRstext extends JFormField
{
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
		'id', 'default', 'description', 'disabled', 'name', 'multiple',
		'placeholder', 'readonly', 'required', 'type', 'value'
	);

	/**
	 * The form field type.
	 *
	 * @var  string
	 */
	protected $type = 'RSText';

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
				$html .= '<div class="multiple_input_wrapper"><input ' . $this->parseAttributes() . ' /></div>';
			}

			return $html;
		}

		$this->attribs['value'] = htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8');

		return '<input ' . $this->parseAttributes() . ' />';
	}

	/**
	 * Function to parse the attributes of the input field
	 *
	 * @return  string  Attributes in format: type="text" name="name" value="2"
	 */
	protected function parseAttributes()
	{
		$attributes = array();

		if (!empty($this->attribs))
		{
			foreach ($this->attribs as $name => $value)
			{
				if (!is_null($value))
				{
					$attributes[] = $name . '="' . $value . '"';
				}
			}

			$attributes = implode(' ', $attributes);
		}

		return $attributes;
	}
}
