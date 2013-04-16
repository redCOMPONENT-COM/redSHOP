<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */


$image_padding = $sliderheight / 3;
?>
<style type="text/css">
	.jcarousel-skin-tango .jcarousel-container {
		-moz-border-radius: 10px;
		-webkit-border-radius: 10px;
		border-radius: 10px;
	}

	.jcarousel-skin-tango .jcarousel-direction-rtl {
		direction: rtl;
	}

	.jcarousel-skin-tango .jcarousel-container-horizontal {
		width: <?php echo $sliderwidth ?>px;
		height: <?php echo $sliderheight ?>px;

	}

	.jcarousel-skin-tango .jcarousel-container-vertical {
		width: 75px;
		height: 245px;
		padding: 40px 20px;
	}

	.jcarousel-skin-tango .jcarousel-clip-horizontal {
		overflow: hidden;
	}

	.jcarousel-skin-tango .jcarousel-clip-vertical {
		width: 75px;
		height: 245px;
	}

	.jcarousel-skin-tango .jcarousel-item {
		width: 200px;
		height: <?php echo $sliderheight ?>px;
	}

	.jcarousel-skin-tango .jcarousel-item-horizontal {
		margin-left: 0;
		margin-right: 8px;
	}

	.jcarousel-skin-tango .jcarousel-direction-rtl .jcarousel-item-horizontal {
		margin-left: 10px;
		margin-right: 0;
	}

	.jcarousel-skin-tango .jcarousel-item-vertical {
		margin-bottom: 10px;
	}

	.jcarousel-skin-tango .jcarousel-item-placeholder {
		background: #fff;
		color: #000;
	}

		/**
		 *  Horizontal Buttons
		 */
	.jcarousel-skin-tango .jcarousel-next-horizontal {

		position: absolute;
		top: <?php echo $image_padding ?>px;
		right: -30px;
		width: 32px;
		height: 32px;
		cursor: pointer;
		background: url(<?php echo JURI::base();?>modules/mod_redshop_who_bought/assets/images/next-horizontal.png) no-repeat 0 0;
	}

	.jcarousel-skin-tango .jcarousel-direction-rtl .jcarousel-next-horizontal {
		left: 5px;
		right: auto;
		background-image: url(<?php echo JURI::base();?>modules/mod_redshop_who_bought/assets/images/prev-horizontal.png);
	}

	.jcarousel-skin-tango .jcarousel-next-horizontal:hover {
		background-position: -32px 0;
	}

	.jcarousel-skin-tango .jcarousel-next-horizontal:active {
		background-position: -64px 0;
	}

	.jcarousel-skin-tango .jcarousel-next-disabled-horizontal,
	.jcarousel-skin-tango .jcarousel-next-disabled-horizontal:hover,
	.jcarousel-skin-tango .jcarousel-next-disabled-horizontal:active {
		cursor: default;
		background-position: -96px 0;
	}

	.jcarousel-skin-tango .jcarousel-prev-horizontal {
		position: absolute;
		top: <?php echo $image_padding ?>px;
		left: -30px;
		width: 32px;
		height: 32px;
		cursor: pointer;
		background: transparent url(<?php echo JURI::base();?>modules/mod_redshop_who_bought/assets/images/prev-horizontal.png) no-repeat 0 0;
	}

	.jcarousel-skin-tango .jcarousel-direction-rtl .jcarousel-prev-horizontal {
		left: auto;
		right: 5px;
		background-image: url(<?php echo JURI::base();?>modules/mod_redshop_who_bought/assets/images/next-horizontal.png);
	}

	.jcarousel-skin-tango .jcarousel-prev-horizontal:hover {
		background-position: -32px 0;
	}

	.jcarousel-skin-tango .jcarousel-prev-horizontal:active {
		background-position: -64px 0;
	}

	.jcarousel-skin-tango .jcarousel-prev-disabled-horizontal,
	.jcarousel-skin-tango .jcarousel-prev-disabled-horizontal:hover,
	.jcarousel-skin-tango .jcarousel-prev-disabled-horizontal:active {
		cursor: default;
		background-position: -96px 0;
	}

		/**
		 *  Vertical Buttons
		 */
	.jcarousel-skin-tango .jcarousel-next-vertical {
		position: absolute;
		bottom: 5px;
		left: 43px;
		width: 32px;
		height: 32px;
		cursor: pointer;
		background: transparent url(<?php echo JURI::base();?>modules/mod_redshop_who_bought/assets/images/next-vertical.png) no-repeat 0 0;
	}

	.jcarousel-skin-tango .jcarousel-next-vertical:hover {
		background-position: 0 -32px;
	}

	.jcarousel-skin-tango .jcarousel-next-vertical:active {
		background-position: 0 -64px;
	}

	.jcarousel-skin-tango .jcarousel-next-disabled-vertical,
	.jcarousel-skin-tango .jcarousel-next-disabled-vertical:hover,
	.jcarousel-skin-tango .jcarousel-next-disabled-vertical:active {
		cursor: default;
		background-position: 0 -96px;
	}

	.jcarousel-skin-tango .jcarousel-prev-vertical {
		position: absolute;
		top: 5px;
		left: 43px;
		width: 32px;
		height: 32px;
		cursor: pointer;
		background: transparent url(<?php echo JURI::base();?>modules/mod_redshop_who_bought/assets/images/prev-vertical.png) no-repeat 0 0;
	}

	.jcarousel-skin-tango .jcarousel-prev-vertical:hover {
		background-position: 0 -32px;
	}

	.jcarousel-skin-tango .jcarousel-prev-vertical:active {
		background-position: 0 -64px;
	}

	.jcarousel-skin-tango .jcarousel-prev-disabled-vertical,
	.jcarousel-skin-tango .jcarousel-prev-disabled-vertical:hover,
	.jcarousel-skin-tango .jcarousel-prev-disabled-vertical:active {
		cursor: default;
		background-position: 0 -96px;
	}

</style>