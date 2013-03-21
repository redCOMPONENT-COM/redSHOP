<?php
/**
 * @copyright      Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * Joomla! System redproductzoom Me Plugin
 *
 * @package        Joomla.Plugin
 * @subpackage     System.redproductzoom
 */
class plgSystemredproductzoom extends JPlugin
{

function onBeforeRender()
{
	$app = JFactory::getApplication();

	// No redproductzoom me for admin
	if ($app->isAdmin())
	{
		return;
	}

	require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'redshop.cfg.php';
	require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'configuration.php';
	$Redconfiguration = new Redconfiguration();
	$Redconfiguration->defineDynamicVars();

	require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'product.php';
	require_once JPATH_SITE . DS . 'plugins' . DS . 'system' . DS . 'redproductzoom' . DS . 'ajax' . DS . 'helper.php';

	if (JRequest::getCmd("option", "") != 'com_redshop') return;

	if (JRequest::getCmd("view", "") != 'product') return;

	$pid = JRequest::getInt("pid", 0);

	if ($pid <= 0) return;

	$document =& JFactory::getDocument();

	//$document->addScript(JPATH_SITE.DS.'plugins'.DS.'system'.DS.'redproductzoom'.DS.'js'.DS.'jquery-1.6.js');
	$document->addScript('plugins' . DS . 'system' . DS . 'redproductzoom' . DS . 'js' . DS . 'jquery.jqzoom-core.js');
	$document->addStyleSheet('plugins' . DS . 'system' . DS . 'redproductzoom' . DS . 'css' . DS . 'jquery.jqzoom.css');

	$zoomproducthelper = new zoomproducthelper();

	$ph_thumb = PRODUCT_MAIN_IMAGE_HEIGHT;
	$pw_thumb = PRODUCT_MAIN_IMAGE;

	$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $pid);

	//Preselection Start
	$preselection_result = $this->checkforpreselection($pid);
	$product_data = $this->getProductData($pid);


	# get Product Main Image functionality
	if (count($preselection_result) > 0)
		$mainimage = $zoomproducthelper->replaceProductImage($product_data, "", "", "", $pw_thumb, $ph_thumb, PRODUCT_DETAIL_IS_LIGHTBOX, 0, $preselection_result);
	else
		$mainimage = $zoomproducthelper->getProductImage($pid, $link, $pw_thumb, $ph_thumb, PRODUCT_DETAIL_IS_LIGHTBOX);
	# End

	$moreimage_response = $preselection_result['response'];

	if ($moreimage_response != "")
		$additionalImage = $moreimage_response;
	else
		$additionalImage = $zoomproducthelper->getAdditionalImageforZoom($pid);

	//Preselection End
	# Get product Additioanl Image
	//$additionalImage = $zoomproducthelper->getAdditionalImageforZoom($pid);

	ob_clean();
	ob_start();


	?>
	<script type="text/javascript">
		$(document).ready(function () {

			$('.product_image').html("<?php echo addslashes($mainimage);?>").addClass('clearfix');
			$('.product_more_images').html("<?php echo addslashes($additionalImage);?>").addClass('clearfix');

			$('.jqzoom').jqzoom({
				zoomType: 'standard',
				lens: true,
				preloadImages: false,
				alwaysOn: false
			});
			$('.zoomPad img').css('border', 'none');

		});

