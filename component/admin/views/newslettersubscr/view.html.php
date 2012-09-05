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

class newslettersubscrViewnewslettersubscr extends JView
{
	function __construct( $config = array())
	{
		 parent::__construct( $config );
	}

	function display($tpl = null)
	{
		global $mainframe, $context;
		$context = 'subscription_id';
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR') );

   		JToolBarHelper::title(   JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR_MANAGEMENT' ), 'redshop_newsletter48' );
		$task = JRequest::getVar('task');

   		if( $task != 'import_data'){

	   		JToolBarHelper::custom('import_data','upload.png','upload_f2.png','COM_REDSHOP_IMPORT_DATA',false);
	   		JToolBarHelper::custom('export_data','save.png','save_f2.png','COM_REDSHOP_EXPORT_DATA',false);
	   		JToolBarHelper::custom('export_acy_data','save.png','save_f2.png','EXPORT_ACY_MAILING_DATA',false);
	   		JToolBarHelper::addNewX();
	 		JToolBarHelper::editListX();
			JToolBarHelper::deleteList();
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
   		}

		if( $task == 'import_data'){

			JToolBarHelper::custom('importdata','save.png','save_f2.png','COM_REDSHOP_IMPORT',false);

			JToolBarHelper::custom('back','back.png','back_f2.png','COM_REDSHOP_BACK',false);

			$this->setLayout('newsletterimport');

			$model=  $this->getModel('newslettersubscr');

			$newsletters=$model->getnewsletters();

			$lists['newsletters'] = JHTML::_('select.genericlist',$newsletters,'newsletter_id','class="inputbox" size="1" ','value','text','');
		}

		$uri =& JFactory::getURI();

		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order','filter_order','subscription_id');
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir','filter_order_Dir','');

		$lists['order'] 	= $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		$newslettersubscrs	= & $this->get( 'Data');
		$total		= & $this->get( 'Total');
		$pagination = & $this->get( 'Pagination' );

    	$this->assignRef('user',		JFactory::getUser());
    	$this->assignRef('lists',		$lists);
  		$this->assignRef('newslettersubscrs',$newslettersubscrs);
    	$this->assignRef('pagination',	$pagination);
    	$this->assignRef('request_url',	$uri->toString());
    	parent::display($tpl);
  }
}
?>