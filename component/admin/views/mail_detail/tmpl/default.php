<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;


JHTML::_('behavior.tooltip');
$editor = JFactory::getEditor();
JHTMLBehavior::modal();
$uri = JURI::getInstance();
$url = $uri->root();
JLoader::load('RedshopHelperAdminExtra_field');
JLoader::load('RedshopHelperAdminTemplate');
$extra_field = new extra_field;

?>

<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		if (form.mail_name.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_MAIL_ITEM_MUST_HAVE_A_NAME', true ); ?>");
		} else {
			submitform(pressbutton);
		}
	}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm"
      enctype="multipart/form-data">

<div class="col50">
<fieldset class="adminform">
	<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>
	<table class="admintable">
		<tr>
			<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_MAIL_NAME'); ?>:</td>
			<td><input class="text_area" type="text" name="mail_name" id="mail_name" size="32" maxlength="250"
			           value="<?php echo $this->detail->mail_name; ?>"/>
				<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_MAIL_NAME'), JText::_('COM_REDSHOP_MAIL_NAME'), 'tooltip.png', '', '', false); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_MAIL_SUBJECT'); ?>:</td>
			<td><input class="text_area" type="text" name="mail_subject" id="mail_subject" size="80" maxlength="255"
			           value="<?php echo $this->detail->mail_subject; ?>"/>
				<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_MAIL_SUBJECT'), JText::_('COM_REDSHOP_MAIL_SUBJECT'), 'tooltip.png', '', '', false); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_MAIL_SECTION'); ?>:</td>
			<td><?php echo $this->lists['type'];?>
				<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_MAIL_SECTION'), JText::_('COM_REDSHOP_MAIL_SECTION'), 'tooltip.png', '', '', false); ?>
				<input type="hidden" id="please"
				       value="<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_MAIL_SECTION'); ?>"></td>
		</tr>
		<tr id="order_state" <?php if ($this->detail->mail_section != 'order_status' || $this->detail->mail_section == "0")
		{ ?>    style="display: none;"    <?php }?>>
			<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_MAIL_ORDER_STATUS'); ?>:</td>
			<td>
				<div id="responce"></div>
				<div id="order_state_edit">
				<?php

					if (isset($this->lists['order_status']))
					{
						echo $this->lists['order_status'];
					}
				?>
				</div>
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_MAIL_BCC'); ?>:</td>
			<td><input class="text_area" type="text" name="mail_bcc" id="mail_bcc" size="80" maxlength="255"
			           value="<?php echo $this->detail->mail_bcc; ?>"/>
				<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_MAIL_BCC'), JText::_('COM_REDSHOP_LBL_MAIL_BCC_TOOLTIP'), 'tooltip.png', '', '', false); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>:</td>
			<td><?php echo $this->lists['published']; ?></td>
		</tr>
	</table>
</fieldset>
<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_REDSHOP_BODY'); ?></legend>
		<table class="admintable">
			<tr>
				<td>
					<?php echo $editor->display("mail_body", $this->detail->mail_body, '$widthPx', '$heightPx', '100', '20');    ?>
				</td>
			</tr>
		</table>
	</fieldset>
</div>

<div class="col50">
<fieldset class="adminform">
<legend><?php echo JText::_('COM_REDSHOP_MAIL_CENTER_HELPFUL_HINT'); ?></legend>
<?php
echo JHtml::_('sliders.start', 'mail-pane');
echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_STSTUS_OF_PASSWORD_RESET'), 'pass-reset'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('status_of_reset_password', 'mail'); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_REGISTRATION_MAIL'), 'registrationmail'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('registration', 'mail'); ?></td>
	</tr>
</table>
<?php
$newbillingtag = '{billing_address_start}
			<table border="0"><tbody>
			<tr><td>{companyname_lbl}</td><td>{companyname}</td></tr>
			<tr><td>{firstname_lbl}</td><td>{firstname}</td></tr>
			<tr><td>{lastname_lbl}</td><td>{lastname}</td></tr>
			<tr><td>{address_lbl}</td><td>{address}</td></tr>
			<tr><td>{city_lbl}</td><td>{city}</td></tr>
			<tr><td>{zip_lbl}</td><td>{zip}</td></tr>
			<tr><td>{country_lbl}</td><td>{country}</td></tr>
			<tr><td>{state_lbl}</td><td>{state}</td></tr>
			<tr><td>{phone_lbl}</td><td>{phone}</td></tr>
			<tr><td>{email_lbl}</td><td>{email}</td></tr>
			<tr><td>{vatnumber_lbl}</td><td>{vatnumber}</td></tr>
			<tr><td>{taxexempt_lbl}</td><td>{taxexempt}</td></tr>
			<tr><td>{user_taxexempt_request_lbl}</td><td>{user_taxexempt_request}</td></tr>{billing_extrafield}
			</tbody></table> {billing_address_end}';

$newshippingtag = '{shipping_address_start}
			<table border="0"><tbody>
			<tr><td>{firstname_lbl}</td><td>{firstname}</td></tr>
			<tr><td>{lastname_lbl}</td><td>{lastname}</td></tr>
			<tr><td>{address_lbl}</td><td>{address}</td></tr>
			<tr><td>{city_lbl}</td><td>{city}</td></tr>
			<tr><td>{zip_lbl}</td><td>{zip}</td></tr>
			<tr><td>{country_lbl}</td><td>{country}</td></tr>
			<tr><td>{state_lbl}</td><td>{state}</td></tr>
			<tr><td>{phone_lbl}</td><td>{phone}</td></tr>{shipping_extrafield}
			</tbody></table> {shipping_address_end}';
echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_ORDER_MAIL'), 'ordermail'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('order', 'mail'); ?></td>
	</tr>
	<tr>
		<td>
			<?php    $tags = $extra_field->getSectionFieldList(14, 1);
			if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
			else echo JText::_("COM_REDSHOP_CUSTOMER_SHIPPING_ADDRESS");
			for ($i = 0; $i < count($tags); $i++)
			{
				echo '<span style="margin-left:10px;">{' . $tags[$i]->field_name . '} -- ' . $tags[$i]->field_title . '</span>';
			}    ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php    $tags = $extra_field->getSectionFieldList(15, 1);
			if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
			else echo JText::_("COM_REDSHOP_COMPANY_SHIPPING_ADDRESS");
			for ($i = 0; $i < count($tags); $i++)
			{
				echo '<span style="margin-left:10px;">{' . $tags[$i]->field_name . '} -- ' . $tags[$i]->field_title . '</span>';
			}    ?>
		</td>
	</tr>
	<tr>
		<td><?php echo htmlentities($newbillingtag) . "<br><br>" . htmlentities($newshippingtag); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_INVOICE_MAIL'), 'invoicemail'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('invoice', 'mail'); ?></td>
	</tr>
	<tr>
		<td>
			<?php    $tags = $extra_field->getSectionFieldList(14, 1);
			if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
			else echo JText::_("COM_REDSHOP_CUSTOMER_SHIPPING_ADDRESS");
			for ($i = 0; $i < count($tags); $i++)
			{
				echo '<span style="margin-left:10px;">{' . $tags[$i]->field_name . '} -- ' . $tags[$i]->field_title . '</span>';
			}    ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php    $tags = $extra_field->getSectionFieldList(15, 1);
			if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
			else echo JText::_("COM_REDSHOP_COMPANY_SHIPPING_ADDRESS");
			for ($i = 0; $i < count($tags); $i++)
			{
				echo '<span style="margin-left:10px;">{' . $tags[$i]->field_name . '} -- ' . $tags[$i]->field_title . '</span>';
			}    ?>
		</td>
	</tr>
	<tr>
		<td><?php echo htmlentities($newbillingtag) . "<br><br>" . htmlentities($newshippingtag); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_ORDER_STATUS_MAIL'), 'orderstatusmail'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('order_status', 'mail'); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_CATALOG_SEND_MAIL'), 'catalogmail'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('catalog_send', 'mail'); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_CATALOG_FIRST_REMINDER'), 'catalogfirstreminder'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('catalog_first_reminder', 'mail'); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_CATALOG_SECOND_REMINDER'), 'catalogsecreminder'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('catalog_second_reminder', 'mail'); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_CATALOG_COUPON_REMINDER'), 'catalogcouponreminder'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('catalog_coupon_reminder', 'mail'); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_CATALOG_SAMPLE_FIRST_REMINDER'), 'catalogsamplefirstreminder'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('catalog_sample_first_reminder', 'mail'); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_CATALOG_SAMPLE_SECOND_REMINDER'), 'catalogsamplesecreminder'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('catalog_sample_second_reminder', 'mail'); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_CATALOG_SAMPLE_THIRD_REMINDER'), 'catalogsamplethirdreminder'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('catalog_sample_third_reminder', 'mail'); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_CATALOG_SAMPLE_COUPON_REMINDER'), 'catalogsamplecouponreminder'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('catalog_sample_coupon_reminder', 'mail'); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_ECONOMIC_INVOICE'), 'economicbookinvoice'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('economic_invoice', 'mail'); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_ASK_QUESTION_MAIL'), 'askquestion'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('ask_question', 'mail'); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_QUOTATION_MAIL'), 'quotationmail'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('quotation', 'mail'); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_DOWNLOADABLE_PRODUCT_MAIL'), 'downloadableproductmail'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('downloable_product', 'mail'); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_REVIEW_MAIL'), 'reviewmail'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('review_product', 'mail'); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_FIRST_MAIL_AFTER_ORDER_PURCHASED'), 'orderpurchase');  ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('first_after_order_purchased', 'mail'); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_GIFTCARD_MAIL'), 'giftcard'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('giftcard', 'mail'); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_WISHLIST_MAIL'), 'wishlist'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('wishlist', 'mail'); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_NEWSLETTER_CONFIRMATION'), 'newsletter_confirmation'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('newsletter_confirmation', 'mail'); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_SEND_FRIEND'), 'newsletter_confirmation'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('send_friend', 'mail'); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_QUOTATION_REGISTRATION_MAIL'), 'quotation_reg'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('quotation_registration', 'mail'); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_REQUEST_TAX_EXEMPT_MAIL'), 'tax_exempt'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('request_tax_exempt', 'mail'); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel',  JText::_('COM_REDSHOP_PRODUCT_SUBSCRIPTION_MAIL'), 'subscription'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('product_subscription', 'mail'); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_TAX_EXEMPT_APPROVAL_DISAPPROVAL_MAIL'), 'tax_exempt'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('tax_exempt_approval_disapproval', 'mail'); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_CATALOG_ORDER_MAIL'), 'catalog_order'); ?>
<table class="adminlist">
	<tr>
		<td><?php echo Redtemplate::getTemplateValues('catalog_order', 'mail'); ?></td>
	</tr>
</table>
<?php echo JHtml::_('sliders.end'); ?>
</fieldset>
</div>

</div>

<div class="clr"></div>
<input type="hidden" name="cid[]" value="<?php echo $this->detail->mail_id; ?>"/>
<input type="hidden" name="task" value=""/>
<input type="hidden" name="view" value="mail_detail"/>
</form>
