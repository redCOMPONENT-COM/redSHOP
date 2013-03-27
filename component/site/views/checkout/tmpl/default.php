<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

include_once JPATH_COMPONENT . '/helpers/helper.php';
include_once JPATH_COMPONENT . '/helpers/extra_field.php';

JHTML::_('behavior.tooltip');
JHTMLBehavior::modal();


$url = JURI::base();
$user = JFactory::getUser();
$session = JFactory::getSession();

$redhelper = new redhelper;
$userhelper = new rsUserhelper;
$order_functions = new order_functions;

$telesearch = $order_functions->getparameters('rs_telesearch');
$Itemid = $redhelper->getCheckoutItemid();
$option = JRequest::getVar('option');
$auth = $session->get('auth');
$l = JRequest::getVar('l', 1);
/*
 * REGISTER_METHOD
 * 0 With account creation
 * 1 Without account creation
 * 2 Optional (create an account if you like or dont)
 * 3 Silent account creation
 *
 * */

$post = JRequest::get('post');

$login_template_desc = '<table border="0" cellspacing="3" cellpadding="3" width="100%"><tbody><tr><td><label>{rs_username_lbl}:</label></td><td>{rs_username}</td><td><label>{rs_password_lbl}:</label></td><td>{rs_password}</td><td>{rs_login_button}</td></tr><tr><td colspan="2">{forget_password_link}</td></tr></tbody></table>';

?>

<hr/>
<table width="100%" class="checkout-bar" border="0" cellspacing="2" cellpadding="2">
	<tr>
		<td width="33%" class="checkout-bar-1-active"><?php echo JText::_('COM_REDSHOP_ORDER_INFORMATION');?></td>
		<td width="33%" class="checkout-bar-2"><?php echo JText::_('COM_REDSHOP_PAYMENT');?></td>
		<td width="33%" class="checkout-bar-3"><?php echo JText::_('COM_REDSHOP_RECEIPT'); ?></td>
	</tr>
</table>
<hr/>
<?php
$returnurl = JRoute::_($url . 'index.php?option=com_redshop&view=checkout', false);
$returnurl = base64_encode($returnurl);

