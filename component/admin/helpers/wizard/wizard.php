<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Wizard
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

class redSHOPWizard
{

	function initialize(){

		$step 		= JRequest::getVar('step', '');

		$helper = new redSHOPWizardHelper;
		$template = new redSHOPWizardTemplate;

		if(!empty($step))
		{
			$progress 	= $helper->install($step);
			$html 		= $progress->message;
			$status		= $progress->status;
			$nextstep 	= $progress->step;
			$title 		= $progress->title;
			$install 	= $progress->install;
			$substep	= isset($progress->substep) ? $progress->substep : 0 ;
		}
		else
		{
			$nextstep = 1;

			$html		= $template->getHTML('welcome');

			$status 	= true;
			$title 		= JText::_('COM_REDSHOP_REDSHOP_CONFIGURATION_WIZARD');
			$install 	= 1;
			$substep	= 0;
		}

		$template->setHTML($html, $nextstep, $title, $status, $install, $substep);
	}
}


class redSHOPWizardHelper
{

	function install($step=1)
	{
		switch($step)
		{
			case 1:
				//check requirement
				$status = $this->general(2);
				break;
			case 2:
				//install backend system
				$status = $this->terms(3);
				break;
			case 3:
				//install backend system
				$status = $this->user(4);
				break;
			case 4:
				//install ajax system
				$status = $this->price(5);
				break;
			case 5:
				//show success message
				$status = $this->finish(0);
				break;
			default:
				$status 			= new stdClass;
				$status->message	= $this->getErrorMessage(0, '0a');
				$status->step 		= '-99';
				$status->title 		= JText::_('COM_REDSHOP_REDSHOP_CONFIGURATION_WIZARD');
				$status->install 	= 1;
				break;
		}
		return $status;
	}

	function general( $step )
	{
		$template = new redSHOPWizardTemplate;

		$status				= true;

		$params = new stdClass;
		$params->step = $step;

		$drawdata 			= new stdClass;
		$drawdata->message	= $template->getHTML('general',$params);
		$drawdata->status 	= $status;
		$drawdata->step 	= $step;
		$drawdata->title 	= JText::_('COM_REDSHOP_REDSHOP_GENERAL_CONFIGURATION_WIZARD');
		$drawdata->install 	= 1;

		return $drawdata;
	}

	function terms($step){

		$template = new redSHOPWizardTemplate;

		$status				= true;
		$this->pageTitle 	= JText::_('COM_REDSHOP_REDSHOP_CONFIGURATION_WIZARD');

		$params = new stdClass;
		$params->step = $step;

		$drawdata 			= new stdClass;
		$drawdata->message	= $template->getHTML('terms',$params);
		$drawdata->status 	= $status;
		$drawdata->step 	= $step;
		$drawdata->title 	= JText::_('COM_REDSHOP_REDSHOP_TERMS_CONFIGURATION_WIZARD');
		$drawdata->install 	= 1;

		return $drawdata;
	}

	function user($step){

		$template = new redSHOPWizardTemplate;

		$status				= true;
		$this->pageTitle 	= JText::_('COM_REDSHOP_REDSHOP_CONFIGURATION_WIZARD');

		$params = new stdClass;
		$params->step = $step;

		$drawdata 			= new stdClass;
		$drawdata->message	= $template->getHTML('user',$params);
		$drawdata->status 	= $status;
		$drawdata->step 	= $step;
		$drawdata->title 	= JText::_('COM_REDSHOP_REDSHOP_USER_CONFIGURATION_WIZARD');
		$drawdata->install 	= 1;

		return $drawdata;
	}

	function price($step){

		$template = new redSHOPWizardTemplate;

		$status				= true;

		$params = new stdClass;
		$params->step = $step;

		$drawdata 			= new stdClass;
		$drawdata->message	= $template->getHTML('price',$params);
		$drawdata->status 	= $status;
		$drawdata->step 	= $step;
		$drawdata->title 	= JText::_('COM_REDSHOP_REDSHOP_PRICE_CONFIGURATION_WIZARD');
		$drawdata->install 	= 1;

		return $drawdata;
	}

	function finish($step){

		$template = new redSHOPWizardTemplate;

		$status				= true;

		$params = new stdClass;
		$params->step = '';

		$drawdata 			= new stdClass;
		$drawdata->message	= $template->getHTML('finish',$params);
		$drawdata->status 	= $status;
		$drawdata->step 	= $step;
		$drawdata->title 	= JText::_('COM_REDSHOP_REDSHOP_FINISH_WIZARD');
		$drawdata->install 	= 1;

		return $drawdata;
	}
}


class redSHOPWizardTemplate
{
	var $title = null;
	var $controller = null;

	function __construct()
	{
		require_once JPATH_COMPONENT . '/controllers/wizard.php';

		$this->title	= '';
		$classname  = 'RedshopControllerWizard';
	   	$this->controller = new $classname( array('default_task' => 'display') );
	}

	function getHTML($page='', $params='')
	{
		$page	= '_'.$page;
		return $this->$page($params);
	}

	function _welcome($params)
	{
		ob_start();

		JRequest::setVar('layout','welcome');
	   	JRequest::setVar('params',$params);
	   	$this->controller->execute( JRequest::getVar('task' ));

	   	# update temp file with config data
		$this->controller->execute( 'copyTempFile');

		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}