		function displayAdditionalImage(product_id, accessory_id, relatedprd_id, selectedproperty_id, selectedsubproperty_id) {

			if (product_id == undefined) {
				return false;
			}

			var suburl = "&product_id=" + product_id;
			suburl = suburl + "&accessory_id=" + accessory_id;
			suburl = suburl + "&relatedprd_id=" + relatedprd_id;
			suburl = suburl + "&property_id=" + selectedproperty_id;
			suburl = suburl + "&subproperty_id=" + selectedsubproperty_id;

			var txtresponse = "";

			if (accessory_id != 0) {
				prefix = "acc_";
				product_id = accessory_id;
			} else if (relatedprd_id != 0) {
				prefix = "rel_";
			} else {
				prefix = "prd_";
			}
			collectAttributes(product_id, 0, relatedprd_id);

			if (document.getElementById(prefix + "main_imgwidth")) {
				suburl = suburl + "&main_imgwidth=" + parseInt(document.getElementById(prefix + "main_imgwidth").value);
			}

			if (document.getElementById(prefix + "main_imgheight")) {
				suburl = suburl + "&main_imgheight=" + parseInt(document.getElementById(prefix + "main_imgheight").value);
			}
			var changehref = 0;

			if (document.getElementById('a_main_image' + product_id) || document.getElementById('main_image' + product_id)) {
				if (document.getElementById('a_main_image' + product_id)) {
					var tmphref = document.getElementById('a_main_image' + product_id).href;
					tmphref = tmphref.split("");
					var newhref = tmphref.reverse();
					newhref = newhref.join("");
					tmphref = newhref.split(".");
					tmphref = tmphref[0].split("");
					newhref = tmphref.reverse();
					newhref = newhref.join("");
				}
				else {
					var tmphref = document.getElementById('main_image' + product_id).src;
					tmphref = tmphref.split("");
					var newhref = tmphref.reverse();
					newhref = newhref.join("");
					tmphref = newhref.split(".");
					tmphref = tmphref[0].split("");
					newhref = tmphref.reverse();
					newhref = newhref.join("");
					newhref = newhref.split("&");
					newhref = newhref[0];
				}


				// change extension to lowercase
				newhref = newhref.toLowerCase();

				if (newhref == "jpg" || newhref == "jpeg" || newhref == "png" || newhref == "gif" || newhref == "bmp") {
					changehref = 1;
				}
			}

			//var url = site_url+"index.php?option=com_redshop&view=product&task=displayAdditionImage&redview="+REDSHOP_VIEW+"&redlayout="+REDSHOP_LAYOUT+"&tmpl=component";
			var url = site_url + "plugins/system/redproductzoom/ajax/displayAdditionImage.php?redview=" + REDSHOP_VIEW + "&redlayout=" + REDSHOP_LAYOUT + "&tmpl=component";
			url = url + suburl;


			request = getHTTPObject();
			request.onreadystatechange = function () {

				// if request object received response

				if (request.readyState == 4) {
					var arrResponse = "";
					txtresponse = request.responseText;
					arrResponse = txtresponse.split("`_`");
					//alert(arrResponse);

					if (arrResponse[9] != "" && document.getElementById('product_number_variable' + product_id)) {
						document.getElementById('product_number_variable' + product_id).innerHTML = arrResponse[9];
					}
					subproperty_main_image = arrResponse[4];

					if (arrResponse[4] != "") {
						var gmyhtml = '<a href="' + arrResponse[2] + '" class="jqzoom" rel=\'gal1\'  title="' + arrResponse[3] + '" ><img src="' + arrResponse[4] + '"  title="triumph"  style="border: 4px solid #666;"></a>';
						$(".redzoom").html(gmyhtml);
					}
					//Preselection start
					$('.product_more_images').html(arrResponse[1]);
					//Preselection end
					if (document.getElementById('hidden_attribute_cartimage' + product_id)) {
						document.getElementById('hidden_attribute_cartimage' + product_id).value = arrResponse[12];
					}
//						alert(arrResponse[6]);

					if (document.getElementById('stockImage' + product_id) && arrResponse[5] != "") {
						document.getElementById('stockImage' + product_id).src = arrResponse[5];
					}

					if (document.getElementById('stockImageTooltip' + product_id) && arrResponse[6] != "") {
						document.getElementById('stockImageTooltip' + product_id).innerHTML = arrResponse[6];
					}

					if (document.getElementById('displayProductInStock' + product_id) && arrResponse[10] != "") {
						document.getElementById('displayProductInStock' + product_id).innerHTML = arrResponse[10];
					}

					if (document.getElementById('ProductAttributeMinDelivery' + product_id) && arrResponse[7] != "") {
						document.getElementById('ProductAttributeMinDelivery' + product_id).innerHTML = arrResponse[7];
					}
					//alert(arrResponse[11]);

					if (document.getElementById('stock_status_div' + product_id) && arrResponse[11] != "") {
						document.getElementById('stock_status_div' + product_id).innerHTML = arrResponse[11];
					}

					// preload slimbox
					//preloadSlimbox();

					jQuery('.jqzoom').jqzoom({
						zoomType: 'standard',
						lens: true,
						preloadImages: false,
						alwaysOn: false
					});
					jQuery('.zoomPad img').css('border', 'none');
				}
			};
			request.open("GET", url, true);
			request.send(null);
		}
	</script>
	<?php
	$output = ob_get_contents();
	ob_end_clean();
	$document->addCustomTag($output);
}
	//Preselection Start
	function checkforpreselection($pid)
	{
		$zoomproducthelper = new zoomproducthelper();
		$childproduct = $zoomproducthelper->getChildProduct($pid);
		$product_data = $this->getProductData($pid);
		$ph_thumb = PRODUCT_MAIN_IMAGE_HEIGHT;
		$pw_thumb = PRODUCT_MAIN_IMAGE;

		if (count($childproduct) > 0)
		{
			if (PURCHASE_PARENT_WITH_CHILD == 1)
			{
				$isChilds = false;
				$attributes_set = array();

				if ($product_data->attribute_set_id > 0)
				{
					$attributes_set = $zoomproducthelper->getProductAttribute(0, $product_data->attribute_set_id, 0, 1);
				}

				$attributes = $zoomproducthelper->getProductAttribute($pid);
				$attributes = array_merge($attributes, $attributes_set);
			}
			else
			{
				$isChilds = true;
				$attributes = array();
			}
		}
		else
		{

			$isChilds = false;
			$attributes_set = array();

			if ($product_data->attribute_set_id > 0)
			{
				$attributes_set = $zoomproducthelper->getProductAttribute(0, $product_data->attribute_set_id, 0, 1);
			}

			$attributes = $zoomproducthelper->getProductAttribute($pid);
			$attributes = array_merge($attributes, $attributes_set);
		}

		if (count($attributes) > 0)
		{
			$selectedpropertyId = 0;
			$selectedsubpropertyId = 0;

			for ($a = 0; $a < count($attributes); $a++)
			{
				$selectedId = array();
				$property = $zoomproducthelper->getAttibuteProperty(0, $attributes[$a]->attribute_id);

				if ($attributes[$a]->text != "" && count($property) > 0)
				{
					for ($i = 0; $i < count($property); $i++)
					{
						if ($property[$i]->setdefault_selected)
						{
							$selectedId[] = $property[$i]->property_id;
						}
					}

					if (count($selectedId) > 0)
					{
						$selectedpropertyId = $selectedId[count($selectedId) - 1];
						$subproperty = $zoomproducthelper->getAttibuteSubProperty(0, $selectedpropertyId);
						$selectedId = array();

						for ($sp = 0; $sp < count($subproperty); $sp++)
						{
							if ($subproperty[$sp]->setdefault_selected)
							{
								$selectedId[] = $subproperty[$sp]->subattribute_color_id;
							}
						}

						if (count($selectedId) > 0)
						{
							$selectedsubpropertyId = $selectedId[count($selectedId) - 1];
						}
					}
				}
			}

			$preselectedresult = $zoomproducthelper->displayAdditionalImage($pid, 0, 0, $selectedpropertyId, $selectedsubpropertyId, $pw_thumb, $ph_thumb, $redview = 'product');

			return $preselectedresult;

		}

	}

	function getProductData($pid)
	{
		$db = JFactory::getDBO();
		$sql = "SELECT * FROM #__redshop_product WHERE product_id=" . $pid;
		$db->setQuery($sql);
		$products = $db->loadObject();

		return $products;
	}
	//Preselection End
}