<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined('_JEXEC') or die;

class redSHOPWizard{

	function initialize(){

		$step 		= JRequest::getVar('step', '');

		$helper = new redSHOPWizardHelper();
		$template = new redSHOPWizardTemplate();

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
				$status 			= new stdClass();
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
		$template = new redSHOPWizardTemplate();

		$status				= true;

		$params = new stdClass();
		$params->step = $step;

		$drawdata 			= new stdClass();
		$drawdata->message	= $template->getHTML('general',$params);
		$drawdata->status 	= $status;
		$drawdata->step 	= $step;
		$drawdata->title 	= JText::_('COM_REDSHOP_REDSHOP_GENERAL_CONFIGURATION_WIZARD');
		$drawdata->install 	= 1;

		return $drawdata;
	}

	function terms($step){

		$template = new redSHOPWizardTemplate();

		$status				= true;
		$this->pageTitle 	= JText::_('COM_REDSHOP_REDSHOP_CONFIGURATION_WIZARD');

		$params = new stdClass();
		$params->step = $step;

		$drawdata 			= new stdClass();
		$drawdata->message	= $template->getHTML('terms',$params);
		$drawdata->status 	= $status;
		$drawdata->step 	= $step;
		$drawdata->title 	= JText::_('COM_REDSHOP_REDSHOP_TERMS_CONFIGURATION_WIZARD');
		$drawdata->install 	= 1;

		return $drawdata;
	}

	function user($step){

		$template = new redSHOPWizardTemplate();

		$status				= true;
		$this->pageTitle 	= JText::_('COM_REDSHOP_REDSHOP_CONFIGURATION_WIZARD');

		$params = new stdClass();
		$params->step = $step;

		$drawdata 			= new stdClass();
		$drawdata->message	= $template->getHTML('user',$params);
		$drawdata->status 	= $status;
		$drawdata->step 	= $step;
		$drawdata->title 	= JText::_('COM_REDSHOP_REDSHOP_USER_CONFIGURATION_WIZARD');
		$drawdata->install 	= 1;

		return $drawdata;
	}

	function price($step){

		$template = new redSHOPWizardTemplate();

		$status				= true;

		$params = new stdClass();
		$params->step = $step;

		$drawdata 			= new stdClass();
		$drawdata->message	= $template->getHTML('price',$params);
		$drawdata->status 	= $status;
		$drawdata->step 	= $step;
		$drawdata->title 	= JText::_('COM_REDSHOP_REDSHOP_PRICE_CONFIGURATION_WIZARD');
		$drawdata->install 	= 1;

		return $drawdata;
	}

	function finish($step){

		$template = new redSHOPWizardTemplate();

		$status				= true;

		$params = new stdClass();
		$params->step = '';

		$drawdata 			= new stdClass();
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
		require_once JPATH_COMPONENT.DS.'controllers'.DS.'wizard.php';

		$this->title	= '';
		$classname  = 'wizardController';
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
	<style type="text/css">
	/**
	 * Reset Joomla! styles
	 */
	div.t, div.b {
		height: 0;
		margin: 0;
		background: none;
	}

	body #content-box div.padding {
		padding: 0;
	}

	body div.m {
		padding: 0;
		border: 0;
	}

	.button1-right {
		background: url('.JURI::root().'administrator/components/com_redshop/assets/images/wizard/j_button1_right.png) 100% 0 no-repeat;
		float: left;
		margin-left: 5px;
	}

	.button1-right .prev {
		float: left;
		background: url('.JURI::root().'administrator/components/com_redshop/assets/images/wizard/j_button1_prev.png) no-repeat;
	}

	.button-previous{
		border:0;
		background: none;
		font-size: 11px;
		height: 26px;
		line-height: 24px;
		padding-left: 30px;
		cursor: pointer;
	}

	.button1-left {
		background: transparent url('.JURI::root().'administrator/components/com_redshop/assets/images/wizard/j_button1_left.png) no-repeat scroll 0 0;
		float: left;
		margin-left: 5px;
		cursor: pointer;
	}
	.button1-left .next {
		background: transparent url('.JURI::root().'administrator/components/com_redshop/assets/images/wizard/j_button1_next.png) no-repeat scroll 100% 0;
		float: left;
		cursor: pointer;
	}

	.button1-left .exit {
		background: transparent url('.JURI::root().'administrator/components/com_redshop/assets/images/wizard/j_button1_admin.png) no-repeat scroll 100% 0;
		float: left;
		cursor: pointer;
	}

	.button-next{
		border: 0;
		background: none;
		font-size: 11px;
		height: 26px;
		line-height: 24px;
		padding-right: 30px;
		cursor: pointer;
	}

	h1.steps{
		color:#0B55C4;
		font-size:20px;
		font-weight:bold;
		margin:0;
		padding-bottom:8px;
	}

