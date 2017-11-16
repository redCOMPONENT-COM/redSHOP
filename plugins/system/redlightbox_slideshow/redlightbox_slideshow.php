<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Redshop Lightbox Slideshow
 *
 * @since  2.0
 */
class PlgSystemRedlightbox_Slideshow extends JPlugin
{
	/**
	 * This event is triggered immediately before pushing the document buffers into the template placeholders,
	 * retrieving data from the document and pushing it into the into the JResponse buffer.
	 * http://docs.joomla.org/Plugin/Events/System
	 *
	 * @return void
	 */
	public function onBeforeRender()
	{
		$app = JFactory::getApplication();

		// No lightbox for admin
		if ($app->isAdmin() || $app->input->getCmd('view') != 'product'
			|| $app->input->getCmd('option') != "com_redshop" || $app->input->getCmd('tmpl') == "component")
		{
			return;
		}

		$document = JFactory::getDocument();
		$scripts  = $document->_scripts;

		foreach ($scripts as $path => $val)
		{
			if (strpos($path, 'attribute.js') !== false || strpos($path, 'attribute-uncompressed.js') !== false)
			{
				unset($scripts[$path]);
			}
		}

		JHtml::_('redshopjquery.framework');

		JHtml::stylesheet('plg_system_redlightbox_slideshow/slimbox2/slimbox2.min.css', array(), true);
		JHtml::stylesheet('plg_system_redlightbox_slideshow/photoswipe/photoswipe.min.css', array(), true);
		JHtml::stylesheet('plg_system_redlightbox_slideshow/photoswipe/default-skin/default-skin.min.css', array(), true);

		JHtml::script('plg_system_redlightbox_slideshow/photoswipe/photoswipe.min.js', false, true, false, false);
		JHtml::script('plg_system_redlightbox_slideshow/photoswipe/photoswipe-ui-default.min.js', false, true, false, false);
		JHtml::script('plg_system_redlightbox_slideshow/slimbox2/slimbox2.min.js', false, true, false, false);


		JHtml::script('com_redshop/attribute.js', false, true, false, false);
		JHtml::script('plg_system_redlightbox_slideshow/redlightbox.js', false, true, false, false);
	}
}

