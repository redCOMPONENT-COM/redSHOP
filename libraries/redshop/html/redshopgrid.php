<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Utility class for creating HTML Grids
 *
 * @package     RedSHOP.Library
 * @subpackage  HTML
 * @since       1.5
 */
abstract class JHtmlRedshopGrid
{
	/**
	 * Method to check all checkboxes in a grid
	 *
	 * @param   string  $name    The name of the form element
	 * @param   string  $tip     The text shown as tooltip title instead of $tip
	 * @param   string  $action  The action to perform on clicking the checkbox
	 *
	 * @return  string
	 *
	 * @since   3.1.2
	 */
	public static function checkall($name = 'checkall-toggle', $tip = 'JGLOBAL_CHECK_ALL', $action = 'Joomla.checkAll(this)')
	{
		if (version_compare(JVERSION, '3.0', '>='))
		{
			JHtml::_('bootstrap.tooltip');

			return '<input type="checkbox" name="' . $name . '" value="" class="hasTooltip"
				title="' . JHtml::tooltipText($tip) . '" onclick="' . $action . '" />';
		}
		else
		{
			return '<input type="checkbox" name="' . $name . '" value="" title="' . JText::_($tip) . '" onclick="' . $action . '" />';
		}
	}

	/**
	 * Method for render text with slide if length is longer than count.
	 *
	 * @param   string  $data   String data
	 * @param   int     $count  Count of maximum length
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function slideText($data = '', $count = 50)
	{
		if (empty($data))
		{
			return '';
		}

		if (strlen($data) <= $count)
		{
			return $data;
		}

		$document = JFactory::getDocument();
		$count    = (int) $count;
		$more     = empty($more) ? JText::_('COM_REDSHOP_MORE') : $more;
		$teaser   = JHtml::_('string.truncate', $data, $count, true, false);

		$document->addStyleDeclaration('
			.rs-full { display: none; }
			.rs-more { cursor: pointer; }
			.rs-teaser { display: inline-block; }
		');

		$document->addScriptDeclaration('
			(function($){
				$(document).ready(function(){
					$(".rs-more").click(function(e){
						var $self = $(this);
						var $teaser = $self.parent().find(".rs-teaser");
						var $full = $self.parent().find(".rs-full");

						$teaser.toggle();
						$full.toggle("slow", function(){
							if ($(this).css("display") == "none") {
								$self.text("' . JText::_('COM_REDSHOP_GRID_SLIDERTEXT_MORE') . '");
							} else {
								$self.text("' . JText::_('COM_REDSHOP_GRID_SLIDERTEXT_LESS') . '");
							}
						});
					});
				});
			})(jQuery);
		');

		return "<span class='rs-teaser'>" . $teaser . "</span>
			<span class='rs-full'>" . $data . "</span>
			<span class='rs-more badge label-success'>" . $more . "</span>";
	}
}
