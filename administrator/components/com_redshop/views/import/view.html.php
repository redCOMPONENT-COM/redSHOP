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
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class importViewimport extends JView
{
	function display($tpl = null)
	{
		$document = JFactory::getDocument();

		$isvm = JRequest::getVar('vm');

		if($isvm || $isvm == 1 )
		{
			$this->setLayout('vmimport');

			$model = $this->getModel('import');

			$check_vm = $model->check_vm();

			$this->assignRef ( 'check_vm',$check_vm );

			$document->setTitle( JText::_('COM_REDSHOP_IMPORT_FROM_VM') );

		}else{
			$layout = JRequest::getVar('layout');//die();
			if($layout == 'importlog' )
	  		{
	   			$this->setLayout($layout);
	   		}
			$task = JRequest::getVar('task');
			if ($task == 'importfile') {
				/* Load the data to export */
				$result = $this->get('Data');
			}

			$document->setTitle( JText::_('COM_REDSHOP_IMPORT') );

	   		JToolBarHelper::title(   JText::_('COM_REDSHOP_IMPORT_MANAGEMENT' ), 'redshop_import48' );
	   		JToolBarHelper :: custom( 'importfile', 'redshop_import_import32.png' , JText::_('COM_REDSHOP_IMPORT') , JText::_('COM_REDSHOP_IMPORT'), false, false );



	        $this->assignRef('result', $result);
		}
    	parent::display($tpl);
  }
}