	div.steps {
		font-size: 12px;
		font-weight: bold;
		padding-bottom: 12px;
		padding-top: 10px;
		background: url('.JURI::root().'administrator/components/com_redshop/assets/images/wizard/j_divider.png) 0 100% repeat-x;
	}

	div.on {
		color:#0B55C4;
	}

	#toolbar-box,
	#submenu-box,
	#header-box {
		display: none;
	}

	div#cElement-box div.t, div#cElement-box div.b {
		height: 6px;
		padding: 0;
		margin: 0;
		overflow: hidden;
	}

	div#cElement-box div.m {
		border-left: 1px solid #ccc;
		border-right: 1px solid #ccc;
		padding: 1px 8px;
	}

	div#cElement-box div.t {
		background: url('.JURI::root().'administrator/components/com_redshop/assets/images/wizard/j_border.png) 0 0 repeat-x;
	}

	div#cElement-box div.t div.t {
		background: url('.JURI::root().'administrator/components/com_redshop/assets/images/wizard/j_crn_tr_light.png) 100% 0 no-repeat;
	}

	div#cElement-box div.t div.t div.t {
		background: url('.JURI::root().'administrator/components/com_redshop/assets/images/wizard/j_crn_tl_light.png) 0 0 no-repeat;
	}

	div#cElement-box div.b {
		background: url('.JURI::root().'administrator/components/com_redshop/assets/images/wizard/j_border.png) 0 100% repeat-x;
	}

	div#cElement-box div.b div.b {
		background: url('.JURI::root().'administrator/components/com_redshop/assets/images/wizard/j_crn_br_light.png) 100% 0 no-repeat;
	}

	div#cElement-box div.b div.b div.b {
		background: url('.JURI::root().'administrator/components/com_redshop/assets/images/wizard/j_crn_bl_light.png) 0 0 no-repeat;
	}
	#stepbar {
		float: left;
		width: 170px;
	}

	#stepbar div.box {
		background: url('.JURI::root().'administrator/components/com_redshop/assets/images/wizard_48.png) 0 0 no-repeat;
		height: 140px;
		background-position: center;
	}

	#stepbar h1 {
		margin: 0;
		padding-bottom: 8px;
		font-size: 20px;
		color: #0B55C4;
		font-weight: bold;
		background: url('.JURI::root().'administrator/components/com_redshop/assets/images/wizard/j_divider.png) 0 100% repeat-x;
	}

	div#stepbar {
	  background: #f7f7f7;
	}

	div#stepbar div.t {
	  background: url('.JURI::root().'administrator/components/com_redshop/assets/images/wizard/j_border.png) 0 0 repeat-x;
	}

	div#stepbar div.t div.t {
	   background: url('.JURI::root().'administrator/components/com_redshop/assets/images/wizard/j_crn_tr_dark.png) 100% 0 no-repeat;
	}

	div#stepbar div.t div.t div.t {
	   background: url('.JURI::root().'administrator/components/com_redshop/assets/images/wizard/j_crn_tl_dark.png) 0 0 no-repeat;
	}

	div#stepbar div.b {
	  background: url('.JURI::root().'administrator/components/com_redshop/assets/images/wizard/j_border.png) 0 100% repeat-x;
	}

	div#stepbar div.b div.b {
	   background: url('.JURI::root().'administrator/components/com_redshop/assets/images/wizard/j_crn_br_dark.png) 100% 0 no-repeat;
	}

	div#stepbar div.b div.b div.b {
	   background: url('.JURI::root().'administrator/components/com_redshop/assets/images/wizard/j_crn_bl_dark.png) 0 0 no-repeat;
	}

	div#stepbar div.t, div#stepbar div.b {
		height: 6px;
		margin: 0;
		overflow: hidden;
		padding: 0;
	}

	div#stepbar div.m,
	div#cToolbar-box div.m {
		padding: 0 8px;
		border-left: 1px solid #ccc;
		border-right: 1px solid #ccc;
	}

	div#cToolbar-box {
		background: #f7f7f7;
		position: relative;
	}

	div#cToolbar-box div.m {
		padding: 0;
		height: 30px;
	}

	div#cToolbar-box {
		background: #fbfbfb;
	}

	div#cToolbar-box div.t,
	div#cToolbar-box div.b {
		height: 6px;
	}

	div#cToolbar-box span.title {
		color: #0B55C4;
		font-size: 20px;
		font-weight: bold;
		line-height: 30px;
		padding-left: 6px;
	}

	div#cToolbar-box div.t {
	  background: url('.JURI::root().'administrator/components/com_redshop/assets/images/wizard/j_border.png) 0 0 repeat-x;
	}

	div#cToolbar-box div.t div.t {
	   background: url('.JURI::root().'administrator/components/com_redshop/assets/images/wizard/j_crn_tr_med.png) 100% 0 no-repeat;
	}

	div#cToolbar-box div.t div.t div.t {
	   background: url('.JURI::root().'administrator/components/com_redshop/assets/images/wizard/j_crn_tl_med.png) 0 0 no-repeat;
	}

	div#cToolbar-box div.b {
	  background: url('.JURI::root().'administrator/components/com_redshop/assets/images/wizard/j_border.png) 0 100% repeat-x;
	}

	div#cToolbar-box div.b div.b {
	   background: url('.JURI::root().'administrator/components/com_redshop/assets/images/wizard/j_crn_br_med.png) 100% 0 no-repeat;
	}

	div#cToolbar-box div.b div.b div.b {
	   background: url('.JURI::root().'administrator/components/com_redshop/assets/images/wizard/j_crn_bl_med.png) 0 0 no-repeat;
	}
	</style>
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
	<table cellpadding="6" width="100%">
		<tr>
			<td rowspan="2" valign="top" width="10%">' . $this->setHTMLSidebar($step) . '</td>
			<td valign="top" height="30">' . $this->setHTMLTitle($title, $step, $status, $install, $substep) . '</td>
		</tr>
		<tr>
			<td valign="top">
				<div id="cElement-box">
					<div class="t">
				 		<div class="t">
							<div class="t"></div>
				 		</div>
					</div>
					<div class="m">
					'. $output . '
					</div>
					<div class="b">
						<div class="b">
							<div class="b"</div>
						</div>
					</div>
				</div>
			</td>
		</tr>
	</table>';

		echo $html;
	}

	function setHTMLSidebar($activeSteps)
	{
		ob_start();
		?>

		<div id="stepbar">
			<div class="t">
				<div class="t">
					<div class="t"></div>
				</div>
			</div>
			<div class="m">
				<h1 class="steps"><?php echo JText::_('COM_REDSHOP_WIZARD_STEPS');?></h1>
				<div id="stepFirst" class="steps<?php if($activeSteps == 1) echo " on"; ?>"><?php echo JText::_('COM_REDSHOP_WIZARD_WELCOME_STEP1');?></div>
				<div class="steps<?php if($activeSteps == 2) echo " on"; ?>"><?php echo JText::_('COM_REDSHOP_WIZARD_GENERAL_STEP2');?></div>
				<div class="steps<?php if($activeSteps == 3) echo " on"; ?>"><?php echo JText::_('COM_REDSHOP_WIZARD_TERM_STEP3');?></div>
				<div class="steps<?php if($activeSteps == 4) echo " on"; ?>"><?php echo JText::_('COM_REDSHOP_WIZARD_USER_STEP3');?></div>
				<div class="steps<?php if($activeSteps == 5) echo " on"; ?>"><?php echo JText::_('COM_REDSHOP_WIZARD_PRICE_STEP4');?></div>
				<div id="stepLast" class="steps<?php if($activeSteps == 0) echo " on"; ?>"><?php echo JText::_('COM_REDSHOP_WIZARD_FINISH');?></div>
				<div class="box"></div>
		  	</div>
			<div class="b">
				<div class="b">
					<div class="b"></div>
				</div>
			</div>
	  	</div>

		<?php
		 $html = ob_get_contents();
		 ob_end_clean();
		 return $html;
	}

	function setHTMLTitle($title, $step, $status, $install = 1, $substep = 0)
	{
		ob_start();
		?>
			<div id="cToolbar-box">
	   			<div class="t">
					<div class="t">
						<div class="t"></div>
					</div>
				</div>
				<div class="m">
					<?php

					if($step >2 || $step==0){
					?>
					<div>
						<div id="Container">
							<div class="button1-right">
								<div id="div-button-prev" class="prev">
									<?php $vaule = JText::_('COM_REDSHOP_WIZARD_PREVIOUS_BUTTON'); ?>
									<input type="button" id="input-button-next" class="button-previous" onclick="submitwizard('pre','<?php echo $step;?>');" value="<?php echo $vaule;?>"/>
								</div>
							</div>
						</div>
					</div>
					<?php
					}
					?>
					<span class="title">
						<?php echo $title; ?>
					</span>

					<div style="position: absolute; top: 8px; right: 150px;">
						<div id="Container">
							<div class="button1-left">
								<div id="div-button-next" class="next">
									<?php $vaule = ($step == 0) ? JText::_('COM_REDSHOP_WIZARD_FINISH_BUTTON') : JText::_('COM_REDSHOP_WIZARD_NEXT_BUTTON'); ?>
									<input type="button" id="input-button-next" class="button-next" onclick="submitwizard('next',1);" value="<?php echo $vaule;?>"/>
								</div>
							</div>
						</div>
					</div>
					<div style="position: absolute; top: 8px; right: 10px;">
						<div id="Container">
							<div class="button1-left">
								<div id="div-button-next" class="exit">
									<?php $vaule = JText::_('COM_REDSHOP_SKIP_DO_IT_LATER')?>
									<input type="button" id="input-button-next" class="button-next" onclick="submitwizard('exit');" value="<?php echo $vaule;?>"/>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="b">
					<div class="b">
						<div class="b"></div>
					</div>
				</div>
	  		</div>

		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
}
