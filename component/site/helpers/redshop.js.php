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
	// 	no direct access
	defined( '_JEXEC' ) or die( 'Restricted access' );

	$doc = & JFactory::getDocument ();
	 $tmpl = JRequest::getCmd('tmpl');
	 $view = JRequest::getCmd('view');
	 $layout = JRequest::getCmd('layout');
	$for = JRequest::getWord("for",false);
	if($tmpl == 'component' && !$for)
    	$doc->addStyleDeclaration('html { overflow:scroll; }');
	// 	Getting the configuration
	require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'redshop.cfg.php');
	require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'configuration.php');
	$Redconfiguration = new Redconfiguration();
	$Redconfiguration->defineDynamicVars();

	require_once(JPATH_SITE. DS .'components'.DS.'com_redshop'.DS.'helpers'.DS.'currency.php');
	$session = JFactory::getSession('product_currency');

	$post = JRequest::get('POST');
	$Itemid= JRequest::getVar('Itemid');
	require_once(JPATH_SITE.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'helper.php');
	$redhelper = new redhelper ( );
	$cart_Itemid = $redhelper->getCartItemid ( $Itemid );
	if ($cart_Itemid  == "" || $cart_Itemid  == 0)
	{
		$cItemid = $redhelper->getItemid();
		$tmpItemid = $cItemid;
	} else {
		$tmpItemid = $cart_Itemid ;
	}
	if(isset($post['product_currency']))
		$session->set('product_currency',$post['product_currency']);

	$currency_symbol = 	REDCURRENCY_SYMBOL;
	$currency_convert = 1;
	/*if($session->get('product_currency')){

		$currency_symbol = $session->get('product_currency');
		$convertPrice = new convertPrice();
		$currency_convert = $convertPrice->convert(1);
	}*/
