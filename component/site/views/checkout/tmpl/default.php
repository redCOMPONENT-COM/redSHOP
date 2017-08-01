<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.calendar');
JHtml::_('behavior.modal');

JPluginHelper::importPlugin('redshop_shipping');
$dispatcher = RedshopHelperUtility::getDispatcher();
$dispatcher->trigger('onRenderCustomField');

$url     = JURI::base();
$user    = JFactory::getUser();
$session = JFactory::getSession();

$redhelper       = redhelper::getInstance();
$userhelper      = rsUserHelper::getInstance();
$order_functions = order_functions::getInstance();
$redTemplate     = Redtemplate::getInstance();

$telesearch = $order_functions->getparameters('rs_telesearch');
$Itemid     = RedshopHelperUtility::getCheckoutItemId();
$auth       = $session->get('auth');
$l          = JRequest::getInt('l', 1);
$jinput     = JFactory::getApplication()->input;

/*
 * REGISTER_METHOD
 * 0 With account creation
 * 1 Without account creation
 * 2 Optional (create an account if you like or dont)
 * 3 Silent account creation
 *
 * */

// Actually need know and determine which variables we want to use
$post = $jinput->post->getArray();

$login_template = $redTemplate->getTemplate("login");

if (count($login_template) > 0 && $login_template[0]->template_desc)
{
	$login_template_desc = $login_template[0]->template_desc;
}
else
{
	$login_template_desc = '
	<div class="redshop-login">
		<div class="form-group">
			<label>{rs_username_lbl}</label>
			{rs_username}
		</div>

		<div class="form-group">
			<label>{rs_password_lbl}</label>
			{rs_password}
		</div>
		{rs_login_button}

		{forget_password_link}
	</div>
	';
}

if (!Redshop::getConfig()->get('ONESTEP_CHECKOUT_ENABLE'))
{
	echo JLayoutHelper::render('cart.wizard', array('step' => '1'));
}

$returnurl = JRoute::_($url . 'index.php?option=com_redshop&view=checkout', false);
$returnurl = base64_encode($returnurl);

if ($user->id || (isset($auth['users_info_id']) && $auth['users_info_id'] > 0))
{
	echo $this->loadTemplate('address');
}
else
{
	if ($user->id == "" && Redshop::getConfig()->get('REGISTER_METHOD') != 1)
	{
		$show_login            = 1;
		$open_to_mystretchermy = 0;
	}
	else
	{
		$show_login            = 0;
		$open_to_mystretchermy = 1;
	}

	if (Redshop::getConfig()->get('NEW_CUSTOMER_SELECTION') || (isset($post['createaccount']) && $post['createaccount'] == 1))
	{
		$open_to_mystretchermy = 1;
	}

	if ($show_login)
	{
		echo '<div class="signInPaneDiv">';
		echo JHtml::_(Redshop::getConfig()->get('CHECKOUT_LOGIN_REGISTER_SWITCHER') . '.start', 'signInPane', array('startOffset' => $open_to_mystretchermy));
		echo JHtml::_(Redshop::getConfig()->get('CHECKOUT_LOGIN_REGISTER_SWITCHER') . '.panel', JText::_('COM_REDSHOP_RETURNING_CUSTOMERS'), 'login');

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
			$loginbutton         = '<input type="submit" class="button btn btn-primary" name="submitbtn" value="' . JText::_('COM_REDSHOP_LOGIN') . '">';
			$loginbutton         .= '<input type="hidden" name="l" value="1">';
			$loginbutton         .= '<input type="hidden" name="return" value="' . $returnurl . '" />';
			$loginbutton         .= '<input type="hidden" name="Itemid" value="' . $Itemid . '" />';
			$loginbutton         .= '<input type="hidden" name="task" id="task" value="setlogin">';
			$loginbutton         .= '<input type="hidden" name="option" id="option" value="com_redshop">';
			$loginbutton         .= '<input type="hidden" name="view" id="view" value="login">';
			$login_template_desc = str_replace("{rs_login_button}", $loginbutton, $login_template_desc);
		}

		$forgotpwd           = '<a href="' . JRoute::_('index.php?option=com_users&view=reset') . '">' . JText::_('COM_REDSHOP_FORGOT_PWD_LINK') . '</a>';
		$login_template_desc = str_replace("{forget_password_link}", $forgotpwd, $login_template_desc);

		$login_template_desc = '<form action="' . JRoute::_('index.php') . '" method="post">' . $login_template_desc . '</form>';

		echo eval("?>" . $login_template_desc . "<?php ");
		echo JHtml::_(Redshop::getConfig()->get('CHECKOUT_LOGIN_REGISTER_SWITCHER') . '.panel', JText::_('COM_REDSHOP_NEW_CUSTOMERS'), 'registration');
	}

	$allowCustomer = $this->lists['allowCustomer'];
	$allowCompany  = $this->lists['allowCompany'];
	$is_company    = $this->lists['is_company'];
	?>

    <div class="form-group">
        <label class="radio-inline" <?php echo $allowCustomer; ?>>
            <input type="radio" name="togglerchecker" id="toggler1" class="toggler"
                   onclick="showCompanyOrCustomer(this);"
                   value="0" <?php echo ($is_company == 0) ? 'checked="checked"' : '' ?> />
			<?php echo JText::_('COM_REDSHOP_USER_REGISTRATION'); ?>
        </label>
        <label class="radio-inline" <?php echo $allowCompany; ?>>
            <input type="radio" name="togglerchecker" id="toggler2" class="toggler"
                   onclick="showCompanyOrCustomer(this);"
                   value="1" <?php echo ($is_company == 1) ? 'checked="checked"' : '' ?> />
			<?php echo JText::_('COM_REDSHOP_COMPANY_REGISTRATION'); ?>
        </label>
    </div>

	<?php if (count($telesearch) > 0 && $telesearch[0]->enabled) : ?>

    <div class="input-group">
		<span class="input-group-btn">
			<button class="btn btn-primary" type="button" name="searchaddbyphone" id="searchaddbyphone"
                    onclick="return searchByPhone();">
				<?php echo JText::_('COM_REDSHOP_SEARCH') ?>
			</button>
		</span>
        <input class="form-control" name="searchphone" id="searchphone" type="text" value=""
               placeholder="<?php echo JText::_('COM_REDSHOP_GET_ADDRESS_BY_PHONE') ?>"/>
    </div>

    <div id="divSearchPhonemsg" style="display:none">
		<?php echo JText::_('COM_REDSHOP_NO_RESULT_FOUND_BY_SEARCHPHONE'); ?>
    </div>
