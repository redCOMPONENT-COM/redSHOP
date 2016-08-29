<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

extract($displayData);

$ratingImage = REDSHOP_ADMIN_IMAGES_ABSPATH . 'star_rating/' . $avgRating . '.gif';

?>

<img src="<?php echo $ratingImage ?>" />

<?php
echo JText::_('COM_REDSHOP_AVG_RATINGS_1') . " " . $countRating . " " . JText::_('COM_REDSHOP_AVG_RATINGS_2');
