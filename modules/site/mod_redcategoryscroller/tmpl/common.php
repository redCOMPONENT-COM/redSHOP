<?php
/**
 * @package     RedSHOP.Module
 * @subpackage  mod_redfeaturedproduct
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

if (($this->ScrollDirection == 'left') || ($this->ScrollDirection == 'right'))
{
	echo '<table><tr>';
}

$i = 0;

foreach ($rows as $row)
{
	if (($this->ScrollDirection == 'left') || ($this->ScrollDirection == 'right'))
	{
		echo '<td style="vertical-align:top;padding: 2px 5px 2px 5px;"><table width="' . $this->boxwidth . '">';
	}

	// Display Product
	$categorydata = $this->ShowCategory($row, $i);
	echo $categorydata;

	if (($this->ScrollDirection == 'left') || ($this->ScrollDirection == 'right'))
	{
		echo '</table></td>';
	}
	else
	{
		for ($i = 0; $i < $this->ScrollLineCharTimes; $i++)
		{
			echo $this->ScrollLineChar;
		}
	}

	$i++;
}

if (($this->ScrollDirection == 'left') || ($this->ScrollDirection == 'right'))
{
	echo '</tr></table>';
}
