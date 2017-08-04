<?php
/**
 * @package     Aesir.Plugin
 * @subpackage  Aesir_Field.Redshop_Product
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('reditem.library');
JLoader::registerPrefix('PlgAesir_FieldRedshop_Product', __DIR__);

use Aesir\App;
use Aesir\Plugin\AbstractFieldPlugin;
use Aesir\Entity\FieldInterface;
use Aesir\Entity\EntityCollection;

/**
 * Item related plugin
 *
 * @since  1.0.0
 */
final class PlgAesir_FieldRedshop_Product extends AbstractFieldPlugin
{
	/**
	 * Type for the form type="Redshop_Product" tag
	 *
	 * @var  string
	 */
	protected $formFieldType = 'PlgAesir_FieldRedshop_Product.Redshop_Product';

	/**
	 * Template section
	 *
	 * @var  string
	 */
	protected $templateSection = 'field_redshop_product';

	/**
	 * Get the attributes applicable to an item field.
	 *
	 * @param   FieldInterface  $field  Field being processed.
	 *
	 * @return  array
	 *
	 * @since   1.0.8
	 */
	protected function getFieldXmlAttributes(FieldInterface $field)
	{
		$attributes = parent::getFieldXmlAttributes($field);

		$attributes['default'] = $field->get('default');

		if ($field->getParams()->get('addSelectOption', 'true') === 'true')
		{
			$attributes['addSelectOption'] = 'true';
		}

		return $attributes;
	}

	/**
	 * Decode a field value from database.
	 *
	 * @param   FieldInterface  $field  Field where value comes from.
	 * @param   mixed           $value  Value to decode
	 *
	 * @return  mixed
	 */
	public function onReditemFieldDecodeDatabaseValue(FieldInterface $field, $value)
	{
		if ($field->type !== $this->_name)
		{
			return;
		}

		$decodedValues = json_decode($value);

		return $decodedValues ? array_unique($decodedValues): null;
	}

	/**
	 * Encode a field value to store it in db, etc.
	 *
	 * @param   FieldInterface  $field  Field where value comes from.
	 * @param   mixed           $value  Value to encode
	 *
	 * @return  mixed
	 */
	public function onReditemFieldEncodeDatabaseValue(FieldInterface $field, $value)
	{
		if ($field->type !== $this->_name)
		{
			return;
		}

		$value = array_values(array_filter((array) $value, 'strlen'));

		return empty($value) ? '' : json_encode($value);
	}
}
