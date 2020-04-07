<?php


import('classes.plugins.GatewayPlugin');
import('pages.sectionEditor.SectionEditorHandler');

class LensGatewayPluginImage extends GatewayPlugin {

	var $parentPluginName;

	function LensGatewayPluginImage($parentPluginName) {
		//parent::GatewayPlugin();
		parent::__construct();
		$this->parentPluginName = $parentPluginName;
	}

	function getHideManagement() {
		return true;
	}

	function getName() {
		return 'LensGatewayPluginImage';
	}

	function getDisplayName() {
		return "Lens Gateway Plugin Image";
	}

	function getDescription() {
		return "Devuelve una imagen";
	}

	function &getLensPluginImage() {
		$plugin =& PluginRegistry::getPlugin('generic', $this->parentPluginName);
		return $plugin;
	}

	function getPluginPath() {
		$plugin =& $this->getLensPluginImage();
		return $plugin->getPluginPath();
	}

	function getTemplatePath() {
		$plugin =& $this->getLensPluginImage();
		return $plugin->getTemplatePath() . 'templates/';
	}

	function getEnabled() {
		$plugin =& $this->getLensPluginImage();
		return $plugin->getEnabled();
	}

	function getManagementVerbs() {
		return array();
	}

	function fetch($args) {
		AppLocale::requireComponents(array(LOCALE_COMPONENT_APPLICATION_COMMON));

                $journal =& Request::getJournal();
                if (!$journal) return false;

                $articleId = array_shift($args);
                $fileName = array_shift($args);
		$fileName = urldecode($fileName);
                //$this->validate($articleId);
//                if (!SectionEditorAction::downloadFile($articleId, $fileId, $revision)) {
//                       $request->redirect(null, null, 'submission', $articleId);
//                }

//echo $articleId;
//echo $fileName;

                $this->import('ArticleDAO');
                $galleyDao = new ArticleDAO($this->getName());
                $galley = $galleyDao->getImageGalleyFromImageName($fileName, $articleId);

                import('classes.file.JournalFileManager');
                $fileManager = new JournalFileManager($journal);

                $file = $fileManager->filesDir . 'articles/' . $galley["article_id"] . '/public/' . $galley["file_name"];

		$imageType = exif_imagetype($file);

		header('Content-type: ' . image_type_to_mime_type($imageType));
                $fileManager->readFile($file, true);

		return true;
	}

	function manage($verb, $args, &$message, &$messageParams) {
		if (!parent::manage($verb, $args, $message, $messageParams)) return false;

		AppLocale::requireComponents(
			LOCALE_COMPONENT_APPLICATION_COMMON,
			LOCALE_COMPONENT_PKP_MANAGER,
			LOCALE_COMPONENT_PKP_USER
		);

		return true;
	}
/*
	function getMetricTypes(){}
	function getPubId(){}
	function getFormFieldNames(){}
	function getPubIdMetadataFile(){}
	function getPubIdType(){}
	function getExcludeFormFieldName(){}
	function getBlockContext(){}
	function getSupportedContexts(){}
*/
}

?>
