<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * element for default product layout
 *
 * @package        Joomla
 * @subpackage     redSHOP
 * @since          1.5
 */

class JFormFieldProducts extends JFormField
{
	/**
	 * Element name
	 *
	 * @access    Public
	 * @var        string
	 */
	public $type = 'Products';

	protected function getInput()
	{
		$name      = $this->name;
		$fieldName = $this->name;

		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/tables');

		$product = JTable::getInstance('product_detail', 'Table');

		if ($this->value)
		{
			$product->load($this->value);
		}
		else
		{
			$product->product_name = JText::_('COM_REDSHOP_SELECT_A_PRODUCT');
		}

		JFactory::getDocument()->addScriptDeclaration("
			function jSelectProduct(id, title, object) {
				document.getElementById(object + '_id').value = id;
				document.getElementById(object + '_name').value = title;
				window.parent.SqueezeBox.close();
			}
		");

		$link = 'index.php?option=com_redshop&amp;view=product&amp;layout=element&amp;tmpl=component&amp;object=' . $name;

		JHTML::_('behavior.modal', 'a.modal');

		$value = htmlspecialchars($product->product_name, ENT_QUOTES, 'UTF-8');
		$attributes [] = 'style="background: #ffffff;"';
		$attributes [] = ($this->required) ? 'required="required"' : '';
		$class [] = ($this->required) ? 'required=' : '';
		$attributes = array_merge($attributes, $class);
		$attributes = trim(implode(' ', array_unique($attributes)));

		$html = '<div style="float: left;">';
		$html .= '<input type="text" id="' . $name . '_name" value="' . $value . '" ' . 'disabled="disabled"' . ' />';
		$html .= '</div>';
		$html .= '<div class="button2-left">';
		$html .= '<div class="blank">';
		$html .= '<a class="modal btn btn-primary" title="' . JText::_('COM_REDSHOP_SELECT_A_PRODUCT') . '"  href="' . $link . '" rel="{handler: \'iframe\', size: {x: 800, y: 500}}">' . JText::_('COM_REDSHOP_Select') . '</a>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '<input type="hidden" id="' . $name . '_id" name="' . $fieldName . '" value="' . (int) $this->value . '"' . $attributes . ' />';

		return $html;
	}
}
