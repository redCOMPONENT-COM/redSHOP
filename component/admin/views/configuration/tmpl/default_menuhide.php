<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$items = RedshopMenuLeft_Menu::render(true);

$menuhide = explode(",", $this->config->get('MENUHIDE'));

$items = array_chunk($items, 3);
?>

<?php if (isset($items)): ?>
    <div class="menu-hide">
		<?php foreach ($items as $key => $data): ?>
            <div class="row-fluid">
				<?php foreach ($data as $group => $sections): ?>
                    <div class="col-md-4">
						<?php if (is_object($sections)): ?>
                            <div class="panel panel-default">
								<?php $isHide = in_array($sections->title, $menuhide); ?>
                                <div class="panel-heading">
                                    <label class="lead no-margin <?php echo $isHide ? 'text-danger' : '' ?>">
                                        <input type="checkbox" value="<?php echo $sections->title ?>" name="menuhide[]"
											<?php echo $isHide ? 'checked' : '' ?> />
										<?php echo JText::_($sections->title) ?>
                                    </label>
                                </div>
                            </div>
						<?php else: ?>
							<?php foreach ($sections['items'] as $sectionKey => $section) : ?>
                                <div class="panel panel-default">
									<?php $isHide = in_array($section->title, $menuhide); ?>
                                    <div class="panel-heading">
                                        <label class="lead no-margin <?php echo $isHide ? 'text-danger' : '' ?>">
                                            <input type="checkbox" value="<?php echo $section->title ?>" name="menuhide[]"
												<?php echo $isHide ? 'checked' : '' ?> />
											<?php echo JText::_($section->title) ?>
                                            <span class="badge badge-info pull-right"><?php echo count($section->items) ?></span>
                                        </label>
                                    </div>
                                    <div class="panel-body item-list">
										<?php foreach ($section->items as $item) : ?>
											<?php $isHide = in_array($item->title, $menuhide); ?>
                                            <label <?php echo $isHide ? 'class="text-danger"' : '' ?>>
                                                <input type="checkbox" value="<?php echo $item->title ?>"
                                                       name="menuhide[]" <?php echo $isHide ? 'checked' : '' ?>>
												<?php echo JText::_($item->title) ?>
                                            </label>
										<?php endforeach; ?>
                                    </div>
                                </div>
							<?php endforeach; ?>
						<?php endif; ?>
                    </div>
				<?php endforeach; ?>
            </div>
		<?php endforeach; ?>
    </div>

    <script type="text/javascript">
        (function ($) {
            $(document).ready(function () {
                $('.menu-hide').find('input[type=checkbox]').click(function () {
                    var $self = $(this);
                    var check = $self.prop("checked");

                    var $childs = $(this).parent().parent().next();

                    if ($childs.hasClass('item-list')) {
                        var $childs = $childs.find('input[type=checkbox]');
                        $childs.prop('checked', this.checked);

                        if (check)
                            $childs.parent().addClass("text-danger");
                        else
                            $childs.parent().removeClass("text-danger");
                    }

                    if (check)
                        $(this).parent().addClass("text-danger");
                    else
                        $(this).parent().removeClass("text-danger");
                });
            });
        })(jQuery);
    </script>

<?php endif; ?>
