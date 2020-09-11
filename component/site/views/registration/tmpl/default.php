<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
$url = JURI::base();
$app = JFactory::getApplication();
JHtml::_('behavior.calendar');
$Itemid = $app->input->getInt('Itemid');
$post   = $app->input->post->getArray();

$isCompany = $this->lists['is_company'];

if ($this->params->get('show_page_heading', 1)) {
    if ($this->params->get('page_title')) {
        ?>
        <h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
            <?php echo JText::_('COM_REDSHOP_REGISTRATION_HEADING'); ?>
        </h1>
        <?php
    }
} ?>

<div class="registrationintro">
	<span id="customer_registrationintro" <?php echo $this->lists['showCustomerdesc']; ?>>
		<?php echo JText::_('COM_REDSHOP_REGISTRATION_INTROTEXT'); ?>
	</span>
    <span id="company_registrationintro" <?php echo $this->lists['showCompanydesc']; ?>>
		<?php echo JText::_('COM_REDSHOP_REGISTRATION_COMPANY_INTROTEXT'); ?>
	</span>
</div>

<div class="form-group">
    <label class="radio-inline" <?php echo $this->lists['allowCustomer']; ?>>
        <input type="radio" name="togglerchecker" id="toggler1" class="toggler" onclick="showCompanyOrCustomer(this);"
               value="0" <?php echo ($isCompany == 0) ? 'checked="checked"' : '' ?> />
        <?php echo JText::_('COM_REDSHOP_USER_REGISTRATION'); ?>
    </label>
    <label class="radio-inline" <?php echo $this->lists['allowCompany']; ?>>
        <input type="radio" name="togglerchecker" id="toggler2" class="toggler" onclick="showCompanyOrCustomer(this);"
               value="1" <?php echo ($isCompany == 1) ? 'checked="checked"' : '' ?> />
        <?php echo JText::_('COM_REDSHOP_COMPANY_REGISTRATION'); ?>
    </label>
</div>

<form action="<?php echo Redshop\IO\Route::_('index.php') ?>" method="post" name="adminForm" id="adminForm"
      enctype="multipart/form-data">
    <fieldset class="adminform">
        <legend><?php echo JText::_('COM_REDSHOP_ADDRESS_INFORMATION'); ?></legend>

        <?php
        echo RedshopHelperBilling::render($post, $isCompany, $this->lists, 0, 1, 1);

        echo RedshopLayoutHelper::render('registration.captcha');

        if (Redshop::getConfig()->get('SHOW_TERMS_AND_CONDITIONS') == 1) {
            echo \Redshop\Terms\Tag::replaceTermsConditions("{terms_and_conditions}");
        }

        ?>

        <input type="submit" class="registrationSubmitButton button btn btn-primary" name="submit"
               value="<?php echo JText::_('COM_REDSHOP_SEND_REGISTRATION'); ?>"/>

        <div class="clr"></div>

        <input type="hidden" name="l" value="0">
        <input type="hidden" name="mywishlist" id="mywishlist"
               value="<?php echo $app->input->getString('wishlist'); ?>">
        <input type="hidden" name="address_type" value="BT"/>
        <input type="hidden" name="usertype" value="Registered"/>
        <input type="hidden" name="groups[]" value="2"/>
        <input type="hidden" name="is_company" id="is_company" value="<?php echo $isCompany; ?>"/>
        <input type="hidden" name="shopper_group_id" value="1"/>
        <input type="hidden" name="createaccount" value="1"/>
        <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
        <input type="hidden" name="option" value="com_redshop"/>
        <input type="hidden" name="task" value="newregistration"/>
        <input type="hidden" name="view" value="registration"/>
    </fieldset>
</form>
