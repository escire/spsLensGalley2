<?php

import('classes.article.ArticleGalleyDAO');


class ArticleDAO extends ArticleGalleyDAO {
	var $parentPluginName;

	function ArticleDAO($parentPluginName) {
		$this->parentPluginName = $parentPluginName;
		parent::ArticleGalleyDAO();
	}

	function getXMLGalleyFromId($galleyId, $articleId = null) {
		$params = array((int) $galleyId);
		if ($articleId) $params[] = (int) $articleId;

		$result =& $this->retrieve(
			'SELECT	x.*,
				x.galley_type AS file_type,
				g.file_id,
				g.html_galley,
				g.style_file_id,
				g.seq,
				g.locale,
				g.remote_url,
				a.file_name,
				a.original_file_name,
				a.file_stage,
				a.file_type,
				a.file_size,
				a.date_uploaded,
				a.date_modified
			FROM	article_xml_galleys x
				LEFT JOIN article_galleys g ON (x.galley_id = g.galley_id)
				LEFT JOIN article_files a ON (g.file_id = a.file_id)
			WHERE	x.galley_id = ?
				' . ($articleId?' AND x.article_id = ?':''),
			$params
		);

		if ($result->RecordCount() != 0) {
			$res = $result->GetRowAssoc(false);
			return $res;
		}
	}

	function getImageGalleyFromImageName($fileName, $articleId = null){
                $params = array($fileName);
                $params[] = $articleId;

                $result =& $this->retrieve(
                        'SELECT *
                        FROM    article_settings
                        WHERE   setting_value = ?',
			array($articleId)
                );

		if($result->RecordCount() == 1){
			$res = $result->GetRowAssoc(false);
			$params[1] = $res["article_id"];
		}

                $result =& $this->retrieve(
     	                'SELECT *
                        FROM    article_files
                        WHERE   original_file_name = ?
                        AND article_id = ?',
                        $params
                );

                if ($result->RecordCount() != 0) {
                        $res = $result->GetRowAssoc(false);
                        return $res;
                }

	}


}

?>
