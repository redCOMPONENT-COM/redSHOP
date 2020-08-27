<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/**
 * Layout variables
 * ======================================
 *
 * @var  object $userRating
 * @var  array  $displayData
 */
extract($displayData);
?>
<div class="form-group row-fluid ">
	<label class="col-md-2 control-label ">
        <?php
        echo JText::_('COM_REDSHOP_RATING'); ?>:
	</label>
	<div class="col-md-10">
		<table cellpadding="3" cellspacing="3" align="left">
			<tr>
				<td align="center">
					<img src="<?php
                    echo REDSHOP_MEDIA_IMAGES_ABSPATH; ?>star_rating/5.gif" border="0"
					     align="absmiddle"><br/>
					<input type="radio" name="jform[user_rating]" id="jform_user_rating5"
					       value="5" <?php
                    if ($userRating == 5) {
                        echo "checked='checked'";
                    } ?>>
				</td>
				<td align="center">
					<img src="<?php
                    echo REDSHOP_MEDIA_IMAGES_ABSPATH; ?>star_rating/4.gif" border="0"
					     align="absmiddle"><br/>
					<input type="radio" name="jform[user_rating]" id="jform_user_rating4"
					       value="4" <?php
                    if ($userRating == 4) {
                        echo "checked='checked'";
                    } ?>>
				</td>
				<td align="center">
					<img src="<?php
                    echo REDSHOP_MEDIA_IMAGES_ABSPATH; ?>/star_rating/3.gif" border="0"
					     align="absmiddle"><br/>
					<input type="radio" name="jform[user_rating]" id="jform_user_rating3"
					       value="3" <?php
                    if ($userRating == 3) {
                        echo "checked='checked'";
                    } ?>>
				</td>
				<td align="center">
					<img src="<?php
                    echo REDSHOP_MEDIA_IMAGES_ABSPATH; ?>star_rating/2.gif" border="0"
					     align="absmiddle"><br/>
					<input type="radio" name="jform[user_rating]" id="jform_user_rating2"
					       value="2" <?php
                    if ($userRating == 2) {
                        echo "checked='checked'";
                    } ?>>
				</td>
				<td align="center">
					<img src="<?php
                    echo REDSHOP_MEDIA_IMAGES_ABSPATH; ?>star_rating/1.gif" border="0"
					     align="absmiddle"><br/>
					<input type="radio" name="jform[user_rating]" id="jform_user_rating1"
					       value="1" <?php
                    if ($userRating == 1) {
                        echo "checked='checked'";
                    } ?>>
				</td>
				<td align="center">
					<img src="<?php
                    echo REDSHOP_MEDIA_IMAGES_ABSPATH; ?>star_rating/0.gif" border="0"
					     align="absmiddle"><br/>
					<input type="radio" name="jform[user_rating]" id="jform_user_rating0"
					       value="0" <?php
                    if ($userRating == 0) {
                        echo "checked='checked'";
                    } ?>>
				</td>
			</tr>
		</table>
	</div>
</div>