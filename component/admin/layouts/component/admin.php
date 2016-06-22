<?php
/**
 * @package     RedSHOP
 * @subpackage  Layouts
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;


extract($displayData);

$data = $displayData;

$content = $view->loadTemplate($tpl);
$displayData['content'] = $content;

$app = JFactory::getApplication();
$input = $app->input;

/**
 * Handle raw format
 */
$format = $input->getString('format');

if ('raw' === $format)
{
	/** @var RView $view */
	$view = $data['view'];

	if (!$view instanceof RedshopViewAdmin)
	{
		throw new InvalidArgumentException(
			sprintf(
				'Invalid view %s specified for the component layout',
				get_class($view)
			)
		);
	}

	$toolbar = $view->getToolbar();

	// Get the view render.
	return $content;
}


$templateComponent = 'component' === $input->get('tmpl');
$input->set('tmpl', 'component');

// The view to render.
if (!isset($data['view']))
{
	throw new InvalidArgumentException('No view specified in the component layout.');
}

/** @var RView $view */
$view = $data['view'];

if (!$view instanceof RedshopViewAdmin)
{
	throw new InvalidArgumentException(
		sprintf(
			'Invalid view %s specified for the component layout',
			get_class($view)
		)
	);
}

if ($content instanceof Exception)
{
	return $content;
}
?>
<script type="text/javascript">
	jQuery(document).ready(function ($) {


	});
</script>
<?php if ($view->getLayout() === 'modal') : ?>
	<div class="row-fluid RedSHOP">
		<section id="component">
			<div class="row-fluid message-sys"  id="message-sys"></div>
			<div class="row-fluid">
				<?php echo $content ?>
			</div>
		</section>
	</div>
<?php elseif ($templateComponent) : ?>
	<div class="container-fluid RedSHOP">
		<div class="span12 content">
			<section id="component">
				<div class="row-fluid">
					<h1><?php echo $view->getTitle() ?></h1>
				</div>
				<div class="row-fluid message-sys" id="message-sys"></div>
				<hr/>
				<div class="row-fluid">
					<?php echo $content ?>
				</div>
			</section>
		</div>
	</div>
<?php
else : ?>
	<?php echo JLayoutHelper::render('component.full', $displayData); ?>
<?php endif;
