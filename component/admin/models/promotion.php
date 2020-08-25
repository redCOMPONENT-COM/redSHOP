<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model promotion
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       __DEPLOY_VERSION__
 */
class RedshopModelPromotion extends RedshopModelForm
{
    /**
     * Method to save the form data.
     *
     * @param   array  $data  The form data.
     *
     * @return  boolean  True on success, False on error.
     *
     * @since   1.6
     */
    public function save($data)
    {
        $post = JFactory::getApplication()->input->post->getArray();

        JPluginHelper::importPlugin('redshop_promotion', $data['type']);
        $dispatcher = RedshopHelperUtility::getDispatcher();
        $data = $dispatcher->trigger('onSavePromotion', [$post])[0];

        //JFactory::getApplication()->enqueueMessage('OK', 'success');

        return parent::save($data);
    }
}
