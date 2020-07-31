<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/**
 * View newsletters
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       __DEPLOY_VERSION__
 */
class RedshopViewNewsletters extends RedshopViewList
{
    /**
     * Method for render column
     *
     * @param   array   $config  Row config.
     * @param   int     $index   Row index.
     * @param   object  $row     Row data.
     *
     * @return  string
     * @throws  Exception
     *
     * @since   __DEPLOY_VERSION__
     */
    public function onRenderColumn($config, $index, $row)
    {
        switch ($config['dataCol']) {
            case 'no_of_subscribers' :

                /** @var \RedshopModelNewsletters $model */
                $model = $this->getModel();
                return $model->noOfSubscribers($row->id);
            default:
                return parent::onRenderColumn($config, $index, $row);
        }
    }

    /**
     * Method for add toolbar.
     *
     * @return  void
     *
     * @since   __DEPLOY_VERSION__
     */
    public function addToolbar()
    {
        if ($this->getLayout() != 'previewlog')
        {
            JToolBarHelper::custom(
                'sendNewsletterPreview',
                'send.png',
                'send.png',
                JText::_('COM_REDSHOP_SEND_NEWSLETTER'),
                true
            );

            JToolBarHelper::custom('copy', 'copy.png', 'copy_f2.png', JText::_('COM_REDSHOP_TOOLBAR_COPY'), true);

            parent::addToolbar();
        }
    }
}