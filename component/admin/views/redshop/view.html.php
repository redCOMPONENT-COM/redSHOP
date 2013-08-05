<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('restricted access');

jimport('joomla.application.component.view');

class redshopViewredshop extends JView
{
	public function display($tpl = null)
	{
		$layout = JRequest::getCmd('layout');

		JToolBarHelper::title('&nbsp;', 'redshop_261-x-88');

		if ($layout != "noconfig")
		{
			JToolBarHelper::custom('statistic', 'redshop_statistic32', JText::_('COM_REDSHOP_STATISTIC'),
				JText::_('COM_REDSHOP_STATISTIC'), false, false
			);
			JToolBarHelper::custom('wizard', 'redshop_configwizrd32', JText::_('COM_REDSHOP_WIZARD'),
				JText::_('COM_REDSHOP_WIZARD'), false, false
			);
			JToolBarHelper::custom('configuration', 'redshop_icon-32-settings', JText::_('COM_REDSHOP_CONFIG'),
				JText::_('COM_REDSHOP_CONFIG'), false, false
			);
			JToolBarHelper::help('redshop', true);
		}

		$user = JFactory::getUser();

		if (ENABLE_BACKENDACCESS)
		{
			$this->access_rslt = new Redaccesslevel;
			$this->access_rslt = $this->access_rslt->checkaccessofuser($user->gid);
		}

		$filteroption = JRequest::getVar('filteroption');

		if (isset($filteroption))
		{
			$filteroption = $filteroption;
		}
		else
		{
			$filteroption = 4;
		}

		$statsticmodel = JModel::getInstance('statistic', 'statisticModel');
		$this->turnover = $statsticmodel->getTotalTurnover();

		$document = JFactory::getDocument();
		$document->addScript('http://www.google.com/jsapi');

		$lists = array();
		$option = array();
		$option[] = JHTML::_('select.option', '0"selected"', JText::_('COM_REDSHOP_Select'));
		$option[] = JHTML::_('select.option', '1', JText::_('COM_REDSHOP_DAILY'));
		$option[] = JHTML::_('select.option', '2', JText::_('COM_REDSHOP_WEEKLY'));
		$option[] = JHTML::_('select.option', '3', JText::_('COM_REDSHOP_MONTHLY'));
		$option[] = JHTML::_('select.option', '4', JText::_('COM_REDSHOP_YEARLY'));
		$lists['filteroption'] = JHTML::_('select.genericlist', $option, 'filteroption',
			'class="inputbox" size="1" onchange="document.chartform.submit();"', 'value', 'text', $filteroption
		);

		$configmodel = JModel::getInstance('configuration', 'configurationModel');

		$this->redshopversion = $configmodel->getcurrentversion();

		$model = $this->getModel();

		if (DISPLAY_NEW_CUSTOMERS)
		{
			$this->newcustomers = $model->getNewcustomers();
		}

		if (DISPLAY_NEW_ORDERS)
		{
			$this->neworders = $model->getNeworders();
		}

		$this->lists  = $lists;
		$this->layout = $layout;

		parent::display($tpl);
	}

	public function quickiconButton($link, $image, $text, $modal = 0)
	{
		// Initialise variables
		$lang = JFactory::getLanguage();
		?>

		<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
			<div class="icon">
				<?php

				if ($modal == 1)
				{
				JHTML::_('behavior.modal');
				?>
				<a href="<?php echo $link . '&amp;tmpl=component'; ?>" style="cursor:pointer" class="modal"
				   rel="{handler: 'iframe', size: {x: 800, y: 650}}">
					<?php
				}
				else
				{
					?>
					<a href="<?php echo $link; ?>">
						<?php
				}

						echo JHTML::_('image', REDSHOP_ADMIN_IMAGES_ABSPATH . $image, $text);
						?>
						<span><?php echo $text; ?></span>
					</a>
			</div>
		</div>
	<?php
	}
}
