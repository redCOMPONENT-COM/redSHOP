<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;
use Redshop\Entity\EntityCollection;

/**
 * Giftcard detail view
 *
 * @package     RedSHOP.Site
 * @subpackage  View
 * @since       1.6
 */
class RedshopViewGiftcard extends RedshopView
{
	/**
	 * @var  mixed
	 */
	public $detail;

	/**
	 * @var  string
	 */
	public $template;

	/**
	 * @var  string
	 */
	public $pageheadingtag;

	/**
	 * @var  Registry
	 */
	public $params;

	/**
	 * @var  string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $content;

	/**
	 * Display the view.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed         A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		// Request variables
		$params = JComponentHelper::getParams('com_redshop');

		$giftcardId = JFactory::getApplication()->input->get('gid', 0);

		/** @var RedshopModelGiftcard $model */
		$model            = $this->getModel();
		$giftcardTemplate = $model->getGiftcardTemplate();
		$detail           = $this->get('data');

		if (!$giftcardId && isset($giftcardTemplate[0]) && $giftcardTemplate[0]->twig_support && $giftcardTemplate[0]->twig_enable)
		{
			/**
			 * @TODO: Need add default template here.
			 */
			$content = $giftcardTemplate[0]->template_desc;

			// Twig process
			$templateName = 'giftcard-list-' . $giftcardTemplate[0]->template_id . '.html';

			$loader = new Twig_Loader_Array(
				array(
					$templateName => $content
				)
			);

			$twig = Redshop::getTwig($loader);

			$items = new EntityCollection;
			$items->loadArray($detail, 'RedshopEntityGiftcard', 'giftcard_id');

			$this->content = $twig->render(
				$templateName,
				array(
					'giftcards' => $items->isEmpty() ? null : $items->toTwigEntities(),
					'page'      => $_SERVER
				)
			);
		}

		$this->detail         = $detail;
		$this->template       = $giftcardTemplate;
		$this->pageheadingtag = JText::_('COM_REDSHOP_REDSHOP');
		$this->params         = $params;

		parent::display($tpl);
	}
}
