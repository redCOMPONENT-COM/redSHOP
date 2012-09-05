<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
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

defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.application.component.controller' );

class accessmanager_detailController extends JController
{
	function __construct($default = array())
	{
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}
	function edit() {
		JRequest::setVar ( 'view', 'answer_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		parent::display ();

	}
	function save($apply)
	{
		$post = JRequest::get ( 'post' );

		$option = JRequest::getVar('option','','request','string');
		$model = $this->getModel ( 'accessmanager_detail' );
		$section = JRequest::getVar('section','','request','string');
 		$row = $model->store ( $post );
		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_ACCESS_LEVEL_SAVED' );
		} else {
			$msg = JText::_('COM_REDSHOP_ERROR_ACCESS_LEVEL_SAVED' );
		}
		if($apply){
			$this->setRedirect ( 'index.php?option='.$option.'&view=accessmanager_detail&section='.$section, $msg );
		}else{
			$this->setRedirect ( 'index.php?option='.$option.'&view=accessmanager', $msg );
		}
	}

	function apply()
	{
		$this->save(1);
	}


	function cancel()
	{
		$option = JRequest::getVar ('option');
		$msg = JText::_('COM_REDSHOP_ACCESS_LEVEL_CANCEL' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=accessmanager',$msg );
	}

}?>