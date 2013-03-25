<?php
// no direct access
defined('_JEXEC') or die;

// Import library dependencies
jimport('joomla.plugin.plugin');

class plgredshop_productexample extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for
	 * plugins because func_get_args ( void ) returns a copy of all passed arguments
	 * NOT references.  This causes problems with cross-referencing necessary for the
	 * observer design pattern.
	 */
	function plgredshop_productexample(&$subject)
	{
		parent::__construct($subject);

		// load plugin parameters
		$this->_plugin = JPluginHelper::getPlugin('redshop_product', 'onPrepareProduct');
		$this->_params = new JRegistry($this->_plugin->params);
	}

	/**
	 * Example prepare redSHOP Product method
	 *
	 * Method is called by the product view
	 *
	 * @param    object        The Product Template Data
	 * @param    object        The product params
	 * @param    object        The product object
	 */
	function onPrepareProduct(&$template, &$params, $product)
	{
		$app = & JFactory::getApplication();

		$template = str_replace("{product_template_plugin_example_demo}", "Product Template Plugin Demo Content...", $template);

		// Plugin code goes here.
		return;
	}

	/**
	 * Example after display title method
	 *
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param    object         The Product Template Data
	 * @param    object         The product params
	 * @param    int            The product object
	 *
	 * @return    string
	 */
	function onAfterDisplayProductTitle(&$template, &$params, $product)
	{
		$string = "";

		return $string;
	}

	/**
	 * Example before display redSHOP Product method
	 *
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param    object         The Product Template Data
	 * @param    object         The product params
	 * @param    int            The product object
	 *
	 * @return    string
	 */
	function onBeforeDisplayProduct(&$template, &$params, $product)
	{
		$string = "";

		return $string;
	}

	/**
	 * Example after display redSHOP Product method
	 *
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param    object         The Product Template Data
	 * @param    object         The Product params
	 * @param    int            The product object
	 *
	 * @return    string
	 */
	function onAfterDisplayProduct(&$template, &$params, $product)
	{
		$string = "";

		return $string;
	}

	/**
	 * Example before save Product method
	 *
	 * Method is called right before product is saved into the database.
	 * Product object is passed by reference, so any changes will be saved!
	 * NOTE:  Returning false will abort the save with an error.
	 *    You can set the error by calling $product->setError($message)
	 *
	 * @param    object        A JTableproduct_detail object
	 * @param    bool          If the product is just about to be created
	 *
	 * @return    bool        If false, abort the save
	 */
	function onBeforeProductSave(&$product, $isnew)
	{
		return true;
	}

	/**
	 * Example after save product method
	 * Product is passed by reference, but after the save, so no changes will be saved.
	 * Method is called right after the product is saved
	 *
	 *
	 * @param    object        A JTableproduct_detail object
	 * @param    bool          If the product is just about to be created
	 *
	 * @return    void
	 */
	function onAfterProductSave(&$product, $isnew)
	{
		return;
	}
}

?>