<?php endif; ?>

    <div id="redshopRegistrationForm">
        <form action="<?php echo JRoute::_('index.php?option=com_redshop&view=checkout&Itemid=' . $Itemid); ?>"
              method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">

			<?php if (Redshop::getConfig()->get('REGISTER_METHOD') == 2) :
				$checked_style = (Redshop::getConfig()->get('CREATE_ACCOUNT_CHECKBOX') == 1) ? 'checked="checked"' : "''";
				?>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="createaccount" <?php echo $checked_style; ?> id="createaccount"
                               value="1" onclick="createUserAccount(this);"/>
						<?php echo JText::_('COM_REDSHOP_CREATE_ACCOUNT'); ?>
                    </label>
                </div>
			<?php endif; ?>

            <fieldset>
                <legend><?php echo JText::_('COM_REDSHOP_ADDRESS_INFORMATION'); ?></legend>

				<?php echo $userhelper->getBillingTable($post, $is_company, $this->lists, Redshop::getConfig()->get('OPTIONAL_SHIPPING_ADDRESS'), 1, Redshop::getConfig()->get('CREATE_ACCOUNT_CHECKBOX')); ?>
            </fieldset>

			<?php if (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE')) : ?>

				<?php
				$billingisshipping = "";

				if (count($_POST) > 0)
				{
					if (isset($post['billisship']) && $post['billisship'] == 1)
					{
						$billingisshipping = "style='display:none'";
					}
				}
                elseif (Redshop::getConfig()->get('OPTIONAL_SHIPPING_ADDRESS'))
				{
					$billingisshipping = "style='display:none'";
				}
				?>

                <div id="divShipping" <?php echo $billingisshipping; ?>>
                    <fieldset class="adminform subTable">
                        <legend><?php echo JText::_('COM_REDSHOP_SHIPPING_ADDRESSES'); ?></legend>
						<?php echo $userhelper->getShippingTable($post, $is_company, $this->lists); ?>
                    </fieldset>
                </div>

			<?php endif; ?>

			<?php echo RedshopLayoutHelper::render('registration.captcha'); ?>

            <div class="btn-group">
                <input type="button" class="btn btn-default btn-lg" name="back"
                       value="<?php echo JText::_('COM_REDSHOP_BACK'); ?>" onclick="javascript:window.history.go(-1);">
                <input type="submit" class="btn btn-primary btn-lg" name="submitbtn" id="submitbtn"
                       value="<?php echo JText::_('COM_REDSHOP_PROCEED'); ?>">
            </div>

            <div class="clr"></div>
            <input type="hidden" name="l" value="0">
            <input type="hidden" name="address_type" value="BT"/>
            <input type="hidden" name="user_id" id="user_id" value="0"/>
            <input type="hidden" name="usertype" value="Registered"/>
            <input type="hidden" name="groups[]" value="2"/>
            <input type="hidden" name="is_company" id="is_company" value="<?php echo $is_company; ?>"/>
            <input type="hidden" name="shopper_group_id" value="1"/>
            <input type="hidden" name="task" value="checkoutprocess"/>

        </form>
    </div>
	<?php
	if ($show_login)
	{
		echo JHtml::_(Redshop::getConfig()->get('CHECKOUT_LOGIN_REGISTER_SWITCHER') . '.end');
		echo '</div>';
	}
} ?>

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
