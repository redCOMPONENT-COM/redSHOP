<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
$url = JURI::base();

$option = JRequest::getVar('option');
$Itemid = JRequest::getVar('Itemid');
$post = JRequest::get('post');

$userhelper = new rsUserhelper;
$rsCarthelper = new rsCarthelper;
$open_to_stretcher = 0;

if ((isset($post['is_company']) && $post['is_company'] == 1) || DEFAULT_CUSTOMER_REGISTER_TYPE == 2)
{
	$open_to_stretcher = 1;
}

// Allow registration type settings
$allowCustomer = "";
$allowCompany = "";
$showCustomerdesc = "";
$showCompanydesc = "style='display:none;'";

if (ALLOW_CUSTOMER_REGISTER_TYPE == 1)
{
	$allowCompany      = "style='display:none;'";
	$open_to_stretcher = 0;
}
elseif (ALLOW_CUSTOMER_REGISTER_TYPE == 2)
{
	$allowCustomer     = "style='display:none;'";
	$showCustomerdesc  = "style='display:none;'";
	$open_to_stretcher = 1;
}

if (DEFAULT_CUSTOMER_REGISTER_TYPE == 2)
{
	$showCompanydesc  = '';
	$showCustomerdesc = "style='display:none;'";
}

$is_company = ($open_to_stretcher == 1 || (isset($post['is_company']) && $post['is_company'] == 1)) ? 1 : 0;

if ($this->params->get('show_page_heading', 1))
{
	if ($this->params->get('page_title'))
	{
		?>
		<h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
			<?php echo JText::_('COM_REDSHOP_REGISTRATION_HEADING'); ?>
		</h1>
	<?php
	}
}    ?>

<div><span
		id="customer_registrationintro" <?php echo $showCustomerdesc;
		?>><?php echo JText::_('COM_REDSHOP_REGISTRATION_INTROTEXT'); ?></span><span
		id="company_registrationintro" <?php echo $showCompanydesc;
		?>><?php echo JText::_('COM_REDSHOP_REGISTRATION_COMPANY_INTROTEXT'); ?></span>
</div>
<table cellpadding="5" cellspacing="0" border="0">
	<tr>
		<td><span <?php echo $allowCustomer;?>><h4>
					<img src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH; ?>account/personal-icon.jpg" align="absmiddle">
					<input type="radio" onclick="showCompanyOrCustomer(this);" name="togglerchecker" id="toggler1"
					       class="toggler" <?php
							if ($is_company == 0)
							{
							?>checked="checked" <?php
							}
							?> value="0"/>
					<?php echo JText::_('COM_REDSHOP_USER_REGISTRATION'); ?></h4></span></td>
		<td><span <?php echo $allowCompany;?>><h4>
					<img src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH; ?>account/business-icon.jpg" align="absmiddle">
					<input type="radio" onclick="showCompanyOrCustomer(this);" name="togglerchecker" id="toggler2"
					       class="toggler" <?php
							if ($is_company == 1)
							{
							?>checked="checked" <?php
							}
							?> value="1"/>
					<?php echo JText::_('COM_REDSHOP_COMPANY_REGISTRATION'); ?></h4></span></td>
	</tr>
</table>

<form action="<?php echo JRoute::_('index.php') ?>" method="post" name="adminForm" id="adminForm"
      enctype="multipart/form-data">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_REDSHOP_ADDRESS_INFORMATION');?></legend>
		<table class="admintable" cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td>
					<table class="admintable" cellpadding="0" cellspacing="0" border="0" width="100%">
						<tr>
							<td><?php echo $userhelper->getBillingTable($post, $is_company, $this->lists, 0, 1, 1);    ?></td>
						</tr>
					</table>
				</td>
			</tr>

			<?php
			if (SHOW_CAPTCHA)
			{
				?>
				<tr>
					<td>
						<table class="admintable" cellpadding="0" cellspacing="0" border="0" width="100%">
							<tr>
								<td align="left"><?php    echo $userhelper->getCaptchaTable();    ?></td>
							</tr>
						</table>
					</td>
				</tr>
			<?php
			}

			if (SHOW_TERMS_AND_CONDITIONS == 1)
			{
				?>
				<tr>
					<td><?php echo $rsCarthelper->replaceTermsConditions("{terms_and_conditions}");?></td>
				</tr>
			<?php
			}
			?>
			<tr>
				<td><input type="submit" class="button" name="submit"
				           value="<?php echo JText::_('COM_REDSHOP_SEND_REGISTRATION'); ?>"/></td>
			</tr>
		</table>

		<div class="clr"></div>

		<input type="hidden" name="l" value="0">
		<input type="hidden" name="mywishlist" id="mywishlist" value="<?php echo JRequest::getVar('wishlist'); ?>">
		<input type="hidden" name="address_type" value="BT"/>
		<input type="hidden" name="usertype" value="Registered"/>
		<input type="hidden" name="groups[]" value="2"/>
		<input type="hidden" name="is_company" id="is_company" value="<?php echo $is_company; ?>"/>
		<input type="hidden" name="shopper_group_id" value="1"/>
		<input type="hidden" name="createaccount" value="1"/>
		<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
		<input type="hidden" name="option" value="<?php echo $option ?>"/>
		<input type="hidden" name="task" value="newregistration"/>
		<input type="hidden" name="view" value="registration"/>
	</fieldset>
</form>