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
        echo '<pre>';
        var_dump($post);
        var_dump($data);
        die;
        parent::save($data);
    }
}
