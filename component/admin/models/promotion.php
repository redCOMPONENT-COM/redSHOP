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
        # Step 1: get data from Post
        $post = \Joomla\CMS\Factory::getApplication()->input->post->getArray();

        # Step 2: validate data
        $check = \Redshop\Plugin\Helper::invoke('redshop_promotion',
            $data['type'],
            'onValidate',
            [$post])[0];

        if (!$check->isValid) {
            /** @scrutinizer ignore-deprecated */
            $this->setError('<br />' . implode('<br />', $check->errorMessage));
            return false;
        }

        # Step 3: If pass validate, do save;
        $data = \Redshop\Plugin\Helper::invoke('redshop_promotion',
            $data['type'],
            'onSavePromotion',
            [$post])[0];

        return parent::save($data);
    }
}
