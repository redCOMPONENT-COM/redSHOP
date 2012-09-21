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

class mass_discount_detailController extends JController
{
	function __construct($default = array())
    {
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}

	function edit()
	{
		JRequest::setVar ( 'view', 'mass_discount_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );

		parent::display ();
	}

	function apply()
    {
		$this->save(1);
	}

	function save($apply=0)
	{
		$post = JRequest::get ( 'post' );

		$option = JRequest::getVar ('option');

		$cid = JRequest::getVar ( 'cid', array (0), 'post', 'array' );


		$post ['discount_product'] = $post ['container_product'];

		$post ['discount_startdate'] = strtotime($post ['discount_startdate']);
		$post ['discount_enddate'] = strtotime($post ['discount_enddate'])+(23*59*59);

		$model = $this->getModel ( 'mass_discount_detail' );

		$post ['mass_discount_id'] = $cid[0];

		$row = $model->store ( $post );
		$did = $row->mass_discount_id;

		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_SAVED' );
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_DISCOUNT_DETAIL' );
		}
		if($apply ==1 )
				$this->setRedirect ( 'index.php?option=' . $option . '&view=mass_discount_detail&task=edit&cid[]='.$row->mass_discount_id, $msg );
		else {
				$this->setRedirect ( 'index.php?option=' . $option . '&view=mass_discount', $msg );
		}

	}

	function remove()
	{
        $option = JRequest::getVar ('option');

		$layout = JRequest::getVar('layout');

		$cid = JRequest::getVar ( 'cid', array (0), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE' ) );
		}

		$model = $this->getModel ( 'mass_discount_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_DELETED_SUCCESSFULLY' );

		$this->setRedirect ( 'index.php?option='.$option.'&view=mass_discount',$msg );
	}

	function cancel()
	{
        $option = JRequest::getVar ('option');
		$msg = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_EDITING_CANCELLED' );

		$this->setRedirect ( 'index.php?option='.$option.'&view=mass_discount',$msg );
	}
}
