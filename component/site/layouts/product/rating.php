<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

extract($displayData);
?>  <?php
	// Tweak by Ronni - Rating star img path + Specify dimentions ?>
    <img src="<?php echo REDSHOP_MEDIA_IMAGES_ABSPATH . 'star_rating_ny/' . $avgRating . '.gif' ?>" 
        alt="Rating stars" width="75px" height="15px"/>

<?php
echo JText::_('COM_REDSHOP_AVG_RATINGS_1') . " " . $countRating . " " . JText::_('COM_REDSHOP_AVG_RATINGS_2');
