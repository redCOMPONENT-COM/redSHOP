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

class payment_detailViewpayment_detail extends JView
{
	function display($tpl = null)
	{
		$db = jFactory::getDBO();

		JToolBarHelper::title(   JText::_('COM_REDSHOP_TEMPLATES_MANAGEMET' ), 'redshop_payment48' );

		JSubMenuHelper::addEntry( JText::_('COM_REDSHOP_REDSHOP' ), 'index.php?option=com_redshop', true);

   		JSubMenuHelper::addEntry( JText::_('COM_REDSHOP_FIELDS' ), 'index.php?option=com_redshop&view=fields');

   		JSubMenuHelper::addEntry( JText::_('COM_REDSHOP_PRODUCTS' ), 'index.php?option=com_redshop&view=product');

   		JSubMenuHelper::addEntry( JText::_('COM_REDSHOP_CATEGORIES' ), 'index.php?option=com_redshop&view=category');

   		JSubMenuHelper::addEntry( JText::_('COM_REDSHOP_CONTAINER' ), 'index.php?option=com_redshop&view=container');

   		JSubMenuHelper::addEntry( JText::_('COM_REDSHOP_STOCKROOM' ), 'index.php?option=com_redshop&view=stockroom');

   		JSubMenuHelper::addEntry( JText::_('COM_REDSHOP_USER' ), 'index.php?option=com_redshop&view=user');

   		JSubMenuHelper::addEntry( JText::_('COM_REDSHOP_ORDER' ), 'index.php?option=com_redshop&view=order');

   		JSubMenuHelper::addEntry( JText::_('COM_REDSHOP_PAYMENT' ), 'index.php?option=com_redshop&view=payment');

   		JSubMenuHelper::addEntry( JText::_('COM_REDSHOP_SHIPPING' ), 'index.php?option=com_redshop&view=shipping');

        JSubMenuHelper::addEntry( JText::_('COM_REDSHOP_TEMPLATES' ), 'index.php?option=com_redshop&view=template');

		$uri 		= JFactory::getURI();

		$this->setLayout('default');

		$lists = array();

		$detail	= $this->get('data');

		$isNew		= ($detail->payment_method_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW' ) : JText::_('COM_REDSHOP_EDIT' );

		JToolBarHelper::title(   JText::_('COM_REDSHOP_PAYMENTS' ).': <small><small>[ ' . $text.' ]</small></small>' , 'redshop_payment48' );

		if ($isNew)  {

			JToolBarHelper::cancel();

			$this->setLayout('default_install');

		} else {

		    JToolBarHelper::save();

		   	JToolBarHelper::cancel();

			$adminpath=JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop';

	        $paymentxml=$adminpath.DS.'helpers'.DS.'payments'.DS.$detail->plugin.'.xml';

	        $paymentfile=$adminpath.DS.'helpers'.DS.'payments'.DS.$detail->plugin.DS.$detail->plugin.'.php';

	        $paymentcfg=$adminpath.DS.'helpers'.DS.'payments'.DS.$detail->plugin.DS.$detail->plugin.'.cfg.php';

	        include_once ($paymentfile);

	         $ps = new $detail->payment_class;

	        $this->assignRef('ps', $ps);

	        if(file_exists($paymentcfg)){

			if(!is_writable($paymentcfg)){

				echo "<font color='red'>".$paymentcfg.' is not writable</font>';
			}

	        include_once ($paymentcfg);

	        }

	 	   	$myparams = new JRegistry($detail->params,$paymentxml);

	        $ret = $myparams->render();

		}
		$cc_list = array();
		$cc_list['VISA'] = 'Visa';
		$cc_list['MC'] = 'MasterCard';
		$cc_list['amex'] = 'American Express';
		$cc_list['maestro'] = 'Maestro';
		$cc_list['jcb'] = 'JCB';
		$cc_list['diners'] = 'Diners Club';

		$query = ' SELECT shopper_group_id as value, shopper_group_name as text '
			. ' FROM  #__'.TABLE_PREFIX.'_shopper_group where  published=1';

		$db->setQuery( $query );

		$shopper_groups = $db->loadObjectList();

		$detail->shopper_group = explode(',',$detail->shopper_group);
		$tmp = new stdClass;
		$tmp = @array_merge($tmp,$detail->shopper_group);

		$lists['shopper_group'] 	=  JHTML::_('select.genericlist',   $shopper_groups, 'shopper_group[]', 'size="10" multiple', 'value', 'text', @$detail->shopper_group );

		$lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $detail->published );

		$lists['is_creditcard'] = JHTML::_('select.booleanlist',  'is_creditcard', 'class="inputbox" onChange="hide_show_cclist(this.value);"', $detail->is_creditcard );

 		$this->assignRef('params',		$ret);

		$this->assignRef('lists',		$lists);

		$this->assignRef('cc_list',		$cc_list);

		$this->assignRef('detail',		$detail);

		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}

}
?>
