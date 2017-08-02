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
$extra_field = extra_field::getInstance();
$app         = JFactory::getApplication();

$Itemid = $app->input->getInt('Itemid');
$layout = $app->input->getCmd('layout', 'default');
$model  = $this->getModel('catalog');

$template = $redTemplate->getTemplate("product_sample");

if (count($template) > 0 && $template[0]->template_desc != "")
{
	$template_desc = $template[0]->template_desc;
}
else
{
	$template_desc = '<div id="katalog_wrapper"><div id="bestil_katalog_wrapper"><div id="katalog_hojre_wrapper"></div></div><div id="bestil_farveproeve_wrapper"><div id="farveproeve_overskrift"><h2>Bestil Farveprver</h2></div><div id="farveproeve_venstre"><div id="farveproever_tekst">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris eget nisi orci, vel vehicula massa. Phasellus ipsum est, egestas a consequat eget, placerat vitae ipsum. Proin ac purus risus. Quisque nec nisi lacus, vitae iaculis eros. Donec ipsum diam, dictum ac euismod molestie, ultrices eget arcu. Vestibulum lacinia nisl et odio sagittis fermentum. Aliquam tristique volutpat faucibus. Sed id orci ut metus condimentum bibendum. Ut gravida scelerisque magna et pharetra. Ut vel turpis in orci molestie scelerisque. Proin nisl elit, ullamcorper id blandit nec, congue eget augue. Nullam gravida ligula nisi.</div><div id="farveproever"><p style="text-align: left;">{product_samples}</p></div></div><div id="farveproeve_hojre_wrapper"><div id="farveproeve_addressefelt"><p style="text-align: left;">{address_fields}</p></div><div id="farveproeve_sendknap"><p style="text-align: left;">{submit_button_sample}</p></div></div></div></div>';
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
<form action="" method="post" name="frmcatalogsample" id="frmcatalogsample">
	<?php
	if (strstr($template_desc, "{product_samples}"))
	{
		$catalog_sample = $model->getCatalogSampleList();
		$saple_data     = "";

		for ($k = 0, $kn = count($catalog_sample); $k < $kn; $k++)
		{
			$saple_data .= $catalog_sample[$k]->sample_name . "<br>";

			$catalog_colour = $model->getCatalogSampleColorList($catalog_sample[$k]->sample_id);

			$saple_data .= "<table cellpadding='0' border='0' cellspacing='0'><tr>";
			$saple_check = "<tr>";

			for ($c = 0, $cn = count($catalog_colour); $c < $cn; $c++)
			{
				$saple_data .= "<td style='padding-right:2px;'>";

				if ($catalog_colour[$c]->is_image == 1)
				{
					$saple_data .= "<img src='" . $catalog_colour[$c]->code_image . "' border='0'  width='27' height='27'/><br>";
				}
				else
				{
					$saple_data .= '<div style="background-color:' . $catalog_colour[$c]->code_image . ';width: 27px; height:27px; "></div> ';
				}

				$saple_check .= "<td><input type='checkbox' name='sample_code[]' id='sample_code" . $c . "' value='" . $catalog_colour[$c]->colour_id . "' ></td>";
				$saple_data .= "</td>";
			}

			$saple_check .= "</tr>";
			$saple_data .= "</tr>" . $saple_check . "<tr><td>&nbsp;</td></tr></table>";
		}

		$template_desc = str_replace("{product_samples}", $saple_data, $template_desc);
	}

	if (strstr($template_desc, "{address_fields}"))
	{
		$address_fields = $extra_field->list_all_field(9, 0, '', 1);
		$myfield        = "<table class='admintable' border='0'>";
		$myfield .= $address_fields;
		$myfield .= '</table>';

		$template_desc = str_replace("{address_fields}", $myfield, $template_desc);
	}

	$template_desc = str_replace("{name_lbl}", JText::_('COM_REDSHOP_NAME_LBL'), $template_desc);
	$template_desc = str_replace("{name}", '<input type="text" name="name_2" id="name" />', $template_desc);
	$template_desc = str_replace("{email_lbl}", JText::_('COM_REDSHOP_EMAIL_LBL'), $template_desc);
	$template_desc = str_replace("{email_address}", '<input type="text" name="email_address" id="email_address" />', $template_desc);

	$template_desc = str_replace("{submit_button_sample}", '<input type="submit" name="samplesend" id="samplesend" onClick="return getCatalogSampleValidation();" value="' . JText::_('COM_REDSHOP_SAMPLE_SEND') . '" />', $template_desc);

	echo $template_desc;
	?>
	<input type="hidden" name="view" value="catalog" id="view"/>
	<input type="hidden" name="option" id="option" value="com_redshop"/>
	<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
	<input type="hidden" name="task" value="catalogsample_send"/>
	<input type="hidden" name="layout" value="<?php echo $layout; ?>"/>
</form>
<script type="text/javascript">
	function getCatalogSampleValidation() {
		var frm = document.frmcatalogsample;
		var email = frm.email_address.value;
		var patt1 = new RegExp("([a-z0-9_]+)@([a-z0-9_-]+)[.][a-z]");

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
		else if (patt1.test(email) == false)
		{
			alert("<?php echo JText::_('COM_REDSHOP_EMAIL_ADDRESS_NOT_VALID');?>");
			frm.email_address.focus();
			return false;
		}
		var sampleArr = new Array;

		if (document.getElementsByName('sample_code[]')) {
			var sName = document.getElementsByName('sample_code[]');
			var i = 0;

			for (var p = 0; p < sName.length; p++) {
				if (sName[p].checked) {
					sampleArr[i++] = sName[p].value;
				}
			}
		}

		if (sampleArr.length > 0) {
			return true;
		}
		else {
			alert("<?php echo JText::_('COM_REDSHOP_SELECT_SAMPLE_COLOR');?>");
			return false;
		}
	}
</script>
