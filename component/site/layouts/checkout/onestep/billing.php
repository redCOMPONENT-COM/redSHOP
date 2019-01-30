<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$dispatcher = RedshopHelperUtility::getDispatcher();
JPluginHelper::importPlugin('redshop_checkout');

$openToStretcher = 0;
$company         = "hidden";
$customer        = "hidden";

$registerMethod = Redshop::getConfig()->getInt('REGISTER_METHOD');

if (Redshop::getConfig()->get('ALLOW_CUSTOMER_REGISTER_TYPE') == 0)
{
	$company  = "";
	$customer = "";
}
elseif (Redshop::getConfig()->get('ALLOW_CUSTOMER_REGISTER_TYPE') == 1)
{
	$openToStretcher = 0;
}
elseif (Redshop::getConfig()->get('ALLOW_CUSTOMER_REGISTER_TYPE') == 2)
{
	$openToStretcher = 1;
}

$isCompany = $openToStretcher == 1 ? 1 : 0;

$lists['shipping_customer_field'] = Redshop\Fields\SiteHelper::renderFields(RedshopHelperExtrafields::SECTION_PRIVATE_SHIPPING_ADDRESS);
$lists['shipping_company_field']  = Redshop\Fields\SiteHelper::renderFields(RedshopHelperExtrafields::SECTION_COMPANY_SHIPPING_ADDRESS);

$input = JFactory::getApplication()->input;
?>

<?php if ($registerMethod == 2): ?>
    <div class="form-group">
        <div class="checkbox">
            <label>
                <input type="checkbox" name="createaccount" id="createaccount" class="onestep-createaccount-toggle"
					<?php echo Redshop::getConfig()->get('CREATE_ACCOUNT_CHECKBOX') == 1 ? 'checked="checked"' : "''" ?>
                       value="1"/>
				<?php echo JText::_('COM_REDSHOP_CREATE_ACCOUNT'); ?>
            </label>
        </div>
    </div>
<?php endif; ?>
<?php if ($registerMethod != 1 && $registerMethod != 3): ?>
    <div id="onestep-createaccount-wrapper"
         style="display: <?php echo (Redshop::getConfig()->get('CREATE_ACCOUNT_CHECKBOX') == 1 || $registerMethod == 0) ? 'block' : 'none' ?>;">
        <div class="form-group">
            <label><?php echo JText::_('COM_REDSHOP_USERNAME_REGISTER') ?></label>
            <input class="inputbox form-control required" type="text" name="username"
                   id="onestep-createaccount-username"
                   size="32" maxlength="250" value="<?php echo $input->getString('username', '') ?>"/>
        </div>
        <div class="form-group">
            <label><?php echo JText::_('COM_REDSHOP_PASSWORD_REGISTER') ?></label>
            <input class="inputbox form-control required" type="password" name="password1"
                   id="password1" size="32" maxlength="250" value=""/>
        </div>
        <div class="form-group">
            <label><?php echo JText::_('COM_REDSHOP_CONFIRM_PASSWORD') ?></label>
            <input class="inputbox form-control required" type="password" name="password2"
                   id="password2" size="32" maxlength="250" value=""/>
        </div>
        <hr />
    </div>
<?php endif; ?>
<div class="form-group">
    <label class="radio-inline <?php echo $customer; ?>">
        <input type="radio" name="togglerchecker" id="toggler1" class="toggler" onclick="getBillingTemplate(this);"
               value="0" <?php echo ($isCompany == 0) ? 'checked="checked"' : '' ?> billing_type="private"/>
		<?php echo JText::_('COM_REDSHOP_USER_REGISTRATION'); ?>
    </label>
    <label class="radio-inline <?php echo $company; ?>">
        <input type="radio" name="togglerchecker" id="toggler2" class="toggler" onclick="getBillingTemplate(this);"
               value="1" <?php echo ($isCompany == 1) ? 'checked="checked"' : '' ?> billing_type="company"/>
		<?php echo JText::_('COM_REDSHOP_COMPANY_REGISTRATION'); ?>
    </label>
	<?php $dispatcher->trigger('onRenderOnstepCheckout'); ?>
</div>
<div id="wrapper-billing"></div>
