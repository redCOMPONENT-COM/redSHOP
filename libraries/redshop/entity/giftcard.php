<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2012 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

use Redshop\Entity\AbstractEntity;
use Redshop\Entity\Twig;
use Redshop\Entity\TwigableEntityInterface;

/**
 * Giftcard Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       2.0.6
 */
class RedshopEntityGiftcard extends AbstractEntity implements TwigableEntityInterface
{
	/**
	 * Method for get Twig Entity
	 *
	 * @return \Redshop\Entity\Twig\Giftcard
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function getTwigEntity()
	{
		return new Twig\Giftcard($this);
	}
}
