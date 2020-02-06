<?php
/**
 * @package     Phproberto.Joomla-Twig
 * @subpackage  Twig
 *
 * @copyright  Copyright (C) 2017-2018 Roberto Segura López, Inc. All rights reserved.
 * @license    See COPYING.txt
 */

namespace Redshop\Twig\View;

defined('_JEXEC') || die;

use Redshop\View\HtmlView;

/**
 * Base item view.
 *
 * @since  1.2.0
 */
abstract class ItemView extends HtmlView
{
	/**
	 * Load layout data.
	 *
	 * @return  self
	 */
	protected function loadLayoutData()
	{
		$model = $this->getModel();

		return array_merge(
			parent::loadLayoutData(),
			[
				'item'  => $model->getItem(),
				'model' => $model
			]
		);
	}
}
