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
jimport('joomla.html.pane');
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/extra_field.php';
$extra_field = new extra_field();

?>

<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	}

	submitbutton = function (pressbutton) {
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
				<div id="order_state_edit"><?php echo $this->lists['order_status'];?>    </div>
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
<?php    $title = JText::_('COM_REDSHOP_STSTUS_OF_PASSWORD_RESET');
echo $this->pane->startPane('stat-pane');
echo $this->pane->startPanel($title, 'events');?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_("COM_REDSHOP_STATUS_OF_RESET_PASSWORD_HINT");?></td>
	</tr>
</table>
<?php    echo $this->pane->endPanel();

$title = JText::_('COM_REDSHOP_REGISTRATION_MAIL');
echo $this->pane->startPanel($title, 'registrationmail');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_REGISTRATION_MAIL_HINT'); ?></td>
	</tr>
</table>
<?php    echo $this->pane->endPanel();

$title = JText::_('COM_REDSHOP_ORDER_MAIL');
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
echo $this->pane->startPanel($title, 'ordermail');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_ORDER_MAIL_HINT'); ?></td>
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
<?php    echo $this->pane->endPanel();

$title = JText::_('COM_REDSHOP_INVOICE_MAIL');
echo $this->pane->startPanel($title, 'invoicemail');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_INVOICE_MAIL_HINT'); ?></td>
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
<?php    echo $this->pane->endPanel();

$title = JText::_('COM_REDSHOP_ORDER_STATUS_MAIL');
echo $this->pane->startPanel($title, 'orderstatusmail');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_ORDER_STATUS_MAIL_HINT'); ?></td>
	</tr>
</table>
<?php    echo $this->pane->endPanel();

$title = JText::_('COM_REDSHOP_CATALOG_SEND_MAIL');
echo $this->pane->startPanel($title, 'catalogmail');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_CATALOG_SEND_MAIL_HINT'); ?></td>
	</tr>
</table>
<?php    echo $this->pane->endPanel();

$title = JText::_('COM_REDSHOP_CATALOG_FIRST_REMINDER');
echo $this->pane->startPanel($title, 'catalogfirstreminder');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_CATALOG_FIRST_REMINDER_HINT'); ?></td>
	</tr>
</table>
<?php    echo $this->pane->endPanel();

$title = JText::_('COM_REDSHOP_CATALOG_SECOND_REMINDER');
echo $this->pane->startPanel($title, 'catalogsecreminder');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_CATALOG_SECOND_REMINDER_HINT'); ?></td>
	</tr>
</table>
<?php    echo $this->pane->endPanel();

$title = JText::_('COM_REDSHOP_CATALOG_COUPON_REMINDER');
echo $this->pane->startPanel($title, 'catalogcouponreminder');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_CATALOG_COUPON_REMINDER_HINT'); ?></td>
	</tr>
</table>
<?php    echo $this->pane->endPanel();

$title = JText::_('COM_REDSHOP_CATALOG_SAMPLE_FIRST_REMINDER');
echo $this->pane->startPanel($title, 'catalogsamplefirstreminder');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_CATALOG_SAMPLE_FIRST_REMINDER_HINT'); ?></td>
	</tr>
</table>
<?php    echo $this->pane->endPanel();

$title = JText::_('COM_REDSHOP_CATALOG_SAMPLE_SECOND_REMINDER');
echo $this->pane->startPanel($title, 'catalogsamplesecreminder');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_CATALOG_SAMPLE_SECOND_REMINDER_HINT'); ?></td>
	</tr>
</table>
<?php    echo $this->pane->endPanel();

$title = JText::_('COM_REDSHOP_CATALOG_SAMPLE_THIRD_REMINDER');
echo $this->pane->startPanel($title, 'catalogsamplethirdreminder');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_CATALOG_SAMPLE_THIRD_REMINDER_HINT'); ?></td>
	</tr>
</table>
<?php    echo $this->pane->endPanel();

$title = JText::_('COM_REDSHOP_CATALOG_SAMPLE_COUPON_REMINDER');
echo $this->pane->startPanel($title, 'catalogsamplecouponreminder');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_CATALOG_SAMPLE_COUPON_REMINDER_HINT'); ?></td>
	</tr>
</table>
<?php    echo $this->pane->endPanel();

$title = JText::_('COM_REDSHOP_ECONOMIC_INVOICE');
echo $this->pane->startPanel($title, 'economicbookinvoice');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_ECONOMIC_INVOICE_HINT'); ?></td>
	</tr>
</table>
<?php    echo $this->pane->endPanel();

$title = JText::_('COM_REDSHOP_ASK_QUESTION_MAIL');
echo $this->pane->startPanel($title, 'askquestion');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_ASK_QUESTION_MAIL_HINT'); ?></td>
	</tr>
</table>
<?php    echo $this->pane->endPanel();

$title = JText::_('COM_REDSHOP_QUOTATION_MAIL');
echo $this->pane->startPanel($title, 'quotationmail');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_QUOTATION_MAIL_HINT'); ?></td>
	</tr>
</table>
<?php    echo $this->pane->endPanel();

$title = JText::_('COM_REDSHOP_DOWNLOADABLE_PRODUCT_MAIL');
echo $this->pane->startPanel($title, 'downloadableproductmail');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_DOWNLOADABLE_PRODUCT_MAIL_HINT'); ?></td>
	</tr>
</table>
<?php    echo $this->pane->endPanel();

$title = JText::_('COM_REDSHOP_REVIEW_MAIL');
echo $this->pane->startPanel($title, 'reviewmail');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_REVIEW_PRODUCT_MAIL_HINT'); ?></td>
	</tr>
</table>
<?php    echo $this->pane->endPanel();

$title = JText::_('COM_REDSHOP_FIRST_MAIL_AFTER_ORDER_PURCHASED');
echo $this->pane->startPanel($title, 'orderpurchase');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_FIRST_MAIL_AFTER_ORDER_PURCHASED_HINT'); ?></td>
	</tr>
</table>
<?php    echo $this->pane->endPanel();
$title = JText::_('COM_REDSHOP_GIFTCARD_MAIL');
echo $this->pane->startPanel($title, 'giftcard');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_GIFTCARD_MAIL_HINT'); ?></td>
	</tr>
</table>
<?php    echo $this->pane->endPanel();
$title = JText::_('COM_REDSHOP_WISHLIST_MAIL');
echo $this->pane->startPanel($title, 'wishlist');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_WISHLIST_MAIL_HINT'); ?></td>
	</tr>
</table>
<?php    echo $this->pane->endPanel();
$title = JText::_('COM_REDSHOP_NEWSLETTER_CONFIRMATION');
echo $this->pane->startPanel($title, 'newsletter_confirmation');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_NEWSLETTER_CONFIRMATION_HINT'); ?></td>
	</tr>
</table>
<?php    echo $this->pane->endPanel();
$title = JText::_('COM_REDSHOP_SEND_FRIEND');
echo $this->pane->startPanel($title, 'newsletter_confirmation');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_SEND_FRIEND_HINT'); ?></td>
	</tr>
</table>
<?php    echo $this->pane->endPanel();
$title = JText::_('COM_REDSHOP_QUOTATION_REGISTRATION_MAIL');
echo $this->pane->startPanel($title, 'quotation_reg');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_QUOTATION_REGISTRATION_MAIL_HINT'); ?></td>
	</tr>
</table>
<?php    echo $this->pane->endPanel();
$title = JText::_('COM_REDSHOP_REQUEST_TAX_EXEMPT_MAIL');
echo $this->pane->startPanel($title, 'tax_exempt');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_REQUEST_TAX_EXEMPT_MAIL_HINT'); ?></td>
	</tr>
</table>
<?php    echo $this->pane->endPanel();
$title = JText::_('COM_REDSHOP_PRODUCT_SUBSCRIPTION_MAIL');
echo $this->pane->startPanel($title, 'subscription');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_PRODUCT_SUBSCRIPTION_MAIL_HINT'); ?></td>
	</tr>
</table>
<?php    echo $this->pane->endPanel();
$title = JText::_('COM_REDSHOP_TAX_EXEMPT_APPROVAL_DISAPPROVAL_MAIL');
echo $this->pane->startPanel($title, 'tax_exempt');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_TAX_EXEMPT_APPROVAL_DISAPPROVAL_MAIL_HINT'); ?></td>
	</tr>
</table>
<?php    echo $this->pane->endPanel();
$title = JText::_('COM_REDSHOP_CATALOG_ORDER_MAIL');
echo $this->pane->startPanel($title, 'catalog_order');    ?>
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_CATALOG_ORDER_MAIL_HINT'); ?></td>
	</tr>
</table>
<?php    echo $this->pane->endPanel();

echo $this->pane->endPane();    ?>
</fieldset>
</div>

</div>

<div class="clr"></div>
<input type="hidden" name="cid[]" value="<?php echo $this->detail->mail_id; ?>"/>
<input type="hidden" name="task" value=""/>
<input type="hidden" name="view" value="mail_detail"/>
</form>
