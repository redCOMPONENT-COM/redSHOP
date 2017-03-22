<?php
/**
 * @package     Redshop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Helper;

use Redshop\App;

defined('_JEXEC') or die;

/**
 * Asset helper
 *
 * @since  __DEPLOY_VERSION__
 */
class AssetHelper
{
	/**
	 * We are using file for saving configuration variables
	 * We need some variables that can be uses as dynamically
	 * Here is the logic to define that variables
	 *
	 * IMPORTANT: we need to call this function in plugin or module manually to see the effect of this variables
	 *
	 * @return void
	 */
	public static function defineDynamicVars()
	{
		$config = App::getConfig();

		$config->set('SHOW_PRICE', UserHelper::isShowPrice());
		$config->set('USE_AS_CATALOG', UserHelper::isUseCatalog());

		$quotationModePre = (int) $config->get('DEFAULT_QUOTATION_MODE_PRE');

		$config->set('DEFAULT_QUOTATION_MODE', $quotationModePre);

		if ($quotationModePre == 1)
		{
			$config->set('DEFAULT_QUOTATION_MODE', (int) UserHelper::setQuotationMode());
		}
	}
}
