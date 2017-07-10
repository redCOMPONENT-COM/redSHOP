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

        if (JRequest::getCmd('view') != 'product')
        {
            return;
        }

        // Assign paths
        $sitePath = JPATH_SITE;
        $siteUrl = JURI::base(true);

        // Check if plugin is enabled
        if (JPluginHelper::isEnabled('system', $this->plg_name) == false)
        {
            return;
        }

        if (JRequest::getCmd('option') == "com_redshop"
            && JRequest::getCmd('tmpl') != "component")
        {
            $document = JFactory::getDocument();
            $headerstuff = $document->getHeadData();
            $scripts = $headerstuff['scripts'];

            foreach ($scripts as $path => $val)
            {
                if (strpos($path, 'attribute.js') !== false)
                {
                    unset($scripts[$path]);
                }
            }

            $headerstuff['scripts'] = $scripts;
            $document->setHeadData($headerstuff);

			JHtml::_('redshopjquery.framework');
            $document->addScript('plugins/system/' . $this->plg_name . '/' . $this->plg_name . '/phoswipe/klass.min.js');
            $document->addScript('plugins/system/' . $this->plg_name . '/' . $this->plg_name . '/phoswipe/photoswipe.js');
            $document->addScript('plugins/system/' . $this->plg_name . '/' . $this->plg_name . '/slimbox/slimbox2.js');
			JHtml::script('com_redshop/attribute.js', false, true);
            $document->addScript('plugins/system/' . $this->plg_name . '/' . $this->plg_name . '/redlightbox.js');

            $document->addStyleSheet('plugins/system/' . $this->plg_name . '/' . $this->plg_name . '/phoswipe/photoswipe.css');
            $document->addStyleSheet('plugins/system/' . $this->plg_name . '/' . $this->plg_name . '/slimbox/slimbox2.css');
        }
    }
}

