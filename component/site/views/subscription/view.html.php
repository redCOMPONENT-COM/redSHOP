<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('restricted access');
jimport('joomla.application.component.view');
require_once( JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'category.php' );
class subscriptionViewsubscription extends JView
{
	function display ($tpl=null)
   	{
   		global $mainframe;
   		// Include Javascript
   		JHTML::_('behavior.modal');
   		JHTML::Script('attribute.js', 'components/com_redshop/assets/js/',false);
		JHTML::Script('common.js', 'components/com_redshop/assets/js/',false);
   		JHTML::Script('jquery.js', 'components/com_redshop/assets/js/',false);
		JHTML::Script('redBOX.js', 'components/com_redshop/assets/js/',false);
		JHTML::Script('json.js', 'components/com_redshop/assets/js/',false);
   		$Itemid 	        = JRequest::getVar('Itemid');
   		$model  	        = $this->getModel('subscription');
   		$option		        = JRequest::getVar('option','com_redshop');
   		$catid  	        = JRequest::getInt('cid', 0, '', 'int');
   		$layout		        = JRequest::getVar('layout');
   		$detail		        = $model->getdata();
   		$wishlist 	        = $model->getUserWishlist();
	    $productmain_in_sub = $model->getProductMainInSub();
	    $category_in_sub    = $model->getCategoryInSub();
   		//Load Template		
   		$loadSubscriptionOverviewTemplate           =  $model->loadSubscriptionOverViewTemplate();
   		$loadSubscriptionDetailTemplate             =  $model->loadSubscriptionDetailTemplate();
   		if($layout == "detail")
		{
			$this->assignRef('loadSubscriptionDetailTemplate',$loadSubscriptionDetailTemplate);
			$this->assignRef('productmain_in_sub',$productmain_in_sub);
			$this->assignRef('category_in_sub',$category_in_sub);
			$this->setLayout('subscription_detail');
		}
		else if ($layout == "wishlist")
		{
			$this->assignRef('wishlist',$wishlist);
			$this->setLayout('subscription_wishlist');
		}
		$this->assignRef('loadSubscriptionOverviewTemplate',$loadSubscriptionOverviewTemplate);
		$this->assignRef('detail',$detail);
		parent::display($tpl);	
  	}
}