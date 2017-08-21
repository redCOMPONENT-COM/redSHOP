<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JHTML::_('behavior.tooltip');
$redTemplate = Redtemplate::getInstance();
$app         = JFactory::getApplication();

$Itemid = $app->input->getInt('Itemid');
$layout = $app->input->getCmd('layout', 'default');
$model  = $this->getModel('catalog');

$template = $redTemplate->getTemplate("catalog");

if (count($template) > 0 && $template[0]->template_desc != "")
{
	$template_desc = $template[0]->template_desc;
}
else
{
	$template_desc = '<div id="katalog_wrapper"><div id="bestil_katalog_wrapper"><div id="katalog_overskrift"><h1>Order Catalog</h1></div><div id="katalog_venstre_wrapper"><div id="katalog_tekst"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris eget nisi orci, vel vehicula massa. Phasellus ipsum est, egestas a consequat eget, placerat vitae ipsum. Proin ac purus risus. Quisque nec nisi lacus, vitae iaculis eros. Donec ipsum diam, dictum ac euismod molestie, ultrices eget arcu. Vestibulum lacinia nisl et odio sagittis fermentum. Aliquam tristique volutpat faucibus. Sed id orci ut metus condimentum bibendum. Ut gravida scelerisque magna et pharetra. Ut vel turpis in orci molestie scelerisque. Proin nisl elit, ullamcorper id blandit nec, congue eget augue. Nullam gravida ligula nisi.</p></div><div>Catalog:{catalog_select}</div><div id="katalog_navn_email">NAME  {name} <br /><br />EMAIL {email_address} <br /><br />{submit_button_catalog}</div></div><div id="katalog_hojre_wrapper"></div></div></div>';
}

if ($this->params->get('show_page_heading', 1))
{
	if ($this->params->get('page_title'))
	{
		?>
	<h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
		<?php echo $this->escape($this->params->get('page_title')); ?>
		</h1><?php
	}
} ?>
<form action="" method="post" name="frmcatalog" id="frmcatalog">
	<?php
	if (strstr($template_desc, "{catalog_select}"))
	{
		$catalog        = $model->getCatalogList();
		$optionselect   = array();
		$optionselect[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
		$catalog_select = array_merge($optionselect, $catalog);

		$catalogsel = JHTML::_('select.genericlist', $catalog_select, 'catalog_id', 'class="inputbox" size="1" ', 'value', 'text', 0);

		$template_desc = str_replace("{catalog_select}", $catalogsel, $template_desc);
	}

	$template_desc = str_replace("{name_lbl}", JText::_('COM_REDSHOP_NAME_LBL'), $template_desc);
	$template_desc = str_replace("{name}", '<input type="text" name="name_2" id="name" />', $template_desc);
	$template_desc = str_replace("{email_lbl}", JText::_('COM_REDSHOP_EMAIL_LBL'), $template_desc);
	$template_desc = str_replace("{email_address}", '<input type="text" name="email_address" id="email_address" />', $template_desc);
	$template_desc = str_replace("{submit_button_catalog}", '<input type="submit" name="catalogsend" id="catalogsend" onClick="return getCatalogValidation();" value="' . JText::_('COM_REDSHOP_CATALOG_SEND') . '" />', $template_desc);

	echo $template_desc;
	?>
	<input type="hidden" name="view" value="catalog" id="view"/>
	<input type="hidden" name="option" id="option" value="com_redshop"/>
	<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
	<input type="hidden" name="task" value="catalog_send"/>
</form>
<script type="text/javascript">
	function getCatalogValidation() {
		var frm = document.frmcatalog;
		var email = frm.email_address.value;
		var patt1 = new RegExp("([a-z0-9_]+)@([a-z0-9_-]+)[.][a-z]");

		if (frm.catalog_id.value == '0') {
			alert('<?php echo JText::_('COM_REDSHOP_SELECT_CATALOG');?>');
			frm.catalog_id.focus();
			return false;
		}

		if (frm.name_2.value == '') {
			alert('<?php echo JText::_('COM_REDSHOP_ENTER_NAME');?>');
			frm.name_2.focus();
			return false;
		}

		if (email == '') {
			alert("<?php echo JText::_('COM_REDSHOP_ENTER_AN_EMAIL_ADDRESS');?>");
			frm.email_address.focus();
			return false;
		}
		else if (patt1.test(email) == false) {
			alert("<?php echo JText::_('COM_REDSHOP_EMAIL_ADDRESS_NOT_VALID');?>");
			frm.email_address.focus();
			return false;
		}
		return true;
	}
</script>
