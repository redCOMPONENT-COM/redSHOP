<?php
/**
 * @version		$Id: pagebreak.php 10709 2008-08-21 09:58:52Z eddieajau $
 * @package		Joomla
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );


/**
 * Editor Pagebreak buton
 *
 * @package Editors-xtd
 * @since 1.5
 */
class plgButtonproduct extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param object $subject The object to observe
	 * @param 	array  $config  An array that holds the plugin configuration
	 * @since 1.5
	 */
	function plgButtonproduct(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}

	/**
	 * Display the button
	 *
	 * @return array A two element array of ( imageName, textToInsert )
	 */
	function onDisplay($name)
	{
		$mainframe =& JFactory::getApplication();

		$doc = & JFactory::getDocument();

		$js = "
		function jSelectProduct(id, title, object) {

			var tag = '{redshop:'+id+'}';
			window.parent.jInsertEditorText(tag, object);
			window.parent.SqueezeBox.close();

		}";
		$doc->addScriptDeclaration($js);

		//$doc->addStyleSheet( JURI::root( true ).'/plugins/editors-xtd/product/css/product.css' );

		$template = $mainframe->getTemplate();
		$link = 'index.php?option=com_redshop&amp;view=product_mini&amp;tmpl=component&amp;e_name='.$name;
		if($mainframe->isAdmin()){
			$link = '../index.php?option=com_redshop&amp;view=product_mini&amp;tmpl=component&amp;e_name='.$name;
		}

		$link = 'index.php?option=com_redshop&amp;view=product&amp;task=element&amp;tmpl=component&amp;object='.$name;


		JHTML::_('behavior.modal');

		$button = new JObject();
		$button->set('modal', true);
		$button->set('link', $link);
		$button->set('text', JText::_('COM_REDSHOP_PRODUCT'));
		$button->set('name', 'pagebreak');
		$button->set('options', "{handler: 'iframe', size: {x: 600, y: 500}}");

		return $button;
	}
}