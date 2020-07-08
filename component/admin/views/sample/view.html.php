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
 * View Sample
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       __DEPLOY_VERSION__
 */
class RedshopViewSample extends RedshopViewForm
{
    /**
     * Method for prepare field HTML
     *
     * @param   object  $field  Group object
     *
     * @return  boolean|string  False if keep. String for HTML content if success.
     *
     * @throws \Exception
     * @since   __DEPLOY_VERSION__
     */
    protected function prepareField($field)
    {
        $input = JFactory::getApplication()->input;
        $id    = $input->getInt('id', '');

        /* @var \RedshopModelSample $model*/
        $model = $this->getModel();

        if ($id && $field->getAttribute('name') == 'catalog_color') {
            $lists = $model->getColorData($id);

            return RedshopLayoutHelper::render(
                'sample.catalog_color',
                ['lists' => $lists]
            );
        }

        return parent::prepareField($field);
    }
}
