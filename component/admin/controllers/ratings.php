<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/**
 * Ratings controller
 *
 * @package     RedSHOP.backend
 * @subpackage  Controller
 * @since       __DEPLPOY_VERSION__
 */
class RedshopControllerRatings extends RedshopControllerAdmin
{
    /**
     * Method to favour a list of rating items
     *
     * @return  void
     *
     * @since   __DEPLPOY_VERSION__
     */
    public function FVpublish()
    {
        $cid = $this->input->post->get('cid', array(0), 'array');

        if ( ! is_array($cid) || count($cid) < 1) {
            throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
        }

        $model = $this->getModel('rating');

        if ( ! \Redshop\Rating\Helper::setFavoured($cid, 1)) {
            echo "<script> alert('" . $model->getError() . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_RATING_DETAIL_PUBLISHED_SUCCESFULLY');
        $this->setRedirect('index.php?option=com_redshop&view=ratings', $msg);
    }

    /**
     * Method to disfavour a list of rating items
     *
     * @return  void
     *
     * @since   __DEPLPOY_VERSION__
     */
    public function FVunpublish()
    {
        $cid = $this->input->post->get('cid', array(0), 'array');

        if ( ! is_array($cid) || count($cid) < 1) {
            throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
        }

        $model = $this->getModel('rating');

        if (!\Redshop\Rating\Helper::setFavoured($cid, 0)) {
            echo "<script> alert('" . $model->getError() . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_RATING_DETAIL_UNPUBLISHED_SUCCESFULLY');
        $this->setRedirect('index.php?option=com_redshop&view=ratings', $msg);
    }
}