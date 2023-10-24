<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/**
 * Controller Zipcode Detail
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 * @since       2.1.3
 */
class RedshopControllerZipcode extends RedshopControllerForm
{
    /**
     * Ajax get state 2 code
     *
     * @return  void
     *
     * @since   2.1.3
     */
    public function ajaxGetState2Code()
    {
        JSession::checkToken() or jexit(Text::_('JINVALID_TOKEN'));
        $model = $this->getModel();
        $form  = $model->getForm();
        echo $form->renderField('state_code');
        jexit();
    }
}