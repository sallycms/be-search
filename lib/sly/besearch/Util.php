<?php
/*
 * Copyright (c) 2013, webvariants GbR, http://www.webvariants.de
 *
 * This file is released under the terms of the MIT license. You can find the
 * complete text in the attached LICENSE file or online at:
 *
 * http://www.opensource.org/licenses/mit-license.php
 */

namespace sly\besearch;

use sly\Assets\Util as AssetUtil;

/**
 * Backend Search utility service
 *
 * @author zozi@webvariants.de
 */
class Util implements \sly_ContainerAwareInterface {
	protected $container;

	public function setContainer(\sly_Container $container = null) {
		$this->container = $container;
	}

	/**
	 * adds some needed assets to page
	 *
	 * @param sly_Layout $layout
	 * @param array      $params  additional event parameters
	 */
	public function addAssets(\sly_Layout $layout, array $params) {
		$layout->addCSSFile(AssetUtil::addOnUri('sallycms/be-search', 'css/be_search.less'));

		if (!empty($params['articleSearch'])) {
			$layout->addJavaScriptFile(AssetUtil::addOnUri('sallycms/be-search', 'js/jquery.autocomplete.min.js'));
			$layout->addJavaScriptFile(AssetUtil::addOnUri('sallycms/be-search', 'js/be_search.js'));
		}
	}

	public function mediaToolbar(\sly_Form $form) {
		$this->initWidget(false);

		$mediumName = $this->container['sly-request']->get('be_search_medium_name', 'string');
		$input      = new \sly_Form_Input_Text('be_search_medium_name', '', $mediumName);
		$button     = new \sly_Form_Input_Button('submit', 'be_search_submit', t('search'));

		$row = new \sly_Form_Freeform('be_search_medium_name', t('be_search_medium_name'), $input->render().' '.$button->render());
		$form->add($row);

		return $form;
	}

	public function mediaQuery($where) {
		$mediumName = $this->container['sly-request']->get('be_search_medium_name', 'string');

		if (mb_strlen($mediumName) === 0) {
			return $where;
		}

		$pdo        = $this->container['sly-persistence'];
		$mediumName = $pdo->quote('%'.$mediumName.'%');

		return "$where AND (f.filename LIKE $mediumName OR f.title LIKE $mediumName)";
	}

	/**
	 * Event handler for both PAGE_STRUCTURE_HEADER and PAGE_CONTENT_HEADER
	 *
	 * @param  string $header
	 * @param  array  $params
	 * @return string
	 */
	public function articleSearch($header, array $params) {
		$this->initWidget(true);

		$request    = $this->container['sly-request'];
		$artService = $this->container['sly-service-article'];
		$articleID  = isset($params['article_id']) ? $params['article_id'] : null;
		$clang      = $params['clang'];
		$categoryID = $params['category_id'];
		$searchID   = $request->request('besearch-article-id', 'int', 0);

		// article search by ID

		if ($searchID !== 0) {
			$article = $artService->findByPK($searchID, $clang);

			if ($article) {
				$router  = $this->container['sly-app']->getRouter();
				$editUrl = $router->getAbsoluteUrl('content', null, array('article_id' => $searchID, 'clang' => $clang), '&');

				\sly_Util_HTTP::tempRedirect($editUrl);
			}
		}

		$page      = $this->container['sly-app']->getCurrentControllerName();
		$user      = $this->container['sly-service-user']->getCurrentUser();
		$quickNavi = \sly_Backend_Form_Helper::getCategorySelect('category_id', false, null, null, $user, 'besearch-category-id', true);

		// find current category

		if ($articleID !== 0 && $articleID !== null) {
			$article = $artService->findByPK($articleID, $clang);

			// the article might just have been deleted, so be careful
			if ($article) $categoryID = $article->getCategoryId();
		}

		// pre-select the category
		$quickNavi->setSelected($categoryID);

		ob_start();
		include SLY_BESEARCH_PATH.'/views/toolbar.phtml';
		$bar = ob_get_clean();

		return $bar.$header;
	}

	/**
	 * @param boolean $articleSearch  add js for article search
	 */
	private function initWidget($articleSearch) {
		$this->container['sly-dispatcher']->addListener('PAGE_HEADER', array($this, 'addAssets'), array('articleSearch' => $articleSearch));
		$this->container['sly-i18n']->appendFile(SLY_BESEARCH_PATH.'/lang/');
	}
}
