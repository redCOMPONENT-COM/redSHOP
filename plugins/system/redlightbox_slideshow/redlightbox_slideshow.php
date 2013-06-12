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

            $document->addScript('plugins/system/' . $this->plg_name . '/' . $this->plg_name . '/jquery/jquery.min.js');
            $document->addScript('plugins/system/' . $this->plg_name . '/' . $this->plg_name . '/phoswipe/klass.min.js');
            $document->addScript('plugins/system/' . $this->plg_name . '/' . $this->plg_name . '/phoswipe/photoswipe.js');
            $document->addScript('plugins/system/' . $this->plg_name . '/' . $this->plg_name . '/slimbox/slimbox2.js');
            $document->addScript('components/com_redshop/assets/js/attribute.js');
            $document->addScript('plugins/system/' . $this->plg_name . '/' . $this->plg_name . '/redlightbox.js');

            $document->addStyleSheet('plugins/system/' . $this->plg_name . '/' . $this->plg_name . '/phoswipe/photoswipe.css');
            $document->addStyleSheet('plugins/system/' . $this->plg_name . '/' . $this->plg_name . '/slimbox/slimbox2.css');
        }
    }
}

