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

class fieldsController extends JController
{
	function cancel()
	{
		$this->setRedirect( 'index.php' );
	}

	function saveorder()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		$model = $this->getModel ( 'fields' );

		if ($model->saveorder($cid))
		{
			$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED' );
		} else {
			$msg = JText::_('COM_REDSHOP_NEW_ORDERING_ERROR' );
		}
		$this->setRedirect ( 'index.php?option=' .$option. '&view=fields', $msg );
	}
}
