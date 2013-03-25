<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgSystemRedlightbox_slideshow extends JPlugin
{
	public $plg_name = "redlightbox_slideshow";

	public function onBeforeRender()
	{
		$app = JFactory::getApplication();

		// No redlighbox for admin
		if ($app->isAdmin())
		{
			return;
		}

		$view = JRequest::getCmd('view');

		if ($view != 'product')
		{
			return;
		}

		// Requests
		$option = JRequest::getCmd('option');
		$tmpl = JRequest::getCmd('tmpl');

		// Assign paths
		$sitePath = JPATH_SITE;
		$siteUrl = JURI::base(true);

		// Check if plugin is enabled
		if (JPluginHelper::isEnabled('system', $this->plg_name) == false)
		{
			return;
		}

		if ($option == "com_redshop" && $tmpl != "component")
		{
			$document =& JFactory::getDocument();
			$headerstuff = $document->getHeadData();
			$scripts = $headerstuff['scripts'];
			$jqueryfound = false;

			foreach ($scripts as $path => $val)
			{
				if (strpos($path, 'attribute.js') !== false)
				{
					unset($scripts[$path]);
					$jqueryfound = true;
				}
			}

			$headerstuff['scripts'] = $scripts;
			$document->setHeadData($headerstuff);
			JHTML::Script('jquery-1.4.4.min.js', 'plugins/system/' . $this->plg_name . '/' . $this->plg_name . '/jquery/', false);
			JHTML::Script('slimbox2.js', 'plugins/system/' . $this->plg_name . '/' . $this->plg_name . '/slimbox-2.04/js/', false);
			JHTML::Script('attribute.js', 'components/com_redshop/assets/js/', false);
			JHTML::Stylesheet('slimbox2.css', 'plugins/system/' . $this->plg_name . '/' . $this->plg_name . '/slimbox-2.04/css/');
			$document->addScriptDeclaration(
				'function preloadSlimbox(isenable)
				{
					if (!/android|iphone|ipod|series60|symbian|windows ce|blackberry/i.test(navigator.userAgent)) {
						jQuery(function($) {
							$("a[rel^=\'myallimg\']").attr("rel","lightbox[gallery]");
							$("a[rel^=\'lightbox\']").slimbox({/* Put custom options here */}, null, function(el) {
								return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
							});
						});
					}
				}'
			);
		}
	}
}

