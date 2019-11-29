<?php
/**
 * @package     Redshop.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_BASE') or die;

/**
 * $displayData extract
 *
 * @var   array    $displayData          Display data
 * @var   integer  $productId            Product Id
 */
extract($displayData);
$statistics = RedshopHelperProduct::statisticsRatingProduct($productId);
$star = array();
$totalRating  = 0;
$avg          = 0;

foreach ($statistics as $statistic)
{
	$star[$statistic->user_rating] = $statistic->percent;
	$totalRating                  += $statistic->count;
	$avg                          += $statistic->user_rating * $statistic->count;
}

$avg          = (is_numeric(number_format($avg  / $totalRating, 1))) ? number_format($avg  / $totalRating, 1) : 0;
$totalPercent = ($avg * 100) / 5;
?>
<div class="rating-statistics">
	<table>
		<tr>
			<td width="35%">
				<div class="total-rating"><?php echo $avg ?>/5</div>
				<div class="total-rating-image"><img src="media/com_redshop/images/star_rating/<?php echo round($avg) ?>.gif"></div>
				<div class="total-comments"><?php echo $totalRating ?> nhận xét</div>
			</td>
			<td>
				<ul>
					<?php
					for ($i = 5; $i > 0; $i--) : ?>
						<?php $star[$i] = isset($star[$i]) ? $star[$i] : 0 ?>
						<li>
							<div>
								<img src="media/com_redshop/images/star_rating/<?php echo $i ?>.gif">
							</div>
							<div>
								<div class="bar-wrap">
									<div class="bar" style="width: <?php echo $star[$i] ?>%"></div>
								</div>
							</div>
							<div><?php echo $star[$i] ?>%</div>
						</li>
					<?php endfor; ?>
				</ul>
			</td>
		</tr>
	</table>
</div>
