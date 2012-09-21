<?php
/**
 * @copyright  Copyright (C) 2010-2012 redCOMPONENT.com. All rights reserved.
 * @license    GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class country_detailController extends JController
 {
	function __construct($default = array())
	{
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
    }

	function edit()
	{
		JRequest::setVar ( 'view', 'country_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );

		$model = $this->getModel ( 'country_detail' );

		parent::display ();
	}

	function apply()
	{
       $this->save(1);
	}

	function save($apply=0)
    {
		$post = JRequest::get ( 'post' );

		$country_name = JRequest::getVar( 'country_name', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$post["country_name"] = $country_name;
		$option = JRequest::getVar ('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		$post ['country_id'] = $cid [0];
		$model = $this->getModel ( 'country_detail' );
		$row = $model->store ( $post );

        if ($row)
		{
            $msg = JText::_('COM_REDSHOP_COUNTRY_DETAIL_SAVED' );
        }
        else
		{
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_COUNTRY_DETAIL' );
        }

		if ($apply==1){
			$this->setRedirect ( 'index.php?option=' . $option . '&view=country_detail&task=edit&cid[]='.$row->country_id, $msg );
		}else {
			$this->setRedirect ( 'index.php?option=' . $option . '&view=country', $msg);
		}

	}

	function cancel()
    {
        $option = JRequest::getVar ('option');
		$msg = JText::_('COM_REDSHOP_COUNTRY_DETAIL_EDITING_CANCELLED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=country',$msg );
	}

	function remove()
    {
        $option = JRequest::getVar ('option');

		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE' ) );
		}

		$model = $this->getModel ( 'country_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_COUNTRY_DETAIL_DELETED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=country',$msg );
	}
}
