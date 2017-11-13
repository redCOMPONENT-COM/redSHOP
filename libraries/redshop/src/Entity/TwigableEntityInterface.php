<?php
/**
 * @package     Aesir
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Entity;

defined('_JEXEC') or die;

/**
 * This class has an associated Twig entity.
 *
 * @since  __DEPLOY_VERSION__
 */
interface TwigableEntityInterface
{
	/**
	 * Get the associated Twig entity.
	 *
	 * @return  mixed
	 */
	public function getTwigEntity();
}
