<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class RedshopViewGiftcard
 */
class RedshopViewGiftcard extends RedshopView
{
    /**
     * @param null $tpl
     * @return mixed|void
     * @throws Exception
     */
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		// Request variables
		$params = $app->getParams('com_redshop');
		$pageHeadingTag = JText::_('COM_REDSHOP_REDSHOP');
		$model             = $this->getModel('giftcard');
		$giftCardTemplate = $model->getGiftcardTemplate();
		$detail            = $this->get('data');
		$this->detail = $detail;
		$this->template = $giftCardTemplate;
		$this->pageheadingtag = $pageHeadingTag;
		$this->params = $params;
		parent::display($tpl);
	}
}
