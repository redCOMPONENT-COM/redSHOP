<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/**
 * View zipcode
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       __DEPLOY_VERSION__
 */
class RedshopViewShopperGroup extends RedshopViewForm
{
    /**
     * Method for prepare field HTML
     *
     * @param object $field Group object
     *
     * @return  boolean|string  False if keep. String for HTML content if success.
     *
     * @throws \Exception
     * @since   __DEPLOY_VERSION__
     */
    protected function prepareField($field)
    {
        switch ($field->getAttribute('name')) {
            case 'url':
                return RedshopLayoutHelper::render(
                    'shopper_group.field_url',
                    [
                        'label'          => $field->label,
                        'shopperGroupId' => $this->item->id
                    ]
                );
            case 'logo':
                return RedshopLayoutHelper::render(
                    'shopper_group.logo',
                    [
                        'item' => $this->item,
                    ]
                );
            default:
                return parent::prepareField($field);
        }
    }
}
