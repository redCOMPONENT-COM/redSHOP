<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Load redSHOP Library
JLoader::import('redshop.library');

/**
 * Generate Bundle product
 *
 * @since  1.0.0
 */
class PlgSystemRedSHOP_Product_Bundle extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object  $subject  The object to observe
	 * @param   array   $config   An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 * @since  1.0.0
	 */
	public function __construct(&$subject, $config = array())
	{
		JPlugin::loadLanguage('plg_system_redshop_product_bundle');

		parent::__construct($subject, $config);
	}

	/**
	 * onTemplateSections
	 *
	 * @param   array  $options  Template array
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function onTemplateSections(&$options)
	{
		$options['bundle_template'] = JText::_('PLG_SYSTEM_REDSHOP_PRODUCT_BUNDLE_TEMPLATE');
	}
}
