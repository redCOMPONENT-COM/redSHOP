<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Joomla! System Red Product Zoom Plugin
 *
 * @package     RedSHOP.Plugin
 * @subpackage  System.redproductzoom
 * @since       2.0.0
 */
class PlgSystemRedProductZoom extends JPlugin
{
	/**
	 * This event is triggered immediately before pushing the document buffers into the template placeholders,
	 * retrieving data from the document and pushing it into the into the JResponse buffer.
	 * http://docs.joomla.org/Plugin/Events/System
	 *
	 * @return void
	 */
	public function onBeforeRender()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;

		// No redSHOP Product Zoom me for admin
		if ($app->isAdmin() || $input->get('option') != 'com_redshop' || $input->get('view') != 'product' || !$input->getInt('pid', 0))
		{
			return;
		}

		$document = JFactory::getDocument();
		$url      = JUri::base(true) . '/plugins/system/redproductzoom';

		$document->addScript($url . '/js/jquery.elevatezoom.js');
		$document->addScript($url . '/js/redproductzoom.js');

		$scripts = array();
		$scripts[] = 'loadingIcon: "plugins/system/redproductzoom/js/zoomloader.gif"';
		$scripts[] = 'cursor: "crosshair"';
		$scripts[] = 'zoomType: "' . $this->params->get('zoom_type', 'window') . '"';
		$scripts[] = 'scrollZoom: ' . ($this->params->get('scroll_zoom', true) ? 'true' : 'false');

		$zoomType = $this->params->get('zoom_type', 'window');

		if ($zoomType == 'lens')
		{
			$scripts[] = 'lensShape: "' . $this->params->get('lens_shape', 'round') . '"';
			$scripts[] = 'lensSize: ' . $this->params->get('lens_size', 200);
			$scripts[] = 'lensFadeIn: ' . ($this->params->get('lens_fade_in', true) ? 'true' : 'false');
			$scripts[] = 'lensFadeOut: ' . ($this->params->get('lens_fade_out', true) ? 'true' : 'false');
		}
		elseif ($zoomType == 'window')
		{
			$scripts[] = 'tint: ' . ($this->params->get('tint', false) ? 'true' : 'false');
			$scripts[] = 'tintColour: "' . $this->params->get('tint_color') . '"';
			$scripts[] = 'tintOpacity: ' . $this->params->get('tint_opacity');
			$scripts[] = 'zoomWindowWidth: ' . $this->params->get('zoom_window_width');
			$scripts[] = 'zoomWindowHeight: ' . $this->params->get('zoom_window_height');
		}

		$document->addScriptDeclaration(
			'(function($){
				$(document).ready(function(){
					redproductzoom({' . implode(',', $scripts) . '});
				});
			})(jQuery);'
		);
	}
}
