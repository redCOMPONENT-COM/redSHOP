<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('restricted access');

require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'category.php');

class send_friendViewsend_friend extends JViewLegacy
{
    public function display($tpl = null)
    {
        global $mainframe;

        $params = $mainframe->getParams('com_redshop');

        // Include Javascript

        JHTML::Script('attribute.js', 'components/com_redshop/assets/js/', false);
        JHTML::Script('json.js', 'components/com_redshop/assets/js/', false);
        JHTML::Stylesheet('scrollable-navig.css', 'components/com_redshop/assets/css/');
        $data = $this->get('data');

        $template =& $this->get('template');

        // Next/Prev navigation end

        $this->assignRef('data', $data);
        $this->assignRef('template', $template);

        $this->assignRef('params', $params);

        parent::display($tpl);
    }
}
