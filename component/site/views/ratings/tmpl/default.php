<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
$redconfig = Redconfiguration::getInstance();
$redTemplate = Redtemplate::getInstance();
$model = $this->getModel('ratings');

$main_template = $redTemplate->getTemplate("review");

if (count($main_template) > 0 && $main_template[0]->template_desc)
{
	$main_template = $main_template[0]->template_desc;
}
else
{
	$main_template = "<div>{product_loop_start}<p><strong>{product_title}</strong></p><div>{review_loop_start}<div id=\"reviews_wrapper\"><div id=\"reviews_rightside\"><div id=\"reviews_fullname\">{fullname}</div><div id=\"reviews_title\">{title}</div><div id=\"reviews_comment\">{comment}</div></div><div id=\"reviews_leftside\"><div id=\"reviews_stars\">{stars}</div></div></div>{review_loop_end}<div>{product_loop_end}</div></div></div>	";
}

if ($this->params->get('show_page_heading', 1))
{
	if ($this->params->get('page_title'))
	{
	?>
		<h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
			<?php echo $this->escape($this->params->get('page_title')); ?>
		</h1>
	<?php
	}
}

if (Redshop::getConfig()->get('FAVOURED_REVIEWS') != "" || Redshop::getConfig()->get('FAVOURED_REVIEWS') != 0)
	$mainblock = Redshop::getConfig()->get('FAVOURED_REVIEWS');
else
	$mainblock = 5;

if (strstr($main_template, "{product_loop_start}") && strstr($main_template, "{product_loop_end}"))
{
	$product_start    = explode("{product_loop_start}", $main_template);
	$product_end      = explode("{product_loop_end}", $product_start[1]);
	$product_template = $product_end[0];

	$product_data = "";

	for ($i = 0; $i < count($this->detail); $i++)
	{
		$product_data .= $product_template;
		$product_data = str_replace("{product_title}", $this->detail[$i]->product_name, $product_data);

		if (strstr($product_data, "{review_loop_start}") && strstr($product_data, "{review_loop_end}"))
		{
			$review_start    = explode("{review_loop_start}", $product_data);
			$review_end      = explode("{review_loop_end}", $review_start[1]);
			$review_template = $review_end[0];

			$review_data = "";
			$reviews     = $model->getProductreviews($this->detail[$i]->product_id);

			if (count($reviews) > 0)
			{
				for ($j = 0; $j < $mainblock && $j < count($reviews); $j++)
				{
					$review_data .= $review_template;
					$fullname  = $reviews[$j]->firstname . " " . $reviews[$j]->lastname;
					$starimage = '<img src="' . REDSHOP_ADMIN_IMAGES_ABSPATH . 'star_rating/' . $reviews[$j]->user_rating . '.gif">';

					$review_data = str_replace("{fullname}", $fullname, $review_data);
					$review_data = str_replace("{title}", $reviews[$j]->title, $review_data);
					$review_data = str_replace("{comment}", $reviews[$j]->comment, $review_data);
					$review_data = str_replace("{reviewdate}", $redconfig->convertDateFormat($reviews[$j]->time), $review_data);
					$review_data = str_replace("{stars}", $starimage, $review_data);
				}

				if ($mainblock < count($reviews))
				{
					$review_data .= '<div style="clear:both;" class="show_reviews"><a href="javascript:showallreviews(' . $this->detail[$i]->product_id . ');"> <img src="' . REDSHOP_FRONT_IMAGES_ABSPATH . 'reviewarrow.gif">&nbsp;' . JText::_('COM_REDSHOP_SHOW_ALL_REVIEWS') . '</a></div>';
				}

				$review_data .= '<div style="display:none;" id="showreviews' . $this->detail[$i]->product_id . '" name="showreviews' . $this->detail[$i]->product_id . '">';

				for ($k = $mainblock; $k < count($reviews); $k++)
				{
					$review_data .= $review_template;
					$fullname2  = $reviews[$k]->firstname . " " . $reviews[$k]->lastname;
					$starimage2 = '<img src="' . REDSHOP_ADMIN_IMAGES_ABSPATH . 'star_rating/' . $reviews[$k]->user_rating . '.gif">';

					$review_data = str_replace("{fullname}", $fullname2, $review_data);
					$review_data = str_replace("{title}", $reviews[$k]->title, $review_data);
					$review_data = str_replace("{comment}", $reviews[$k]->comment, $review_data);
					$review_data = str_replace("{reviewdate}", $redconfig->convertDateFormat($reviews[$k]->time), $review_data);
					$review_data = str_replace("{stars}", $starimage2, $review_data);
				}

				$review_data .= '</div>';
			}

			$product_data = $review_start[0] . $review_data . $review_end[1];
		}
	}

	$main_template = $product_start[0] . $product_data . $product_end[1];
}

if (strstr($main_template, "{pagination}"))
{
	$main_template = str_replace("{pagination}", "", $main_template);
}

echo eval("?>" . $main_template . "<?php ");
