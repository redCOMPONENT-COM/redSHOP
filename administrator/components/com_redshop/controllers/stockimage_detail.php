<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller.php';

class stockimage_detailController extends RedshopCoreController
{
    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function edit()
    {
        JRequest::setVar('view', 'stockimage_detail');
        JRequest::setVar('layout', 'default');
        JRequest::setVar('hidemainmenu', 1);
        parent::display();
    }

    public function save()
    {
        $post                     = JRequest::get('post');
        $option                   = JRequest::getVar('option');
        $cid                      = JRequest::getVar('cid', array(0), 'post', 'array');
        $post ['stock_amount_id'] = $cid [0];

        $model = $this->getModel('stockimage_detail');

        if ($row = $model->store($post))
        {
            $msg = JText::_('COM_REDSHOP_STOCKIMAGE_DETAIL_SAVED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_STOCKIMAGE_DETAIL');
        }
        $this->setRedirect('index.php?option=' . $option . '&view=stockimage', $msg);
    }

    public function remove()
    {
        $option = JRequest::getVar('option');
        $cid    = JRequest::getVar('cid', array(0), 'post', 'array');
        if (!is_array($cid) || count($cid) < 1)
        {
            JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
        }
        $model = $this->getModel('stockimage_detail');
        if (!$model->delete($cid))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }
        $msg = JText::_('COM_REDSHOP_STOCKIMAGE_DETAIL_DELETED_SUCCESSFULLY');
        $this->setRedirect('index.php?option=' . $option . '&view=stockimage', $msg);
    }

    /*public function publish()
     {
         $option = JRequest::getVar('option');
         $cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
         if (! is_array ( $cid ) || count ( $cid ) < 1) {
             JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH' ) );
         }
         $model = $this->getModel ( 'stockimage_detail' );
         if (! $model->publish ( $cid, 1 )) {
             echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
         }
         $msg = JText::_('COM_REDSHOP_STOCKIMAGE_DETAIL_PUBLISHED_SUCCESSFULLY' );
         $this->setRedirect ( 'index.php?option='.$option.'&view=stockimage',$msg );
     }
     public function unpublish()
     {
         $option = JRequest::getVar('option');
         $cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
         if (! is_array ( $cid ) || count ( $cid ) < 1) {
             JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH' ) );
         }
         $model = $this->getModel ( 'stockimage_detail' );
         if (! $model->publish ( $cid, 0 )) {
             echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
         }
         $msg = JText::_('COM_REDSHOP_STOCKIMAGE_DETAIL_UNPUBLISHED_SUCCESSFULLY' );
         $this->setRedirect ( 'index.php?option='.$option.'&view=stockimage',$msg );
     }*/

    public function cancel()
    {
        $option = JRequest::getVar('option');
        $msg    = JText::_('COM_REDSHOP_STOCKIMAGE_DETAIL_EDITING_CANCELLED');
        $this->setRedirect('index.php?option=' . $option . '&view=stockimage', $msg);
    }
}
