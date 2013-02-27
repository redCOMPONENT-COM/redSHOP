<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('restricted access');

class registrationViewregistration extends JViewLegacy
{
    public function display($tpl = null)
    {
        global $mainframe;

        $option = JRequest::getVar('option');
        $Itemid = JRequest::getVar('Itemid');

        $user    = JFactory::getUser();
        $session = JFactory::getSession();
        $auth    = $session->get('auth');
        if ($user->id || (isset($auth['users_info_id']) && $auth['users_info_id'] > 0))
        {
            $mainframe->Redirect('index.php?option=' . $option . '&view=account&Itemid=' . $Itemid);
        }

        $params = $mainframe->getParams('com_redshop');
        JHTML::Script('joomla.javascript.js', 'includes/js/', false);
        JHTML::Script('jquery-1.4.2.min.js', 'components/com_redshop/assets/js/', false);
        JHTML::Script('jquery.validate.js', 'components/com_redshop/assets/js/', false);
        JHTML::Script('common.js', 'components/com_redshop/assets/js/', false);
        JHTML::Script('jquery.metadata.js', 'components/com_redshop/assets/js/', false);
        JHTML::Script('registration.js', 'components/com_redshop/assets/js/', false);
        JHTML::Stylesheet('validation.css', 'components/com_redshop/assets/css/');

        $field                        = new extraField();
        $lists['extra_field_user']    = $field->list_all_field(7); // field_section 7 : Customer Registration
        $lists['extra_field_company'] = $field->list_all_field(8); // field_section 8 : Company Address

        $this->assignRef('lists', $lists);
        $this->assignRef('params', $params);
        parent::display($tpl);
    }
}