	function _general($params)
	{
		ob_start();

		JRequest::setVar('layout','general');
	   	JRequest::setVar('params',$params);
	   	$this->controller->execute( JRequest::getVar('task' ));
	   	//$this->controller->redirect();


		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	function _terms($params)
	{
		ob_start();

		JRequest::setVar('layout','terms');
	   	JRequest::setVar('params',$params);
	   	$this->controller->execute( JRequest::getVar('task' ));


		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	function _user($params)
	{
		ob_start();

		JRequest::setVar('layout','user');
	   	JRequest::setVar('params',$params);
	   	$this->controller->execute( JRequest::getVar('task' ));


		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	function _price($params)
	{
		ob_start();

		JRequest::setVar('layout','price');
	   	JRequest::setVar('params',$params);
	   	$this->controller->execute( JRequest::getVar('task' ));


		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	function _finish($params)
	{
		ob_start();

		JRequest::setVar('layout','finish');
	   	JRequest::setVar('params',$params);
	   	$this->controller->execute( JRequest::getVar('task' ));

		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	function setHTML($output, $step, $title, $status, $install= 1, $substep=0)
	{

		$html 		= '';

		$html .= '
			<script>
				function submitwizard(str,step){

					if(str == "exit"){
						window.location.href = "index.php?option=com_redshop";
						return;
					}

					var wform = document.installform;

					if(str == "pre"){
						wform.go.value = "pre";
						if(step == 0){
							wform.task.value = "save";
							wform.substep.value = "6";
						}

					}else{
						wform.go.value = "next";
					}

					wform.submit();
				}
			</script>

			<div id="redSHOPAdminContainer" class="redSHOPAdminViewWizard">
				<div class="wrapper">
					<header class="main-header">
						' . $this->setHTMLTitle($title, $step) . '
					</header>
					<aside class="main-sidebar">
						' . $this->setHTMLSidebar($step) . '
					</aside>
					<div class="content-wrapper">
						<section class="content">' . $output . '</section>
					</div>
				</div>
			</div>';

		echo $html;
	}

	function setHTMLSidebar($activeSteps)
	{
		ob_start();
		?>
		<aside class="main-sidebar">
			<section class="sidebar">
				<ul class="sidebar-menu">
					<li class="<?php if($activeSteps == 1) echo "active"; ?>">
						<a href="#">
							<i>1</i>
							<span><?php echo JText::_('COM_REDSHOP_WIZARD_WELCOME_STEP1');?></span>
						</a>
					</li>

					<li class="<?php if($activeSteps == 2) echo "active"; ?>">
						<a href="#">
							<i>2</i>
							<span><?php echo JText::_('COM_REDSHOP_WIZARD_GENERAL_STEP2');?></span>
						</a>
					</li>

					<li class="<?php if($activeSteps == 3) echo "active"; ?>">
						<a href="#">
							<i>3</i>
							<span><?php echo JText::_('COM_REDSHOP_WIZARD_TERM_STEP3');?></span>
						</a>
					</li>

					<li class="<?php if($activeSteps == 4) echo "active"; ?>">
						<a href="#">
							<i>4</i>
							<span><?php echo JText::_('COM_REDSHOP_WIZARD_USER_STEP3');?></span>
						</a>
					</li>

					<li class="<?php if($activeSteps == 5) echo "active"; ?>">
						<a href="#">
							<i>5</i>
							<span><?php echo JText::_('COM_REDSHOP_WIZARD_FINISH');?></span>
						</a>
					</li>
				</ul>
			</section>

		</aside>
		<?php
		 $html = ob_get_contents();
		 ob_end_clean();
		 return $html;
	}

	function setHTMLTitle($title, $step)
	{
		ob_start();
	?>

			<a role="button" data-toggle="offcanvas" class="sidebar-toggle" href="#">
			<span class="sr-only">Toggle navigation</span>
		</a>
		<div class="component-title"><?php echo $title; ?></div>

		<nav class="navbar navbar-static-top" role="navigation">
			<div class="navbar-custom-menu">
				<ul class="nav navbar-nav">
					<?php if($step >2 || $step==0){ ?>
					<li>
						<?php $vaule = JText::_('COM_REDSHOP_WIZARD_PREVIOUS_BUTTON'); ?>
						<a href="#" onclick="submitwizard('pre','<?php echo $step;?>');"><i class="fa fa-backward"></i><?php echo $vaule; ?></a>
					</li>
					<?php } ?>
					<li>
						<?php $vaule = ($step == 0) ? JText::_('COM_REDSHOP_WIZARD_FINISH_BUTTON') : JText::_('COM_REDSHOP_WIZARD_NEXT_BUTTON'); ?>
						<a href="#" onclick="submitwizard('next',1);"><i class="fa fa-forward"></i><?php echo $vaule; ?></a>
					</li>
					<li>
						<?php $vaule = JText::_('COM_REDSHOP_SKIP_DO_IT_LATER')?>
						<a href="#" onclick="submitwizard('exit');"><?php echo $vaule; ?></a>
					</li>
				</ul>
			</div>
		</nav>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
}
