<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHtml::_('behavior.calendar');
JHtml::_('behavior.modal');

JPluginHelper::importPlugin('redshop_shipping');
$dispatcher = RedshopHelperUtility::getDispatcher();
$dispatcher->trigger('onRenderCustomField');

$url        = JURI::base();
$user       = JFactory::getUser();
$session    = JFactory::getSession();
$teleSearch = RedshopHelperOrder::getParameters('rs_telesearch');
$itemId     = RedshopHelperRouter::getCheckoutItemId();
$auth       = $session->get('auth');
$jinput     = JFactory::getApplication()->input;
$l          = $jinput->getInt('l', 1);

JPluginHelper::importPlugin('redshop_checkout');
$dispatcher = RedshopHelperUtility::getDispatcher();
$dispatcher->trigger('onRenderCustomField');

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

$loginTemplate = RedshopHelperTemplate::getTemplate("login");

if (count($loginTemplate) > 0 && $loginTemplate[0]->template_desc)
{
	$loginTemplateDesc = $loginTemplate[0]->template_desc;
}
else
{
	$loginTemplateDesc = RedshopHelperTemplate::getDefaultTemplateContent('login');
}

if (!Redshop::getConfig()->get('ONESTEP_CHECKOUT_ENABLE'))
{
	echo JLayoutHelper::render('cart.wizard', array('step' => '1'));
}

$returnUrl = JRoute::_($url . 'index.php?option=com_redshop&view=checkout', false);
$returnUrl = base64_encode($returnUrl);

if ($user->id || (isset($auth['users_info_id']) && $auth['users_info_id'] > 0))
{
	echo $this->loadTemplate('address');
}
else
{
	if (!$user->id && Redshop::getConfig()->get('REGISTER_METHOD') != 1)
	{
		$showLogin           = 1;
		$openToMystretchermy = 0;
	}
	else
	{
		$showLogin           = 0;
		$openToMystretchermy = 1;
	}

	if (Redshop::getConfig()->get('NEW_CUSTOMER_SELECTION') || (isset($post['createaccount']) && $post['createaccount'] == 1))
	{
		$openToMystretchermy = 1;
	}

	if ($showLogin)
	{
		echo '<div class="signInPaneDiv">';
		echo JHtml::_(Redshop::getConfig()->get('CHECKOUT_LOGIN_REGISTER_SWITCHER') . '.start', 'signInPane', array('startOffset' => $openToMystretchermy));
		echo JHtml::_(Redshop::getConfig()->get('CHECKOUT_LOGIN_REGISTER_SWITCHER') . '.panel', JText::_('COM_REDSHOP_RETURNING_CUSTOMERS'), 'login');

		$loginTemplateDesc = RedshopTagsReplacer::_(
				'login',
				$loginTemplateDesc,
				array(
					'returnUrl' => $returnUrl,
					'Itemid' => $itemId
				)
		);

		echo eval("?>" . $loginTemplateDesc . "<?php ");
		echo JHtml::_(Redshop::getConfig()->get('CHECKOUT_LOGIN_REGISTER_SWITCHER') . '.panel', JText::_('COM_REDSHOP_NEW_CUSTOMERS'), 'registration');
	}

	$allowCustomer = $this->lists['allowCustomer'];
	$allowCompany  = $this->lists['allowCompany'];
	$isCompany    = $this->lists['is_company'];
	?>

    <div class="form-group">
        <label class="radio-inline" <?php echo $allowCustomer; ?>>
            <input type="radio" name="togglerchecker" id="toggler1" class="toggler"
                   onclick="showCompanyOrCustomer(this);"
                   value="0" <?php echo ($isCompany == 0) ? 'checked="checked"' : '' ?> />
			<?php echo JText::_('COM_REDSHOP_USER_REGISTRATION'); ?>
        </label>
        <label class="radio-inline" <?php echo $allowCompany; ?>>
            <input type="radio" name="togglerchecker" id="toggler2" class="toggler"
                   onclick="showCompanyOrCustomer(this);"
                   value="1" <?php echo ($isCompany == 1) ? 'checked="checked"' : '' ?> />
			<?php echo JText::_('COM_REDSHOP_COMPANY_REGISTRATION'); ?>
        </label>
    </div>

	<?php if (count($teleSearch) > 0 && $teleSearch[0]->enabled) : ?>

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
        <form action="<?php echo JRoute::_('index.php?option=com_redshop&view=checkout&Itemid=' . $itemId); ?>"
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

				<?php echo RedshopHelperBilling::render($post, $isCompany, $this->lists, Redshop::getConfig()->get('OPTIONAL_SHIPPING_ADDRESS'), 1, Redshop::getConfig()->get('CREATE_ACCOUNT_CHECKBOX')); ?>
            </fieldset>

			<?php if (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE')) : ?>

				<?php
				$billingIsShipping = "";

				if (count($_POST) > 0)
				{
					if (isset($post['billisship']) && $post['billisship'] == 1)
					{
						$billingIsShipping = "style='display:none'";
					}
				}
                elseif (Redshop::getConfig()->get('OPTIONAL_SHIPPING_ADDRESS'))
				{
					$billingIsShipping = "style='display:none'";
				}
				?>

				<div id="divShipping" <?php echo $billingIsShipping; ?>>
					<fieldset class="adminform subTable">
						<legend><?php echo JText::_('COM_REDSHOP_SHIPPING_ADDRESSES'); ?></legend>
						<?php
							echo RedshopTagsReplacer::_(
								'shippingtable',
								'',
								array(
									'data' => $post,
									'isCompany' => $isCompany,
									'lists' => $this->lists
								)
							);
						?>
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
            <input type="hidden" name="is_company" id="is_company" value="<?php echo $isCompany; ?>"/>
            <input type="hidden" name="shopper_group_id" value="1"/>
            <input type="hidden" name="task" value="checkoutprocess"/>

        </form>
    </div>
	<?php
	if ($showLogin)
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
