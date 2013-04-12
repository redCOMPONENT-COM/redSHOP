<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
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
		$doc = JFactory::getDocument();
		$name = $this->name;
		$fieldName = $this->name; //$this->control_name.'['.$name.']';

		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/tables');

		$product = JTable::getInstance('product_detail', 'Table');
		$value = $this->value;
		if ($value)
		{
			$product->load($value);
		}
		else
		{
			$product->product_name = JText::_('COM_REDSHOP_SELECT_A_PRODUCT');
		}

		$js = "
		function jSelectProduct(id, title, object) {
			document.getElementById(object + '_id').value = id;
			document.getElementById(object + '_name').value = title;
			window.parent.SqueezeBox.close();
		}";
		$doc->addScriptDeclaration($js);

		$link = 'index.php?option=com_redshop&amp;view=product&amp;task=element&amp;tmpl=component&amp;object=' . $name;

		JHTML::_('behavior.modal', 'a.modal');
		$html = "\n" . '<div style="float: left;"><input style="background: #ffffff;" type="text" id="' . $name . '_name" value="' . htmlspecialchars($product->product_name, ENT_QUOTES, 'UTF-8') . '" disabled="disabled" /></div>';
		$html .= '<div class="button2-left"><div class="blank"><a class="modal" title="' . JText::_('COM_REDSHOP_SELECT_A_PRODUCT') . '"  href="' . $link . '" rel="{handler: \'iframe\', size: {x: 650, y: 375}}">' . JText::_('COM_REDSHOP_Select') . '</a></div></div>' . "\n";
		$html .= "\n" . '<input type="hidden" id="' . $name . '_id" name="' . $fieldName . '" value="' . (int) $value . '" />';

		return $html;
	}
}

?>
