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
 * Editor redSHOP product button
 *
 * @package  Editors-xtd
 * @since    1.5
 */
class PlgButtonProduct extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An array that holds the plugin configuration
	 *
	 * @since   1.5
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * Display the button
	 *
	 * @param   string  $name  The name of the button to add
	 *
	 * @return array A two element array of (imageName, textToInsert)
	 */
	public function onDisplay($name)
	{
		$doc = JFactory::getDocument();

		$js = "
		function jSelectProduct(id, title, object) {
			var tag = '{redshop:'+id+'}';
			window.parent.jInsertEditorText(tag, object);
			window.parent.SqueezeBox.close();
		}";
		$doc->addScriptDeclaration($js);

		$link = 'index.php?option=com_redshop&amp;view=product&amp;layout=element&amp;tmpl=component&amp;object=' . $name . '&' . JSession::getFormToken() . '=1';

		JHTML::_('behavior.modal');

		$button = new JObject;
		$button->set('modal', true);
		$button->set('class', 'btn');
		$button->set('link', $link);
		$button->set('text', JText::_('PLG_EDITORS-XTD_PRODUCT_BUTTON_TEXT'));
		$button->set('name', 'file-add article');
		$button->set('options', "{handler: 'iframe', size: {x: 800, y: 500}}");

		return $button;
	}
}
