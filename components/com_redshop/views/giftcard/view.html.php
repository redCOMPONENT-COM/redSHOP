<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('restricted access');

require_once(JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'product.php');

class giftcardViewgiftcard extends JViewLegacy
{
    public function display($tpl = null)
    {
        global $mainframe;

        // Request variables
        $option = JRequest::getVar('option');
        $params = $mainframe->getParams($option);
        JHTML::Script('redBOX.js', 'components/com_redshop/assets/js/', false);
        JHTML::Script('common.js', 'components/com_redshop/assets/js/', false);
        JHTML::Script('attribute.js', 'components/com_redshop/assets/js/', false);
        JHTML::Stylesheet('fetchscript.css', 'components/com_redshop/assets/css/');

        $pageheadingtag = JText::_('COM_REDSHOP_REDSHOP');

        $model             = $this->getModel('giftcard');
        $giftcard_template = $model->getGiftcardTemplate();
        $detail            = $this->get('data');

        $this->assignRef('detail', $detail);
        $this->assignRef('lists', $lists);
        $this->assignRef('template', $giftcard_template);
        $this->assignRef('pageheadingtag', $pageheadingtag);
        $this->assignRef('params', $params);

        parent::display($tpl);
    }
}

