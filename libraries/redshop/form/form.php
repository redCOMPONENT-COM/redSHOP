<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       __DEPLOY_VERSION__
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * Class Redshop Helper for Access Level
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopForm extends JForm
{
	/**
	 * Method to get an instance of a form.
	 *
	 * @param   string          $name     The name of the form.
	 * @param   string          $data     The name of an XML file or string to load as the form definition.
	 * @param   array           $options  An array of form options.
	 * @param   boolean         $replace  Flag to toggle whether form fields should be replaced if a field
	 *                                    already exists with the same group/name.
	 * @param   string|boolean  $xpath    An optional xpath to search for the fields.
	 *
	 * @return  JForm  JForm instance.
	 *
	 * @since   11.1
	 * @throws  InvalidArgumentException if no data provided.
	 * @throws  RuntimeException if the form could not be loaded.
	 */
	public static function getInstance($name, $data = null, $options = array(), $replace = true, $xpath = false)
	{
		// Reference to array with form instances
		/** @var  RedshopForm[] $forms */
		$forms = &self::$forms;

		// Only instantiate the form if it does not already exist.
		if (!isset($forms[$name]))
		{
			$data = trim($data);

			if (empty($data))
			{
				throw new InvalidArgumentException(sprintf('RedshopForm::getInstance(name, *%s*)', gettype($data)));
			}

			// Instantiate the form.
			$forms[$name] = new RedshopForm($name, $options);

			// Load the data.
			if (substr(trim($data), 0, 1) == '<')
			{
				if ($forms[$name]->load($data, $replace, $xpath) == false)
				{
					throw new RuntimeException('RedshopForm::getInstance could not load form');
				}
			}
			else
			{
				if ($forms[$name]->loadFile($data, $replace, $xpath) == false)
				{
					throw new RuntimeException('RedshopForm::getInstance could not load file');
				}
			}
		}

		return $forms[$name];
	}

	/**
	 * Override the validate() method to store the error per field.
	 *
	 * @param   array   $data   An array of field values to validate.
	 * @param   string  $group  The optional dot-separated form group path on which to filter the
	 *                          fields to be validated.
	 *
	 * @return  mixed  True on sucess.
	 */
	public function validate($data, $group = null)
	{
		// Make sure there is a valid JForm XML document.
		if (!($this->xml instanceof SimpleXMLElement))
		{
			return false;
		}

		$return = true;

		// Create an input registry object from the data to validate.
		$input = new Registry($data);

		// Get the fields for which to validate the data.
		$fields = $this->findFieldsByGroup($group);

		if (!$fields)
		{
			// PANIC!
			return false;
		}

		/** @var $field SimpleXMLElement */
		foreach ($fields as $field)
		{
			$value = null;
			$name = (string) $field['name'];

			// Get the group names as strings for ancestor fields elements.
			$attrs = $field->xpath('ancestor::fields[@name]/@name');
			$groups = array_map('strval', $attrs ? $attrs : array());
			$group = implode('.', $groups);

			// Get the value from the input data.
			if ($group)
			{
				$value = $input->get($group . '.' . $name);
			}
			else
			{
				$value = $input->get($name);
			}

			// Validate the field.
			$valid = $this->validateField($field, $group, $value, $input);

			// Check for an error.
			if ($valid instanceof Exception)
			{
				if ($group)
				{
					$this->errors[$group . '.' . $name] = $valid;
				}
				else
				{
					$this->errors[$name] = $valid;
				}

				$return = false;
			}
		}

		return $return;
	}

	/**
	 * Method to get the error for a field input.
	 *
	 * @param   string  $name   The name of the form field.
	 * @param   string  $group  The optional dot-separated form group path on which to find the field.
	 *
	 * @return  string  The form field error.
	 */
	public function getError($name, $group = null)
	{
		if ($group)
		{
			$name = $group . '.' . $name;
		}

		if (isset($this->errors[$name]))
		{
			return $this->errors[$name];
		}

		return '';
	}

	/**
	 * Returns the value of an attribute of the form itself
	 *
	 * @param   string  $attribute  The name of the attribute
	 * @param   mixed   $default    Optional default value to return
	 *
	 * @return  mixed  The attribute value.
	 */
	public function getAttribute($attribute, $default = null)
	{
		$value = $this->xml->attributes()->$attribute;

		if (is_null($value))
		{
			return $default;
		}
		else
		{
			return (string) $value;
		}
	}

	/**
	 * Method to get the XML form object
	 *
	 * @return  SimpleXMLElement  The form XML object
	 *
	 * @since   3.2
	 */
	public function getXml()
	{
		return $this->xml;
	}

	/**
	 * Method to validate a JFormField object based on field data.
	 *
	 * @param   SimpleXMLElement  $element  The XML element object representation of the form field.
	 * @param   string            $group    The optional dot-separated form group path on which to find the field.
	 * @param   mixed             $value    The optional value to use as the default for the field.
	 * @param   Registry          $input    An optional JRegistry object with the entire data set to validate
	 *                                      against the entire form.
	 *
	 * @return  mixed  Boolean true if field value is valid, Exception on failure.
	 *
	 * @throws  InvalidArgumentException
	 * @throws  UnexpectedValueException
	 */
	protected function validateField(SimpleXMLElement $element, $group = null, $value = null, Registry $input = null)
	{
		$valid = true;

		// Check if the field is required.
		$required = ((string) $element['required'] == 'true' || (string) $element['required'] == 'required');

		if ($required)
		{
			// If the field is required and the value is empty return an error message.
			if (($value === '') || ($value === null))
			{
				if ($element['label'])
				{
					$message = JText::_($element['label']);
				}
				else
				{
					$message = JText::_($element['name']);
				}

				$message = JText::sprintf('JLIB_FORM_VALIDATE_FIELD_REQUIRED', $message);

				return new RuntimeException($message);
			}
		}

		// Get the field validation rule.
		if ($type = (string) $element['validate'])
		{
			// Load the JFormRule object for the field.
			$rule = $this->loadRuleType($type);

			// If the object could not be loaded return an error message.
			if ($rule === false)
			{
				throw new UnexpectedValueException(sprintf('%s::validateField() rule `%s` missing.', get_class($this), $type));
			}

			// Run the field validation rule test.
			$valid = $rule->test($element, $value, $group, $input, $this);

			// Check for an error in the validation test.
			if ($valid instanceof Exception)
			{
				return $valid;
			}
		}

		// Check if the field is valid.
		if ($valid === false)
		{
			// Does the field have a defined error message?
			$message = (string) $element['message'];

			if ($message)
			{
				$message = JText::_($element['message']);

				// Trick to use attributes as an array
				$tags = current($element->attributes());
				$tags['value'] = $value;

				$message = RedshopHelperUtility::replace($message, $tags);

				return new UnexpectedValueException($message);
			}
			else
			{
				$message = JText::_($element['label']);
				$message = JText::sprintf('JLIB_FORM_VALIDATE_FIELD_INVALID', $message);

				return new UnexpectedValueException($message);
			}
		}

		return true;
	}
}
