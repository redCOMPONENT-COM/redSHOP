<?php

/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

JHtml::_('bootstrap.modal');

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

if (count($loginTemplate) > 0 && $loginTemplate[0]->template_desc) {
    $loginTemplateDesc = $loginTemplate[0]->template_desc;
} else {
    $loginTemplateDesc = RedshopHelperTemplate::getDefaultTemplateContent('login');
}

if (!Redshop::getConfig()->get('ONESTEP_CHECKOUT_ENABLE')) {
    echo JLayoutHelper::render('cart.wizard', array('step' => '1'));
}

$returnUrl = Redshop\IO\Route::_($url . 'index.php?option=com_redshop&view=checkout', false);
$returnUrl = base64_encode($returnUrl);

if ($user->id || (isset($auth['users_info_id']) && $auth['users_info_id'] > 0)) {
    echo $this->loadTemplate('address');
} else {
    if (!$user->id && Redshop::getConfig()->get('REGISTER_METHOD') != 1) {
        $showLogin           = 1;
        $openToMystretchermy = 0;
    } else {
        $showLogin           = 0;
        $openToMystretchermy = 1;
    }

    if (Redshop::getConfig()->get(
        'NEW_CUSTOMER_SELECTION'
    ) || (isset($post['createaccount']) && $post['createaccount'] == 1)) {
        $openToMystretchermy = 1;
    }
} ?>
<br>
<ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="registration-tab" data-bs-toggle="tab" 
                data-bs-target="#registration" type="button" role="tab" aria-controls="registration" 
                aria-selected="true">
            <?php echo Text::_('COM_REDSHOP_NEW_CUSTOMERS'); ?>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" 
                role="tab" aria-controls="login" aria-selected="false">
            <?php echo Text::_('COM_REDSHOP_RETURNING_CUSTOMERS'); ?>
        </button>
    </li>
</ul>
<div class="container tab-content" id="tabRegistration" style="display: grid">
    <div class="row tab-pane show active" id="registration" role="tabpanel" 
            aria-labelledby="registration-tab">
        <?php
        $allowCustomer = $this->lists['allowCustomer'];
        $isCompany     = $this->lists['is_company'];
        ?>

        <div class=" col-sm-12 form-group" <?php echo $allowCustomer; ?>>
            <div class="d-grid gap-2">
                <button class="btn btn-primary" name="togglerchecker" id="toggler1" type="button" 
                        onclick="showCompanyOrCustomer(this);" value="0">
                    <?php echo Text::_('COM_REDSHOP_USER_REGISTRATION'); ?>
                </button>
                <button class="btn btn-primary" name="togglerchecker" id="toggler2" type="button" 
                        onclick="showCompanyOrCustomer(this);" value="1">
                    <?php echo Text::_('COM_REDSHOP_COMPANY_REGISTRATION'); ?>
                </button>
            </div>
        </div>

        <?php if (count($teleSearch) > 0 && $teleSearch[0]->enabled) : ?>
            <div class="col-sm-12 input-group">
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="button" name="searchaddbyphone" id="searchaddbyphone" onclick="return searchByPhone();">
                        <?php echo Text::_('COM_REDSHOP_SEARCH') ?>
                    </button>
                </span>
                <input class="form-control" name="searchphone" id="searchphone" type="text" value="" placeholder="<?php echo Text::_('COM_REDSHOP_GET_ADDRESS_BY_PHONE') ?>" />
            </div>

            <div id="divSearchPhonemsg" style="display:none">
                <?php echo Text::_('COM_REDSHOP_NO_RESULT_FOUND_BY_SEARCHPHONE'); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo Redshop\IO\Route::_('index.php?option=com_redshop&view=checkout&Itemid=' . $itemId); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
            <div class="col-md-6">
                <?php if (Redshop::getConfig()->get('REGISTER_METHOD') == 2) :
                    $checked_style = (Redshop::getConfig()->get(
                        'CREATE_ACCOUNT_CHECKBOX'
                    ) == 1) ? 'checked="checked"' : "''";
                ?>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="createaccount" <?php echo $checked_style; ?> id="createaccount" value="1" onclick="createUserAccount(this);" />
                            <?php echo Text::_('COM_REDSHOP_CREATE_ACCOUNT'); ?>
                        </label>
                    </div>
                <?php endif; ?>

                <fieldset>
                    <legend><?php echo Text::_('COM_REDSHOP_ADDRESS_INFORMATION'); ?></legend>
                    <?php echo RedshopHelperBilling::render(
                        $post,
                        $isCompany,
                        $this->lists,
                        Redshop::getConfig()->get('OPTIONAL_SHIPPING_ADDRESS'),
                        1,
                        Redshop::getConfig()->get('CREATE_ACCOUNT_CHECKBOX')
                    ); ?>
                </fieldset>
            </div>

            <?php if (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE')) : ?>
                <div class="col-md-6" id="divShipping" <?php echo $billingIsShipping; ?>>
                    <?php $billingIsShipping = "";

                    if (count($_POST) > 0) {
                        if (isset($post['billisship']) && $post['billisship'] == 1) {
                            $billingIsShipping = "style='display:none'";
                        }
                    } elseif (Redshop::getConfig()->get('OPTIONAL_SHIPPING_ADDRESS')) {
                        $billingIsShipping = "style='display:none'";
                    } ?>

                    <fieldset class="adminform subTable">
                        <legend><?php echo Text::_('COM_REDSHOP_SHIPPING_ADDRESSES'); ?></legend>
                        <?php
                        echo RedshopTagsReplacer::_(
                            'shippingtable',
                            '',
                            array(
                                'data'      => $post,
                                'isCompany' => $isCompany,
                                'lists'     => $this->lists
                            )
                        ); ?>
                    </fieldset>
                </div>
            <?php endif; ?>

            <?php echo RedshopLayoutHelper::render('registration.captcha'); ?>

            <div class="btn-group col-md-12">
                <input type="button" class="btn btn-default btn-lg" name="back" value="<?php echo Text::_('COM_REDSHOP_BACK'); ?>" onclick="javascript:window.history.go(-1);">
                <input type="submit" class="btn btn-primary btn-lg" name="submitbtn" id="submitbtn" value="<?php echo Text::_('COM_REDSHOP_PROCEED'); ?>">
            </div>

            <div class="clr col-md-12"></div>
            <input type="hidden" name="l" value="0">
            <input type="hidden" name="address_type" value="BT" />
            <input type="hidden" name="user_id" id="user_id" value="0" />
            <input type="hidden" name="usertype" value="Registered" />
            <input type="hidden" name="groups[]" value="2" />
            <input type="hidden" name="is_company" id="is_company" value="<?php echo $isCompany; ?>" />
            <input type="hidden" name="shopper_group_id" value="1" />
            <input type="hidden" name="task" value="checkoutprocess" />
        </form>

    </div>
    <div class="tab-pane" id="login" role="tabpanel" aria-labelledby="login-tab">
        <div class="container">
            <div class="rov">
                <div class="col-sm-2"></div>
                <div class="col-sm-8"> <?php
                                        $loginTemplateDesc = RedshopTagsReplacer::_(
                                            'login',
                                            $loginTemplateDesc,
                                            array(
                                                'returnUrl' => $returnUrl,
                                                'Itemid'    => $itemId
                                            )
                                        );
                                        echo eval("?>" . $loginTemplateDesc . "<?php "); ?>
                </div>
                <div class="col-sm-2"></div>
            </div>
        </div>
    </div>
</div>




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