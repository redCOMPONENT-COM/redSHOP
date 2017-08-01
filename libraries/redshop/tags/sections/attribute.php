<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Tags replacer abstract class
 *
 * @since  2.0.4
 */
class RedshopTagsSectionsAttribute extends RedshopTagsAbstract
{
	/**
	 * @var    array
	 *
	 * @since  2.0.4
	 */
	public $tags = array(
		'{product_attribute}'
	);

	/**
	 * Init
	 *
	 * @return  void
	 *
	 * @since   2.0.4
	 */
	public function init()
	{
		$productAttribute = isset($this->data['product_attribute'])? $this->data['product_attribute']: '';

		$html = RedshopLayoutHelper::render(
			'tags.product.product_attribute',
				array(
					'productAttribute' 	=> $productAttribute,
				),
				'',
				array(
					'component' => 'com_redshop'
				)
			);

		$this->addReplace('{product_attribute}', $html);
	}
}
