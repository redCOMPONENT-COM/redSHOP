<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewSample_detail extends RedshopViewAdmin
{
	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	public function display($tpl = null)
	{

		JToolBarHelper::title(JText::_('COM_REDSHOP_PRODUCT_SAMPLE'), 'redshop_colorsample48');

		$document = JFactory::getDocument();

		/** @scrutinizer ignore-deprecated */JHtml::stylesheet('com_redshop/colorpicker.min.css', array(), true);
		/** @scrutinizer ignore-deprecated */JHtml::stylesheet('com_redshop/redshop.layout.min.css', array(), true);
		/** @scrutinizer ignore-deprecated */JHtml::script('com_redshop/redshop.validation.min.js', false, true);
		/** @scrutinizer ignore-deprecated */JHtml::script('com_redshop/colorpicker.min.js', false, true);
		/** @scrutinizer ignore-deprecated */JHtml::script('com_redshop/eye.min.js', false, true);
		/** @scrutinizer ignore-deprecated */JHtml::script('com_redshop/redshop.utils.min.js', false, true);
		/** @scrutinizer ignore-deprecated */JHtml::script('com_redshop/json.min.js', false, true);

		$uri = JUri::getInstance();

		$this->setLayout('default');

		$lists = array();

		$detail = $this->get('data');

		$layout = JFactory::getApplication()->input->getCmd('layout', 'default');

		$this->setLayout($layout);

		$model = $this->getModel('sample_detail');

		if ($layout == 'default')
		{
			$isNew      = ($detail->sample_id < 1);
			$color_data = $model->color_Data($detail->sample_id);

			if (!is_array($color_data))
			{
				$color_data = array();
			}

			$lists['color_data'] = $color_data;
		}
		else
		{
			$isNew = ($detail->catalog_id < 1);
		}

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_PRODUCT_SAMPLE') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_catalogmanagement48');

		JToolBarHelper::save();

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);

		$this->lists       = $lists;
		$this->detail      = $detail;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
