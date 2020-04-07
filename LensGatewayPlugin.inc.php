<?php


import('classes.plugins.GatewayPlugin');

class LensGatewayPlugin extends GatewayPlugin {

	var $parentPluginName;

	function LensGatewayPlugin($parentPluginName) {
		//parent::GatewayPlugin();
		parent::__construct();
		$this->parentPluginName = $parentPluginName;
	}

	function getHideManagement() {
		return true;
	}

	function getName() {
		return 'LensGatewayPlugin';
	}

	function getDisplayName() {
		return "Lens Gateway Plugin";
	}

	function getDescription() {
		return "Genera el XML de la galerada";
	}

	function &getLensPlugin() {
		$plugin =& PluginRegistry::getPlugin('generic', $this->parentPluginName);
		return $plugin;
	}

	function getPluginPath() {
		$plugin =& $this->getLensPlugin();
		return $plugin->getPluginPath();
	}

	function getTemplatePath() {
		$plugin =& $this->getLensPlugin();
		return $plugin->getTemplatePath() . 'templates/';
	}

	function getEnabled() {
		$plugin =& $this->getLensPlugin();
		return $plugin->getEnabled();
	}

	function getManagementVerbs() {
		return array();
	}

	function fetch($args) {
		AppLocale::requireComponents(array(LOCALE_COMPONENT_APPLICATION_COMMON));

		$journal =& Request::getJournal();
		if (!$journal) return false;

		$issueDao =& DAORegistry::getDAO('IssueDAO');
		$issue =& $issueDao->getCurrentIssue($journal->getId(), true);
		if (!$issue) return false;

		$lensPlugin =& $this->getLensPlugin();
		if (!$lensPlugin->getEnabled()) return false;

		$data = array_shift($args);

		if(empty($data))
			 return false;

		if(!is_numeric($data)){
			echo "Oops!, ha ocurrido un error";
			return;
		}
		$data = filter_var($data, FILTER_SANITIZE_STRING);

		$this->import('ArticleDAO');
		$xmlGalleyDao = new ArticleDAO($this->getName());
		$xmlGalley = $xmlGalleyDao->getXMLGalleyFromId($data, null);

		import('classes.file.JournalFileManager');
		$fileManager = new JournalFileManager($journal);

		$file = $fileManager->filesDir . 'articles/' . $xmlGalley["article_id"] . '/public/' . $xmlGalley["file_name"];
		header('Content-type: text/xml');
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
