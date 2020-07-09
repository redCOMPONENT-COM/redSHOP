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
class RedshopViewRating extends RedshopViewForm
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
        $input = JFactory::getApplication()->input;

        if ($field->getAttribute('name') === "time") {
            return false;
        }

        if ($field->getAttribute('name') === "user_rating") {
            return RedshopLayoutHelper::render(
                'rating.star_rating',
                ['userRating' => $field->value]
            );
        }

        return parent::prepareField($field);
    }
}
