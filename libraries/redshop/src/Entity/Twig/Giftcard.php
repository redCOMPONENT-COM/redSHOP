<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2012 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Entity\Twig;

defined('_JEXEC') or die;

/**
 * Giftcard Twig Entity.
 *
 * @since  __DEPLOY_VERSION__
 */
final class Giftcard extends AbstractTwigEntity
{
	/**
	 * Constructor.
	 *
	 * @param   \RedshopEntityGiftcard  $entity  The entity
	 */
	public function __construct(\RedshopEntityGiftcard $entity)
	{
		parent::__construct($entity);
	}

	/**
	 * Get Giftcard ID
	 *
	 * @return  integer
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function getId()
	{
		return $this->entity->getId();
	}

	/**
	 * Get Giftcard name
	 *
	 * @return string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function getName()
	{
		return $this->entity->get('giftcard_name', '');
	}

	/**
	 * Get Giftcard price
	 *
	 * @param   boolean  $format  True for formatted price. False for number.
	 *
	 * @return  string|float  Formatted price.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getPrice($format = true)
	{
		return $format ? $this->getFormattedPrice() : (float) $this->entity->get('giftcard_price', 0.0);
	}

	/**
	 * Get Giftcard formatted price
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getFormattedPrice()
	{
		return \RedshopHelperProductPrice::formattedPrice((float) $this->entity->get('giftcard_price', 0.0));
	}

	/**
	 * Get Giftcard value
	 *
	 * @return  float
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getValue()
	{
		return (float) $this->entity->get('giftcard_value', 0.0);
	}

	/**
	 * Get Giftcard validity
	 *
	 * @return  integer
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function getValidity()
	{
		return (int) $this->entity->get('giftcard_validity', 0);
	}

	/**
	 * Get Giftcard customer amount
	 *
	 * @return  boolean
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function getCustomerAmount()
	{
		return (boolean) $this->entity->get('customer_amount', false);
	}

	/**
	 * Get Giftcard free shipping
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getFreeShipping()
	{
		return (boolean) $this->entity->get('free_shipping', false);
	}

	/**
	 * Get Giftcard description
	 *
	 * @return  string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function getDescription()
	{
		return $this->entity->get('giftcard_desc', '');
	}

	/**
	 * Get Giftcard image full path
	 *
	 * @param   integer  $width      Width of thumb
	 * @param   integer  $height     Height of thumb
	 * @param   integer  $waterMark  0 for disable, -1 for auto, 1 for force generate watermark.
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getImage($width = 0, $height = 0, $waterMark = 0)
	{
		return $this->generateThumb($this->entity->get('giftcard_image', ''), $width, $height, $waterMark);
	}

	/**
	 * Get Giftcard background image full path
	 *
	 * @param   integer  $width      Width of thumb
	 * @param   integer  $height     Height of thumb
	 * @param   integer  $waterMark  0 for disable, -1 for auto, 1 for force generate watermark.
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getBackgroundImage($width = 0, $height = 0, $waterMark = 0)
	{
		return $this->generateThumb($this->entity->get('giftcard_bgimage', ''), $width, $height, $waterMark);
	}

	/**
	 * Get Giftcard background image full path
	 *
	 * @param   string   $imageName  Image name
	 * @param   integer  $width      Width of thumb
	 * @param   integer  $height     Height of thumb
	 * @param   integer  $waterMark  0 for disable, -1 for auto, 1 for force generate watermark.
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function generateThumb($imageName = '', $width = null, $height = null, $waterMark = null)
	{
		if (empty($imageName))
		{
			return '';
		}

		$width     = null === $width ? \Redshop::getConfig()->get('GIFTCARD_LIST_THUMB_WIDTH') : $width;
		$height    = null === $height ? \Redshop::getConfig()->get('GIFTCARD_LIST_THUMB_HEIGHT') : $height;
		$waterMark = null === $waterMark ? \Redshop::getConfig()->get('WATERMARK_GIFTCART_THUMB_IMAGE') : $waterMark;

		return \RedshopHelperMedia::watermark('giftcard', $imageName, $width, $height, $waterMark);
	}
}
