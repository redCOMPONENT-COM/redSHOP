<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Editor Pagebreak buton
 *
 * @package Editors-xtd
 * @since   1.5
 */
class plgButtonproduct extends JPlugin
{
	/**
	 * Display the button
	 *
	 * @return array A two element array of ( imageName, textToInsert )
	 */
	public function onDisplay($name)
	{
		$app = JFactory::getApplication();

		$doc = JFactory::getDocument();

		$js = "
		function jSelectProduct(id, title, object) {
			var tag = '{redshop:'+id+'}';
			window.parent.jInsertEditorText(tag, object);
			window.parent.SqueezeBox.close();
		}";
		$doc->addScriptDeclaration($js);

		$template = $app->getTemplate();
		$link = 'index.php?option=com_redshop&amp;view=product_mini&amp;tmpl=component&amp;e_name=' . $name;

		if ($app->isAdmin())
		{
			$link = '../index.php?option=com_redshop&amp;view=product_mini&amp;tmpl=component&amp;e_name=' . $name;
		}

		$link = 'index.php?option=com_redshop&amp;view=product&amp;task=element&amp;tmpl=component&amp;object=' . $name;

		JHTML::_('behavior.modal');

		$button = new JObject;
		$button->set('modal', true);
		$button->set('link', $link);
		$button->set('text', JText::_('COM_REDSHOP_PRODUCT'));
		$button->set('name', 'pagebreak');
		$button->set('options', "{handler: 'iframe', size: {x: 600, y: 500}}");

		return $button;
	}
}
