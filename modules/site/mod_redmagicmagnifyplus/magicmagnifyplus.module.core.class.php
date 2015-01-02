<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_magicmagnifyplus
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

if (!in_array('MagicMagnifyPlusModuleCoreClass', get_declared_classes()))
{
	require_once dirname(__FILE__) . '/magictoolbox.params.class.php';

	class MagicMagnifyPlusModuleCoreClass
	{
		var $uri;
		var $jsPath;
		var $cssPath;
		var $imgPath;
		var $params;

		function MagicMagnifyPlusModuleCoreClass()
		{
			$this->params = new MagicToolboxParams;
			$this->_paramDefaults();
		}

		function headers($jsPath = '', $cssPath = null, $notCheck = false)
		{
			if ($cssPath == null) $cssPath = $jsPath;
			$headers   = array();
			$headers[] = '<!-- Magic Magnify Plus Joomla 1.5 module module version 2.11.2.0 -->';
			$headers[] = '<link type="text/css" href="' . $cssPath . '/magicthumb.css" rel="stylesheet" media="screen" />';
			$headers[] = '<script type="text/javascript" src="' . $jsPath . '/magicmagnifyplus.js"></script>';

			$conf = Array(
				"captionSlideDuration: " . $this->params->getValue("caption_slide_duration"),
				"zoomDuration: " . $this->params->getValue("zoom_duration"),
				"restoreDuration: " . $this->params->getValue("restore_duration"),
				"zoomPosition: '" . $this->params->getValue("position") . "'",
				//"zoomTrigger: '" . $this->params->getValue("trigger") . "'",
				//"zoomTriggerDelay: " . $this->params->getValue("trigger_delay"),
				"backgroundFadingOpacity: " . $this->params->getValue("background_fading_opacity"),
				"backgroundFadingColor: '" . $this->params->getValue("background_fading_color") . "'",
				"backgroundFadingDuration: " . $this->params->getValue("background_fading_duration"),
				"controlbarPosition: '" . $this->params->getValue("controlbar_position") . "'",
				"zIndex: " . $this->params->getValue("zindex"),
			);

			if ($notCheck)
			{
				$conf = array_merge($conf, array(
					"keepThumbnail: " . $this->params->getValue("keep_thumbnail"),
					"allowKeyboard: " . $this->params->getValue("allow_keyboard"),
					"useCtrlKey: " . $this->params->getValue("use_ctrl_key"),
					"controlbarEnable: " . $this->params->getValue("controlbar_enable"),
					"allowMultipleImages: " . $this->params->getValue("allow_multiple_images"),
					"fitToScreen: " . $this->params->getValue("fit_to_screen"),
				));
			}
			else
			{
				$conf = array_merge($conf, array(
					"keepThumbnail: " . ($this->params->getValue("keep_thumbnail") == 'Yes' ? 'true' : 'false'),
					"allowKeyboard: " . ($this->params->getValue("allow_keyboard") == 'Yes' ? 'true' : 'false'),
					"useCtrlKey: " . ($this->params->getValue("use_ctrl_key") == 'Yes' ? 'true' : 'false'),
					"controlbarEnable: " . ($this->params->getValue("controlbar_enable") == 'Yes' ? 'true' : 'false'),
					"allowMultipleImages: " . ($this->params->getValue("allow_multiple_images") == 'Yes' ? 'true' : 'false'),
					"fitToScreen: " . ($this->params->getValue("fit_to_screen") == 'Yes' ? 'true' : 'false'),
				));
			}


			$headers[] = "<script type=\"text/javascript\">\n\tMagicThumb.options = {\n\t\t" . implode(",\n\t\t", $conf) . "\n\t}\n</script>\n";

			$headers[] = '<script type="text/javascript">
                                            function MagicThumb_selectorClick(el) {
                                                el.blur();
                                                var a = document.getElementById(el.getAttribute(\'rel\'));
                                                a.href = el.href;
                                                a.setAttribute(\'title\', el.getAttribute(\'title\'));
                                                a.firstChild.src = el.getAttribute(\'rev\');
                                                MagicThumb.refresh();
                                            }
                                    </script>';

			return implode("\r\n", $headers);
		}

		/*function template($img, $thumb, $id, $title, $aStyles = '') {
			if(!empty($title)) $title = " title=\"{$title}\"";
			if($this->params->getValue('show_caption') != 'Yes') $title = "";
			$aStyles = trim($aStyles);
			if(!empty($aStyles)) $aStyles .= ' ';
			return '<a ' . $aStyles . 'class="MagicMagnify"' . $title . ' id="MagicMagnifyImage' . $id . '" href="' . $img . '" rel="' . $this->getRel() . '"><img src="' .  $thumb . '" /></a>' . $this->params->getValue('message');
		}*/
		function template($params)
		{
			extract($params);

			if (!isset($img) || empty($img)) return false;
			if (!isset($thumb) || empty($thumb)) $thumb = $img;
			if (!isset($id) || empty($id)) $id = md5($img);

			if (!isset($alt) || empty($alt)) $alt = '';
			if (!isset($title) || empty($title)) $title = '';
			if (!isset($description)) $description = '';
			if ($this->params->checkValue('show_caption', 'Yes'))
			{
				if ($this->params->checkValue('caption_source', array('Title', 'Both')) && !empty($title)) $description_new = "<b>{$title}</b><br />";
				else $description_new = '';
				if ($this->params->checkValue('caption_source', array('Description', 'Both')) && !empty($description)) $description_new .= $description;
			}
			else $description_new = '';
			$description = $description_new;
			$description = trim(preg_replace("/\s+/is", " ", $description));
			if (!empty($description))
			{
				$description = preg_replace("/<(\/?)a([^>]*)>/is", "[$1a$2]", $description);
				$description = "<span>{$description}</span>";
			}
			if (!empty($title))
			{
				$title = htmlspecialchars($title);
				if (empty($alt)) $alt = $title;
				$title = " title=\"{$title}\"";
			}
			else $title = '';

			if (!isset($width) || empty($width)) $width = "";
			else $width = " width=\"{$width}\"";
			if (!isset($height) || empty($height)) $height = "";
			else $height = " height=\"{$height}\"";
			if ($this->params->checkValue('show_message', 'Yes'))
			{
				$message = $this->params->getValue('message');
			}
			else $message = '';

			return "<a class=\"MagicMagnifyPlus\"{$title} id=\"MagicMagnifyPlusImage{$id}\" href=\"{$img}\" rel=\"" . $this->getRel() . "\"><img{$width}{$height} src=\"{$thumb}\" alt=\"{$alt}\" />{$description}</a>" . $message;
		}

		function subTemplate($params)
		{
			extract($params);

			if (!isset($alt) || empty($alt)) $alt = '';
			if (!isset($img) || empty($img)) return false;
			if (!isset($medium) || empty($medium)) $medium = $img;
			if (!isset($thumb) || empty($thumb)) $thumb = $img;
			if (!isset($id) || empty($id)) $id = md5($img);
			if (!isset($title) || empty($title) || $this->params->checkValue('show_caption', 'No')) $title = '';
			else
			{
				$title = htmlspecialchars($title);
				if (empty($alt)) $alt = $title;
				$title = " title=\"{$title}\"";
			}
			if (!isset($width) || empty($width)) $width = "";
			else $width = " width=\"{$width}\"";
			if (!isset($height) || empty($height)) $height = "";
			else $height = " height=\"{$height}\"";

			return "<a {$title} rel=\"MagicMagnifyPlusImage{$id}\" href=\"{$img}\" rev=\"{$medium}\"><img{$width}{$height} src=\"{$thumb}\" alt=\"{$alt}\" /></a>";
		}

		function getRel($notCheck = false)
		{
			$rel = $this->params->getValue('rel');
			if (!$rel || empty($rel))
			{
				$rel   = Array();
				$rel[] = "zoom-color: " . $this->params->getValue('zoom-color');
				$rel[] = "border-color: " . $this->params->getValue('border-color');
				$rel[] = "size: " . $this->params->getValue('size');
				if (!$this->params->checkValue('sizeX', 0) || $notCheck) $rel[] = "sizeX: " . $this->params->getValue('sizeX');
				if (!$this->params->checkValue('sizeY', 0) || $notCheck) $rel[] = "sizeY: " . $this->params->getValue('sizeY');
				$rel[] = "type: " . $this->params->getValue('type');
				$rel[] = "line-thickness: " . $this->params->getValue('line-thickness');
				$rel[] = "line-thickness-border: " . $this->params->getValue('line-thickness-border');
				if (!$this->params->checkValue('lenseUrl', '') || $notCheck)
				{
					$rel[] = "lenseUrl: " . $this->params->getValue('lenseUrl');
					$rel[] = "lenseOffsetX: " . $this->params->getValue('lenseOffsetX');
					$rel[] = "lenseOffsetY: " . $this->params->getValue('lenseOffsetY');
				}
				$rel[] = "lensePotition: " . $this->params->getValue('lensePotition');
				if (!$this->params->checkValue('linkUrl', '') || $notCheck)
				{
					$rel[] = "linkUrl: " . $this->params->getValue('linkUrl');
				}
				$rel[] = "linkWindow: " . $this->params->getValue('linkWindow');
				if ($notCheck)
				{
					$rel[] = "disableAutoStart: " . $this->params->getValue('disableAutoStart', 'Yes');
					$rel[] = "pauseOnClick: " . $this->params->getValue('pauseOnClick', 'Yes');
					$rel[] = "thumb: " . $this->params->getValue('thumb', 'Yes');
				}
				else
				{
					$rel[] = "disableAutoStart: " . ($this->params->checkValue('disableAutoStart', 'Yes') ? 'true' : 'false');
					$rel[] = "pauseOnClick: " . ($this->params->checkValue('pauseOnClick', 'Yes') ? 'true' : 'false');
					$rel[] = "thumb: " . ($this->params->checkValue('thumb', 'Yes') ? 'true' : 'false');
				}

				if ($this->params->checkValue('containerDisplay', 'inline-block'))
				{
					$rel[] = "containerDisplay: inline-block";
				}

				$rel = implode(';', $rel);
				$this->params->append('rel', $rel);
			}

			return $rel;
		}

		function addonsTemplate($imgPath = '')
		{
			return '';
		}

		function _paramDefaults()
		{
			$params = array(

				"zoom-color"                 => array(
					"id"      => "zoom-color",
					"default" => "6E8C0E",
					"label"   => "Magnifier color (RGB)",
					"type"    => "text",

				),

				"border-color"               => array(
					"id"      => "border-color",
					"default" => "6E8C0E",
					"label"   => "Border color (RGB)",
					"type"    => "text",

				),

				"size"                       => array(
					"id"          => "size",
					"default"     => "100",
					"label"       => "Magnifier size (in pixels)",
					"type"        => "num",

					"description" => "Both width and height if sizeX/sizeY are not specified",

				),

				"sizeX"                      => array(
					"id"          => "sizeX",
					"default"     => "0",
					"label"       => "Magnifier width (in pixels)",
					"type"        => "num",

					"description" => "0 - to disable this option",

				),

				"sizeY"                      => array(
					"id"          => "sizeY",
					"default"     => "0",
					"label"       => "Magnifier height (in pixels)",
					"type"        => "num",

					"description" => "0 - to disable this option",

				),

				"type"                       => array(
					"id"      => "type",
					"default" => "circle",
					"label"   => "Magnifier shape",
					"type"    => "array",

					"subType" => "select",
					"values"  => array("circle", "square",),

				),

				"line-thickness"             => array(
					"id"      => "line-thickness",
					"default" => "1",
					"label"   => "Magnifier line thickness (in pixels)",
					"type"    => "num",

				),

				"line-thickness-border"      => array(
					"id"      => "line-thickness-border",
					"default" => "1",
					"label"   => "Border line thickness (in pixels)",
					"type"    => "num",

				),

				"lenseUrl"                   => array(
					"id"          => "lenseUrl",
					"default"     => "",
					"label"       => "Relative/Absolute url of the custom image",
					"type"        => "text",

					"description" => "Leave empty to disable this option",

				),

				"lenseOffsetX"               => array(
					"id"      => "lenseOffsetX",
					"default" => "0",
					"label"   => "Custom image x-offset",
					"type"    => "num",

				),

				"lenseOffsetY"               => array(
					"id"      => "lenseOffsetY",
					"default" => "0",
					"label"   => "Custom image y-offset",
					"type"    => "num",

				),

				"lensePotition"              => array(
					"id"          => "lensePotition",
					"default"     => "top",
					"label"       => "Custom image position",
					"type"        => "array",

					"subType"     => "select",
					"values"      => array("top", "bottom",),

					"description" => "top - above magnifier, bottom - under magnifier",

				),

				"linkUrl"                    => array(
					"id"          => "linkUrl",
					"default"     => "",
					"label"       => "Custom link URL",
					"type"        => "text",

					"description" => "Leave empty to disable this option",

				),

				"linkWindow"                 => array(
					"id"      => "linkWindow",
					"default" => "_self",
					"label"   => "Window or HTML frame into which the document should load",
					"type"    => "array",

					"subType" => "select",
					"values"  => array("_self", "_blank", "_parent", "_top",),

				),

				"disableAutoStart"           => array(
					"id"      => "disableAutoStart",
					"default" => "No",
					"label"   => "Disables magnify effect autostart",
					"type"    => "array",

					"subType" => "radio",
					"values"  => array("Yes", "No",),

				),

				"pauseOnClick"               => array(
					"id"      => "pauseOnClick",
					"default" => "No",
					"label"   => "Pause on click",
					"type"    => "array",

					"subType" => "radio",
					"values"  => array("Yes", "No",),

				),

				"thumb"                      => array(
					"id"      => "thumb",
					"default" => "Yes",
					"label"   => "Enable thumb effect",
					"type"    => "array",

					"subType" => "radio",
					"values"  => array("Yes", "No",),

				),

				"show_message"               => array(
					"id"      => "show_message",
					"default" => "Yes",
					"label"   => "Show message under image?",
					"type"    => "array",

					"subType" => "radio",
					"values"  => array("Yes", "No",),

				),

				"message"                    => array(
					"id"      => "message",
					"default" => "Move your mouse over image or click to enlarge",
					"label"   => "Message under images",
					"type"    => "text",

				),

				"show_caption"               => array(
					"id"      => "show_caption",
					"default" => "Yes",
					"label"   => "Show caption?",
					"type"    => "array",

					"subType" => "radio",
					"values"  => array("Yes", "No",),

				),

				"caption_source"             => array(
					"id"      => "caption_source",
					"default" => "Description",
					"label"   => "Caption source",
					"type"    => "array",

					"subType" => "select",
					"values"  => array("Title", "Description", "Both",),

				),

				"caption_slide_duration"     => array(
					"id"      => "caption_slide_duration",
					"default" => "0.5",
					"label"   => "Caption slide duration (in seconds)",
					"type"    => "float",

				),

				"zoom_duration"              => array(
					"id"      => "zoom_duration",
					"default" => "0.5",
					"label"   => "Zoom duration (in seconds)",
					"type"    => "float",

				),

				"restore_duration"           => array(
					"id"      => "restore_duration",
					"default" => "0.3",
					"label"   => "Image restore duration (in seconds)",
					"type"    => "float",

				),

				"position"                   => array(
					"id"      => "position",
					"default" => "center",
					"label"   => "Zoomed image position",
					"type"    => "array",

					"subType" => "select",
					"values"  => array("center", "auto",),

				),

				"keep_thumbnail"             => array(
					"id"      => "keep_thumbnail",
					"default" => "Yes",
					"label"   => "Thumbnail visibility?",
					"type"    => "array",

					"subType" => "radio",
					"values"  => array("Yes", "No",),

				),

				"allow_keyboard"             => array(
					"id"      => "allow_keyboard",
					"default" => "Yes",
					"label"   => "Allow keyboard navigation?",
					"type"    => "array",

					"subType" => "radio",
					"values"  => array("Yes", "No",),

				),

				"use_ctrl_key"               => array(
					"id"      => "use_ctrl_key",
					"default" => "No",
					"label"   => "Require to use Ctrl key with keyboard shortcuts to navigate between images?",
					"type"    => "array",

					"subType" => "radio",
					"values"  => array("Yes", "No",),

				),

				"background_fading_opacity"  => array(
					"id"      => "background_fading_opacity",
					"default" => "0",
					"label"   => "Fading background opacity (1-100)",
					"type"    => "num",

				),

				"background_fading_color"    => array(
					"id"      => "background_fading_color",
					"default" => "#000000",
					"label"   => "Fading background color (RGB)",
					"type"    => "text",

				),

				"background_fading_duration" => array(
					"id"      => "background_fading_duration",
					"default" => "0.2",
					"label"   => "Fading background duration",
					"type"    => "float",

				),

				"controlbar_enable"          => array(
					"id"      => "controlbar_enable",
					"default" => "Yes",
					"label"   => "Control bar enable?",
					"type"    => "array",

					"subType" => "radio",
					"values"  => array("Yes", "No",),

				),

				"controlbar_position"        => array(
					"id"      => "controlbar_position",
					"default" => "top right",
					"label"   => "Control bar position",
					"type"    => "array",

					"subType" => "select",
					"values"  => array("top right", "bottom right", "bottom left", "top left",),

				),

				"zindex"                     => array(
					"id"      => "zindex",
					"default" => "10001",
					"label"   => "Starting z-index",
					"type"    => "num",

				),

			);
			$this->params->appendArray($params);
		}
	}

}
