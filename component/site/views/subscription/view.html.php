<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('restricted access');

jimport('joomla.application.component.view');

class SubscriptionViewsubscription extends JView
{
	public function display ($tpl=null)
	{
		global $mainframe;

		// Include Javacripts
		JHTML::_('behavior.modal');
		JHTML::Script('attribute.js', 'components/com_redshop/assets/js/', false);
		JHTML::Script('common.js', 'components/com_redshop/assets/js/', false);
		JHTML::Script('jquery.js', 'components/com_redshop/assets/js/', false);
		JHTML::Script('redBOX.js', 'components/com_redshop/assets/js/', false);
		JHTML::Script('json.js', 'components/com_redshop/assets/js/', false);
		$Itemid  = JRequest::getVar('Itemid');
		$model   = $this->getModel('subscription');
		$option  = JRequest::getVar('option', 'com_redshop');
		$catid   = JRequest::getInt('cid', 0, '', 'int');
		$layout  = JRequest::getVar('layout');
		$detail  = $model->getdata();

		// Load Template
		$loadSubscriptionOverviewTemplate = $model->loadSubscriptionTemplate('subscription_template_overview');
		$loadSubscriptionDetailTemplate   = $model->loadSubscriptionTemplate('subscription_template_detail');
		$loadSubscriptionDownloadTemplate = $model->loadSubscriptionTemplate('subscription_template_download');

		if ($layout == "detail")
		{
			$this->assignRef('loadSubscriptionDetailTemplate', $loadSubscriptionDetailTemplate);
			$this->setLayout('detail');
		}
		elseif ($layout == "download")
		{
			$this->assignRef('loadSubscriptionDownloadTemplate', $loadSubscriptionDownloadTemplate);
			$this->setLayout('download');
		}

		$this->assignRef('loadSubscriptionOverviewTemplate', $loadSubscriptionOverviewTemplate);
		$this->assignRef('detail', $detail);
		parent::display($tpl);
	}
}
