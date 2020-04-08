<?php

import('lib.pkp.classes.plugins.GenericPlugin');

class SPSLensGalleyPlugin extends GenericPlugin {

	function register($category, $path) {
		if (parent::register($category, $path)) {
			if ($this->getEnabled()) {
				HookRegistry::register('TemplateManager::display',array(&$this, 'callback'));
				HookRegistry::register('PluginRegistry::loadCategory', array(&$this, 'callbackLoadGateway'));
			}
			return true;
		}
		return false;
	}

	function callbackLoadGateway($hookName, $args) {
		$category =& $args[0];
		$plugins =& $args[1];
		switch ($category) {
			case 'gateways':
				$this->import('LensGatewayPlugin');
				$plugins =& $args[1];
				$gatewayPlugin = new LensGatewayPlugin($this->getName());
				$plugins[$gatewayPlugin->getSeq()][$gatewayPlugin->getPluginPath()] =& $gatewayPlugin;

                                $this->import('LensGatewayPluginImage');
                                $plugins =& $args[1];
                                $gatewayPluginImage = new LensGatewayPluginImage($this->getName());
                                $plugins[$gatewayPluginImage->getSeq() . 'Image'][$gatewayPluginImage->getPluginPath()] =& $gatewayPluginImage;

		}
		return false;
	}

	function getContextSpecificPluginSettingsFile() {
		return $this->getPluginPath() . '/settings.xml';
	}

	function getInstallSitePluginSettingsFile() {
		return $this->getPluginPath() . '/settings.xml';
	}

	function callback($hookName, $args) {
		$request =& Registry::get('request');
		if (!is_a($request->getRouter(), 'PKPPageRouter')) return null;

		$templateManager =& $args[0];
		$page = Request::getRequestedPage();
		$rev = Request::getContext()->_data["path"];
		$issueId = $templateManager->get_template_vars('issue')->_data["id"];
		$currentUrl = $templateManager->get_template_vars('currentUrl');

		$baseUrl = $templateManager->get_template_vars('baseUrl');
		$additionalHeadData = $templateManager->get_template_vars('additionalHeadData');

		$SlideshowScript = '
		<script type="text/javascript">
			$(document).ready(function(){
				var a = "";
				if((window.location.href).indexOf("issue/view") > -1 )
					a = $(".tocGalleys").find("a");
				else if((window.location.href).indexOf("article/view") > -1)
					a = $("#articleFullText").find("a");

				$.each(a, function(k,v){
				    if($(v).html() == "LENS" || $(v).html() == "XML"){
				        var galId = $(v).attr("href").split("/").pop();
						var art = $(v).attr("href").split("/");
						art = art[art.length - 2];
				        $(v).attr("href", "' . $baseUrl . '/plugins/generic/lens/viewer.php?issue=' . $issueId . '&galId=" + galId + "&art=" + art + "&r=' . $rev  . '&b=' . base64_encode($baseUrl) .  '&ref=' . base64_encode($currentUrl) . '");
				    }
				});
			});
		</script>';

		$templateManager->assign('additionalHeadData', $additionalHeadData."\n".$SlideshowScript);
		return false;
	}

	function getDisplayName() {
		return __('plugins.generic.spsLensGalley.displayName');
	}

	function getDescription() {
		return __('plugins.generic.spsLensGalley.description');
	}

	function getManagementVerbs() {
		$verbs = array();
		$verbs = parent::getManagementVerbs();
		return $verbs;
	}
}
?>
