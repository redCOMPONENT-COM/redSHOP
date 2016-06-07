<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewRedshop extends RedshopView
{
	public $layout;

	public function display($tpl = null)
	{
		$this->layout = JRequest::getCmd('layout', 'default');

		JToolBarHelper::title('', 'redshop_261-x-88');

		if ($this->layout != "noconfig")
		{
			JToolBarHelper::custom('update', 'redshop_importexport32', JText::_('COM_REDSHOP_UPDATE_TITLE'),
				JText::_('COM_REDSHOP_UPDATE_TITLE'), false, false
			);
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

		$this->redshopversion = RedshopModel::getInstance('Configuration', 'RedshopModel')->getcurrentversion();

		$model = $this->getModel();

		if (DISPLAY_NEW_CUSTOMERS)
		{
			$this->newcustomers = $model->getNewcustomers();
		}

		if (DISPLAY_NEW_ORDERS)
		{
			$this->neworders = $model->getNeworders();
		}

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