?>
<script>

			window.site_url = '<?php echo JURI::root(); ?>';
			window.AJAX_CART_BOX = '<?php echo AJAX_CART_BOX; ?>';
			window.REDSHOP_VIEW = '<?php echo $view; ?>';
			window.REDSHOP_LAYOUT = '<?php echo $layout; ?>';
			window.DEFAULT_CUSTOMER_REGISTER_TYPE = '<?php echo DEFAULT_CUSTOMER_REGISTER_TYPE; ?>';
			window.AJAX_CART_URL = '<?php echo JRoute::_('index.php?option=com_redshop&view=cart&Itemid='.$tmpItemid,false); ?>';
			window.REDCURRENCY_SYMBOL = '<?php echo REDCURRENCY_SYMBOL; ?>';
			window.CURRENCY_SYMBOL_CONVERT = '<?php echo $currency_symbol; ?>';
			window.CURRENCY_CONVERT = '<?php echo $currency_convert; ?>';
			window.PRICE_SEPERATOR = '<?php echo PRICE_SEPERATOR; ?>';
			window.PRODUCT_OUTOFSTOCK_MESSAGE = '<?php echo JText::_('PRODUCT_OUTOFSTOCK_MESSAGE'); ?>';
			window.YOUR_MUST_PROVIDE_A_VALID_PHONE = '<?php echo JText::_('YOUR_MUST_PROVIDE_A_VALID_PHONE'); ?>';
			window.PREORDER_PRODUCT_OUTOFSTOCK_MESSAGE = '<?php echo JText::_('PREORDER_PRODUCT_OUTOFSTOCK_MESSAGE'); ?>';
			window.CURRENCY_SYMBOL_POSITION = '<?php echo CURRENCY_SYMBOL_POSITION; ?>';
			window.PRICE_DECIMAL = '<?php echo PRICE_DECIMAL; ?>';
			window.PASSWORD_MIN_CHARACTER_LIMIT = '<?php echo JText::_('PASSWORD_MIN_CHARACTER_LIMIT'); ?>';
			window.THOUSAND_SEPERATOR = '<?php echo THOUSAND_SEPERATOR; ?>';
			window.VIEW_CART = '<?php echo JText::_('VIEW_CART'); ?>';
			window.CONTINUE_SHOPPING = '<?php echo JText::_('CONTINUE_SHOPPING'); ?>';
			window.CART_SAVE = '<?php echo JText::_('CART_SAVE'); ?>';
			window.IS_REQUIRED = '<?php echo JText::_('IS_REQUIRED'); ?>';
			window.ENTER_NUMBER = '<?php echo JText::_('ENTER_NUMBER'); ?>';
			window.USE_STOCKROOM = '<?php echo USE_STOCKROOM; ?>';
			window.USE_AS_CATALOG = '<?php echo USE_AS_CATALOG; ?>';
			window.AJAX_CART_DISPLAY_TIME = '<?php echo AJAX_CART_DISPLAY_TIME; ?>';
			window.SHOW_PRICE = '<?php echo SHOW_PRICE; ?>';
			window.DEFAULT_QUOTATION_MODE = '<?php echo DEFAULT_QUOTATION_MODE; ?>';
			window.PRICE_REPLACE = '<?php echo PRICE_REPLACE; ?>';
			window.PRICE_REPLACE_URL = '<?php echo PRICE_REPLACE_URL; ?>';
			window.ZERO_PRICE_REPLACE = '<?php echo ZERO_PRICE_REPLACE; ?>';
			window.ZERO_PRICE_REPLACE_URL = '<?php echo ZERO_PRICE_REPLACE_URL; ?>';
			window.OPTIONAL_SHIPPING_ADDRESS = '<?php echo OPTIONAL_SHIPPING_ADDRESS; ?>';
			window.SHIPPING_METHOD_ENABLE = '<?php echo SHIPPING_METHOD_ENABLE; ?>';
			window.PRODUCT_ADDIMG_IS_LIGHTBOX = '<?php echo PRODUCT_ADDIMG_IS_LIGHTBOX; ?>';
			window.ALLOW_PRE_ORDER = '<?php echo ALLOW_PRE_ORDER; ?>';
			window.ATTRIBUTE_SCROLLER_THUMB_WIDTH = '<?php echo ATTRIBUTE_SCROLLER_THUMB_WIDTH; ?>';
			window.ATTRIBUTE_SCROLLER_THUMB_HEIGHT = '<?php echo ATTRIBUTE_SCROLLER_THUMB_HEIGHT; ?>';
			window.PRODUCT_DETAIL_IS_LIGHTBOX = '<?php echo PRODUCT_DETAIL_IS_LIGHTBOX; ?>';
			window.REQUIRED_VAT_NUMBER = '<?php echo REQUIRED_VAT_NUMBER; ?>';
			window.PLEASE_ENTER_COMPANY_NAME = '<?php echo JText::_ ( 'PLEASE_ENTER_COMPANY_NAME', true ); ?>';
			window.YOUR_MUST_PROVIDE_A_FIRSTNAME = '<?php echo JText::_ ( 'YOUR_MUST_PROVIDE_A_FIRSTNAME', true ); ?>';
			window.YOUR_MUST_PROVIDE_A_LASTNAME = '<?php echo JText::_ ( 'YOUR_MUST_PROVIDE_A_LASTNAME', true ); ?>';
			window.YOUR_MUST_PROVIDE_A_ADDRESS = '<?php echo JText::_ ( 'YOUR_MUST_PROVIDE_A_ADDRESS', true ); ?>';
			window.YOUR_MUST_PROVIDE_A_ZIP = '<?php echo JText::_ ( 'YOUR_MUST_PROVIDE_A_ZIP', true ); ?>';
			window.YOUR_MUST_PROVIDE_A_CITY = '<?php echo JText::_ ( 'YOUR_MUST_PROVIDE_A_CITY', true ); ?>';
			window.YOUR_MUST_PROVIDE_A_PHONE = '<?php echo JText::_ ( 'YOUR_MUST_PROVIDE_A_PHONE', true ); ?>';
			window.THIS_FIELD_REQUIRED = '<?php echo JText::_ ( 'THIS_FIELD_REQUIRED', true ); ?>';
			window.THIS_FIELD_REMOTE = '<?php echo JText::_ ( 'THIS_FIELD_REMOTE', true ); ?>';
			window.THIS_FIELD_URL= '<?php echo JText::_ ( 'THIS_FIELD_URL', true ); ?>';
			window.THIS_FIELD_DATE= '<?php echo JText::_ ( 'THIS_FIELD_DATE', true ); ?>';
			window.THIS_FIELD_DATEISO= '<?php echo JText::_ ( 'THIS_FIELD_DATEISO', true ); ?>';
			window.THIS_FIELD_NUMBER= '<?php echo JText::_ ( 'THIS_FIELD_NUMBER', true ); ?>';
			window.THIS_FIELD_DIGITS= '<?php echo JText::_ ( 'THIS_FIELD_DIGITS', true ); ?>';
			window.THIS_FIELD_CREDITCARD= '<?php echo JText::_ ( 'THIS_FIELD_CREDITCARD', true ); ?>';
			window.THIS_FIELD_EQUALTO= '<?php echo JText::_ ( 'THIS_FIELD_EQUALTO', true ); ?>';
			window.THIS_FIELD_ACCEPT= '<?php echo JText::_ ( 'THIS_FIELD_ACCEPT', true ); ?>';
			window.THIS_FIELD_MAXLENGTH= '<?php echo JText::_ ( 'THIS_FIELD_MAXLENGTH', true ); ?>';
			window.THIS_FIELD_MINLENGTH= '<?php echo JText::_ ( 'THIS_FIELD_MINLENGTH', true ); ?>';
			window.THIS_FIELD_RANGELENGTH= '<?php echo JText::_ ( 'THIS_FIELD_RANGELENGTH', true ); ?>';
			window.THIS_FIELD_RANGE= '<?php echo JText::_ ( 'THIS_FIELD_RANGE', true ); ?>';
			window.THIS_FIELD_MAX= '<?php echo JText::_ ( 'THIS_FIELD_MAX', true ); ?>';
			window.THIS_FIELD_MIN= '<?php echo JText::_ ( 'THIS_FIELD_MIN', true ); ?>';
			window.YOU_MUST_PROVIDE_LOGIN_NAME = '<?php echo JText::_ ( 'YOU_MUST_PROVIDE_LOGIN_NAME', true ); ?>';
			window.PROVIDE_EMAIL_ADDRESS = '<?php echo JText::_ ( 'PROVIDE_EMAIL_ADDRESS', true ); ?>';
			window.EMAIL_NOT_MATCH = '<?php echo JText::_ ( 'EMAIL_NOT_MATCH', true ); ?>';
			window.PASSWORD_NOT_MATCH = '<?php echo JText::_ ( 'PASSWORD_NOT_MATCH', true ); ?>';
			window.NOOF_SUBATTRIB_THUMB_FOR_SCROLLER = '<?php echo NOOF_SUBATTRIB_THUMB_FOR_SCROLLER; ?>';
			window.NOT_AVAILABLE = '<?php echo JText::_ ( 'NOT_AVAILABLE', true ); ?>';
			window.PLEASE_INSERT_HEIGHT = '<?php echo JText::_ ( 'PLEASE_INSERT_HEIGHT', true ); ?>';
			window.PLEASE_INSERT_WIDTH = '<?php echo JText::_ ( 'PLEASE_INSERT_WIDTH', true ); ?>';
			window.PLEASE_INSERT_DEPTH = '<?php echo JText::_ ( 'PLEASE_INSERT_DEPTH', true ); ?>';
			window.PLEASE_INSERT_RADIUS = '<?php echo JText::_ ( 'PLEASE_INSERT_RADIUS', true ); ?>';
			window.PLEASE_INSERT_UNIT = '<?php echo JText::_ ( 'PLEASE_INSERT_UNIT', true ); ?>';
			window.THIS_FIELD_IS_REQUIRED = '<?php echo JText::_ ( 'THIS_FIELD_IS_REQUIRED', true ); ?>';
			window.SELECT_SUBSCRIPTION_PLAN = '<?php echo JText::_ ( 'SELECT_SUBSCRIPTION_PLAN', true ); ?>';
		    window.USERNAME_MIN_CHARACTER_LIMIT = '<?php echo JText::_ ( 'USERNAME_MIN_CHARACTER_LIMIT', true ); ?>';
			window.CREATE_ACCOUNT_CHECKBOX = '<?php echo CREATE_ACCOUNT_CHECKBOX; ?>';
			window.SHOW_QUOTATION_PRICE = '<?php echo SHOW_QUOTATION_PRICE; ?>';
			window.USE_TAX_EXEMPT = '<?php echo USE_TAX_EXEMPT; ?>';
			window.SHOW_EMAIL_VERIFICATION = '<?php echo SHOW_EMAIL_VERIFICATION; ?>';
</script>
<?php
		if($view == 'product')
		{
			if(is_file (JPATH_ROOT . '/components/com_redshop/assets/images/slimbox/' . PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE))
					$slimboxCloseButton = "#lbCloseLink {background: transparent url( \"".JURI :: base()."components/com_redshop/assets/images/slimbox/".PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE."\" ) no-repeat center;}";
			else
					$slimboxCloseButton = "#lbCloseLink {background: transparent url( \"".JURI :: base()."components/com_redshop/assets/images/slimbox/closelabel.gif\" ) no-repeat center;}";
			$doc->addStyleDeclaration($slimboxCloseButton);
		}
?>