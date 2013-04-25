<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.view' );

require_once  JPATH_COMPONENT_SITE.DS.'helpers'.DS.'product.php' ;

class answer_detailVIEWanswer_detail extends JView
{
	public function display($tpl = null)
	{
		$producthelper = new producthelper;
		$option = JRequest::getVar('option');

		$document = JFactory::getDocument();
		$document->setTitle( JText::_('COM_REDSHOP_ANSWER') );

		$uri	= JFactory::getURI();
		$lists = array();
		$model = $this->getModel();

		$detail	= $this->get('data');
		$qdetail= $producthelper->getQuestionAnswer($detail->parent_id);

		if(count($qdetail)>0)
		{
			$qdetail = $qdetail[0];
		}

		$isNew = ($detail->question_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW' ) : JText::_('COM_REDSHOP_EDIT' );

		JToolBarHelper::title(JText::_('COM_REDSHOP_ANSWER_DETAIL' ).': <small><small>[ '.$text.' ]</small></small>', 'redshop_question48' );
		JToolBarHelper::save();
		JToolBarHelper::custom( 'send','send.png','send.png',JText::_('COM_REDSHOP_SEND'),false);

		if ($isNew)
		{
			JToolBarHelper::cancel();
		} else {
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		$option= $model->getProduct();
		$optionsection = array();
		$optionsection[0]->product_id = 0;
		$optionsection[0]->product_name = JText::_('COM_REDSHOP_SELECT');

		if(count($option)>0)
		{
			$optionsection = @array_merge($optionsection,$option);
		}

		$lists['published'] = JHTML::_ ( 'select.booleanlist', 'published', 'class="inputbox"', $detail->published );
		$lists['product_id'] 	= JHTML::_('select.genericlist',$optionsection,  'product_id',
			'class="inputbox" size="1" ', 'product_id', 'product_name',  $detail->product_id
		);

		$this->assignRef('lists',		$lists);
		$this->assignRef('detail',		$detail);
		$this->assignRef('qdetail',		$qdetail);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}
}
