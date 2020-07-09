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
 * @since       2.1.0
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
     * @since   2.1.0
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
