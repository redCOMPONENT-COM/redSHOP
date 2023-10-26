<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

extract($displayData);

?>
<img src="<?php echo REDSHOP_MEDIA_IMAGES_ABSPATH . 'star_rating/' . $avgRating . '.gif' ?>" />

<?php
echo Text::_('COM_REDSHOP_AVG_RATINGS_1') . " " . $countRating . " " . Text::_('COM_REDSHOP_AVG_RATINGS_2');