if ($user->id || (isset($auth['users_info_id']) && $auth['users_info_id'] > 0))
{
	echo $this->loadTemplate('address');
}
else
{
	if ($user->id == "" && REGISTER_METHOD != 1)
	{
		$show_login            = 1;
		$open_to_mystretchermy = 0;
	}
	else
	{
		$show_login            = 0;
		$open_to_mystretchermy = 1;
	}

	if (NEW_CUSTOMER_SELECTION)
	{
		$open_to_mystretchermy = 1;
	}

	$loginuserstyle = '';
	$newuserstyle   = 'style="display:none;"';

	if ($open_to_mystretchermy == 1 || (isset($post['createaccount']) && $post['createaccount'] == 1))
	{
		$loginuserstyle = 'style="display:none;"';
		$newuserstyle   = '';
	}

	if ($show_login)
	{
		?>
		<h4><input class="mytogglermy" type="radio" name="mytogglermychecker" id="mytogglermycheckerlogin"
		           onclick="rss( '#login_div' ).slideDown();rss( '#register_div' ).slideUp()"
				<?php
				if ($open_to_mystretchermy == 0)
				{
				?> checked="checked"
				<?php
				}
				?>
				/>
			<label for="mytogglermycheckerlogin"><?php echo JText::_('COM_REDSHOP_RETURNING_CUSTOMERS');?></label></h4>
		<?php
		if (strstr($login_template_desc, "{rs_username}"))
		{
			$txtusername         = '<input class="inputbox" type="text" id="username" name="username" value="" />';
			$login_template_desc = str_replace("{rs_username_lbl}", JText::_('COM_REDSHOP_USERNAME'), $login_template_desc);
			$login_template_desc = str_replace("{rs_username}", $txtusername, $login_template_desc);
		}

		if (strstr($login_template_desc, "{rs_password}"))
		{
			$txtpassword         = '<input class="inputbox" type="password" id="password" name="password" value="" />';
			$login_template_desc = str_replace("{rs_password_lbl}", JText::_('COM_REDSHOP_PASSWORD'), $login_template_desc);
			$login_template_desc = str_replace("{rs_password}", $txtpassword, $login_template_desc);
		}

		if (strstr($login_template_desc, "{rs_login_button}"))
		{
			$loginbutton = '<input type="submit" class="button" name="submitbtn" value="' . JText::_('COM_REDSHOP_LOGIN') . '">';
			$loginbutton .= '<input type="hidden" name="l" value="1">';
			$loginbutton .= '<input type="hidden" name="return" value="' . $returnurl . '" />';
			$loginbutton .= '<input type="hidden" name="Itemid" value="' . $Itemid . '" />';
			$loginbutton .= '<input type="hidden" name="task" id="task" value="setlogin">';
			$loginbutton .= '<input type="hidden" name="option" id="option" value="com_redshop">';
			$loginbutton .= '<input type="hidden" name="view" id="view" value="login">';
			$login_template_desc = str_replace("{rs_login_button}", $loginbutton, $login_template_desc);
		}

		$forgotpwd_link      = JRoute::_('index.php?option=' . $option . '&view=password&Itemid=' . $Itemid);
		$forgotpwd           = '<a href="' . $forgotpwd_link . '">' . JText::_('COM_REDSHOP_FORGOT_PWD_LINK') . '</a>';
		$login_template_desc = str_replace("{forget_password_link}", $forgotpwd, $login_template_desc);

		$login_template_desc = '<div id="login_div" ' . $loginuserstyle . '><form action="' . JRoute::_('index.php') . '" method="post">' . $login_template_desc . '</form></div>';

		echo eval("?>" . $login_template_desc . "<?php ");    ?>

		<h4><input class="mytogglermy" type="radio" name="mytogglermychecker" id="mytogglermycheckerregister"
		           onclick="rss( '#register_div' ).slideDown();rss( '#login_div' ).slideUp()" <?php
			if ($open_to_mystretchermy == 1 || (isset($post['createaccount']) && $post['createaccount'] == 1))
			{
			?> checked="checked"
			<?php
			}
			?>
			/>
		<label for="mytogglermycheckerregister"><?php echo JText::_('COM_REDSHOP_NEW_CUSTOMERS');?></label></h4><?php
	}

	// Toggler settings
	$open_to_stretcher = 0;

	if ((isset($post['is_company']) && $post['is_company'] == 1) || DEFAULT_CUSTOMER_REGISTER_TYPE == 2)
	{
		$open_to_stretcher = 1;
	}

	// Allow registration type settings
	$allowCustomer = "";
	$allowCompany  = "";

	if (ALLOW_CUSTOMER_REGISTER_TYPE == 1)
	{
		$allowCompany      = "style='display:none;'";
		$open_to_stretcher = 0;
	}
	elseif (ALLOW_CUSTOMER_REGISTER_TYPE == 2)
	{
		$allowCustomer     = "style='display:none;'";
		$open_to_stretcher = 1;
	}

	$is_company = ($open_to_stretcher == 1 || (isset($post['is_company']) && $post['is_company'] == 1)) ? 1 : 0;            ?>

	<div class="mystretchermy" id="register_div" <?php echo $newuserstyle;?>>
		<div><span
				id="customer_registrationintro" <?php echo ($is_company == 1) ? 'style="display:none;"' : '';?>>
				<?php echo REGISTRATION_INTROTEXT; ?></span><span
				id="company_registrationintro" <?php echo ($is_company == 1) ? '' : 'style="display:none;"';?>>
				<?php echo REGISTRATION_COMPANY_INTROTEXT; ?></span>
		</div>
		<table cellpadding="5" cellspacing="0" border="0">
			<tr>
				<td><span <?php echo $allowCustomer;?>><h4><input type="radio" name="togglerchecker" id="toggler1"
				                                                  class="toggler"
								<?php
								if ($is_company == 0)
								{
								?> checked="checked"
								<?php
								}
								?> onclick="showCompanyOrCustomer(this);" value="0"/>
							<?php echo JText::_('COM_REDSHOP_USER_REGISTRATION');?></h4></span></td>

				<td><span <?php echo $allowCompany;?>><h4><input type="radio" name="togglerchecker" id="toggler2"
				                                                 class="toggler"
								<?php
								if ($is_company == 1)
								{
								?>
									 checked="checked"
								<?php
								}
								?> onclick="showCompanyOrCustomer(this);" value="1"/>
							<?php echo JText::_('COM_REDSHOP_COMPANY_REGISTRATION');?></h4></span></td>
			</tr>
			<?php
			if (count($telesearch) > 0 && $telesearch[0]->enabled)
			{
				?>
				<tr>
					<td colspan="2"><?php echo JText::_('COM_REDSHOP_GET_ADDRESS_BY_PHONE') . ':<input name="searchphone" id="searchphone" type="text" value="" /><input type="button" name="searchaddbyphone" id="searchaddbyphone" value="' . JText::_('COM_REDSHOP_SEARCH') . '" onclick="return searchByPhone();" />';?>
						<br/>

						<div id="divSearchPhonemsg"
						     style="display:none"><?php echo JText::_('COM_REDSHOP_NO_RESULT_FOUND_BY_SEARCHPHONE');?></div>
					</td>
				</tr>
			<?php
			}
			?>
		</table>

		<div class="stretcher" style="height:auto;min-height:600px;"/>

		<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="adminForm" id="adminForm"
		      enctype="multipart/form-data">
			<?php
			if (REGISTER_METHOD == 2)
			{
				$checked_style = (CREATE_ACCOUNT_CHECKBOX == 1) ? 'checked="checked"' : "''";
				?>
				<h4><input type="checkbox" name="createaccount" <?php echo $checked_style;?> id="createaccount"
				           value="1" onclick="createUserAccount(this);"/>
					<?php echo JText::_('COM_REDSHOP_CREATE_ACCOUNT');?></h4>
			<?php
			}    ?>
			<div>
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_REDSHOP_ADDRESS_INFORMATION');?></legend>
					<?php
					echo $userhelper->getBillingTable($post, $is_company, $this->lists, OPTIONAL_SHIPPING_ADDRESS, 1, CREATE_ACCOUNT_CHECKBOX);    ?>
				</fieldset>
				<br/>
				<?php
				$billingisshipping = "";

				if (count($post) > 0)
				{
					if (isset($post['billisship']) && $post['billisship'] == 1)
					{
						$billingisshipping = "style='display:none'";
					}
				}
				elseif (OPTIONAL_SHIPPING_ADDRESS)
				{
					$billingisshipping = "style='display:none'";
				}?>
				<div id="divShipping" <?php echo $billingisshipping;?>>
					<?php
					if (SHIPPING_METHOD_ENABLE)
					{
						?>
						<table cellspacing="0" cellpadding="0" border="0" width="100%">
							<tr class="subTable">
								<td>
									<fieldset class="adminform">
										<legend><?php echo JText::_('COM_REDSHOP_SHIPPING_ADDRESSES');?></legend>
										<?php        echo $userhelper->getShippingTable($post, $is_company, $this->lists);    ?>
									</fieldset>
								</td>
							</tr>
						</table>
					<?php
					}
					?>
				</div>

				<div id="divCaptcha">
					<?php
					if (SHOW_CAPTCHA)
					{
						?>
						<table cellspacing="0" cellpadding="0" border="0" width="100%">
							<tr>
								<td align="left">
									<fieldset class="adminform">
										<legend><?php echo JText::_('COM_REDSHOP_CAPTCHA');?></legend>
										<?php    echo $userhelper->getCaptchaTable();    ?>
									</fieldset>
								</td>
							</tr>
						</table>
					<?php
					}
					?>
				</div>

				<div>
					<table cellspacing="3" cellpadding="0" border="0" width="100%">
						<?php
						if (JPluginHelper::isEnabled('redshop_veis_registration', 'rs_veis_registration'))
						{
							?>
							<div id="veis_wait"></div>
							<tr>
								<td align="right"><input type="button" class="blackbutton" name="back"
								                         value="<?php echo JText::_('COM_REDSHOP_BACK'); ?>"
								                         onclick="javascript:window.history.go(-1);"></td>
								<td align="left">
									<input type="submit" class="greenbutton" name="submitbtn"
									       onclick="return checkveisvalid();"
									       value="<?php echo JText::_('COM_REDSHOP_PROCEED'); ?>">
								</td>
							</tr>
						<?php
						}
						else
						{
							?>
							<tr>
								<td align="right"><input type="button" class="blackbutton" name="back"
								                         value="<?php echo JText::_('COM_REDSHOP_BACK'); ?>"
								                         onclick="javascript:window.history.go(-1);"></td>
								<td align="left">
									<input type="submit" class="greenbutton" name="submitbtn" id="submitbtn"
									       value="<?php echo JText::_('COM_REDSHOP_PROCEED'); ?>">
								</td>
							</tr>
						<?php
						}
						?>
					</table>
				</div>

			</div>

			<div class="clr"></div>
			<input type="hidden" name="l" value="0">
			<input type="hidden" name="address_type" value="BT"/>
			<input type="hidden" name="user_id" id="user_id" value="0"/>
			<input type="hidden" name="usertype" value="Registered"/>
			<input type="hidden" name="groups[]" value="2"/>
			<input type="hidden" name="is_company" id="is_company" value="<?php echo $is_company; ?>"/>
			<input type="hidden" name="shopper_group_id" value="1"/>
			<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
			<input type="hidden" name="option" value="<?php echo $option ?>"/>
			<input type="hidden" name="task" value="checkoutprocess"/>
			<input type="hidden" name="view" value="checkout"/>

		</form>
	</div>
	</div>

<?php
}    ?>
<script type="text/javascript">

	function submit_disable(val) {
		document.adminForm.submit();
		document.getElementById(val).disabled = true;
		var op = document.getElementById(val);
		op.setAttribute("style", "opacity:0.3;");

		if (op.style.setAttribute) //For IE
			op.style.setAttribute("filter", "alpha(opacity=30);");

	}
</script>
