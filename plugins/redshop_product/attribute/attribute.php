<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

// Import library dependencies
jimport('joomla.plugin.plugin');
require_once(JPATH_SITE . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'product.php');

class plgredshop_productattribute extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for
	 * plugins because func_get_args ( void ) returns a copy of all passed arguments
	 * NOT references.  This causes problems with cross-referencing necessary for the
	 * observer design pattern.
	 */
	function plgredshop_productattribute(&$subject)
	{
		parent::__construct($subject);

		// load plugin parameters
		$this->_plugin = JPluginHelper::getPlugin('redshop_product', 'onPrepareProduct');
		$this->_params = new JRegistry($this->_plugin->params);
	}

	/**
	 * attribute prepare redSHOP Product method
	 *
	 * Method is called by the product view
	 *
	 * @param    object        The Product Template Data
	 * @param    object        The product params
	 * @param    object        The product object
	 */
function onPrepareProduct(&$template, &$params, $product)
{


	$document =& JFactory::getDocument();
	$document->addScriptDeclaration("

		/**
		 * This function can be override via redSHOP Plugin
		 *
		 * @params: orgarg  All the arguments array from the original function
		 */
		function onchangePropertyDropdown(orgarg){
			if(orgarg[4]!=0)
			document.getElementById('atrib_subprop_price'+orgarg[0]).style.display = 'none';
			else
			document.getElementById('atrib_subprop_price'+orgarg[0]).style.display = '';
			return true;
		}   ");


	$producthelper = new producthelper();
	$total_attributes = 0;
	// checking for child products
	$childproduct = $producthelper->getChildProduct($product->product_id);

	if (count($childproduct) > 0)
	{
		if (PURCHASE_PARENT_WITH_CHILD == 1)
		{
			$isChilds = false;
			// get attributes
			$attributes_set = array();

			if ($product->attribute_set_id > 0)
			{
				$attributes_set = $producthelper->getProductAttribute(0, $product->attribute_set_id, 0, 1);
			}
			$attributes = $producthelper->getProductAttribute($product->product_id);
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
		// get attributes
		$attributes_set = array();

		if ($product->attribute_set_id > 0)
		{
			$attributes_set = $producthelper->getProductAttribute(0, $product->attribute_set_id, 0, 1);
		}
		$attributes = $producthelper->getProductAttribute($product->product_id);
		$attributes = array_merge($attributes, $attributes_set);
	}

	$checkforpreselection = 0;
	//Check for preselection
	$attribute_template = $producthelper->getAttributeTemplate($template);

	if (count($attributes) > 0 && count($attribute_template) > 0)
	{
		$selectedpropertyId = 0;
		$selectedsubpropertyId = 0;

		for ($a = 0; $a < count($attributes); $a++)
		{
			$selectedId = array();
			$property = $producthelper->getAttibuteProperty(0, $attributes[$a]->attribute_id);

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
					$subproperty = $producthelper->getAttibuteSubProperty(0, $selectedpropertyId);
					$checkforpreselection++;
					$selectedId = array();

					for ($sp = 0; $sp < count($subproperty); $sp++)
					{
						if ($subproperty[$sp]->setdefault_selected)
						{
							$checkforpreselection++;
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
	}
	$total_attributes = count($attributes);
	$total = 0;

	for ($i = 0; $i < count($attributes); $i++)
	{
		$property_subattribute = $this->getattribute_property($attributes[$i]->attribute_id);
		$proper_val = count($property_subattribute);
		$total += $proper_val;

	}

	if ($total_attributes > 0 && strstr($template, "{start_if_attribute}") && strstr($template, "{end_if_attribute}"))
	{
		$template = str_replace("{start_if_attribute}", "<div id='atrib_subprop_price" . $product->product_id . "'>", $template);
		$template = str_replace("{end_if_attribute}", "</div>", $template);

		if ($total > 0 && strstr($template, "{start_if_price_attribute}") && strstr($template, "{end_if_price_attribute}"))
		{
			$template = str_replace("{start_if_price_attribute}", "", $template);
			$template = str_replace("{end_if_price_attribute}", "", $template);
		}
		else
		{
			$template = preg_replace('!{start_if_price_attribute}.*?{end_if_price_attribute}!s', '', $template);
		}
	}
	else
	{
		$template = preg_replace('!{start_if_attribute}.*?{end_if_attribute}!s', '', $template);
	}
	?>
	<script>
		window.onload = function () {
			var attribpreselect = <?php echo $checkforpreselection ?>;

			if (attribpreselect > 0) {
				document.getElementById('atrib_subprop_price').style.display = 'none';
			}

		}
	</script>
	<?php
	return;
}

	function getattribute_property($attrib_id)
	{
		$db =& JFactory::getDBO();
		$query = "SELECT p.property_id,p.property_price,s.subattribute_id,s.subattribute_color_price "
			. " FROM #__redshop_product_attribute_property AS p"
			. " LEFT JOIN #__redshop_product_subattribute_color AS s ON s.subattribute_id=p.property_id "
			. " WHERE p.attribute_id ='" . $attrib_id . "'"
			. " AND (p.property_price!=0 OR s.subattribute_color_price!=0)";
		$db->setQuery($query);
		$result = $db->loadObjectList();

		return $result;
	}
}
?>