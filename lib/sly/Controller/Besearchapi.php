<?php
/*
 * Copyright (c) 2013, webvariants GbR, http://www.webvariants.de
 *
 * This file is released under the terms of the MIT license. You can find the
 * complete text in the attached LICENSE file or online at:
 *
 * http://www.opensource.org/licenses/mit-license.php
 */

class sly_Controller_Besearchapi extends sly_Controller_Base implements sly_Controller_Interface {
	public function articlesearchAction() {
		$container  = $this->getContainer();
		$request    = $this->getRequest();
		$query      = $request->get('q', 'string');
		$sql        = $container['sly-persistence'];
		$artService = $container['sly-service-article'];
		$clang      = $container['sly-current-lang-id'];
		$user       = $container['sly-service-user']->getCurrentUser();
		$prefix     = $sql->getPrefix();
		$home       = '('.t('home').')';
		$lines      = array();

		$sql->query('SELECT DISTINCT id FROM '.$prefix.'article WHERE clang = ? AND name LIKE ? GROUP BY id', array($clang, "%$query%"));

		foreach ($sql->all() as $row) {
			$id      = $row['id'];
			$article = $artService->findByPK($id, $clang);

			if ($article && sly_Backend_Authorisation_Util::canReadArticle($user, $id)) {
				$name = str_replace('|', '/', sly_html($article->getName()));
				$path = $article->getParentTree();

				foreach ($path as $idx => $cat) {
					$path[$idx] = str_replace('|', '/', sly_html($cat->getName()));
				}

				if (count($path) > 3) {
					$path = array_slice($path, -2);
					array_unshift($path, '&hellip;');
				}

				array_unshift($path, $home);
				$lines[] = sprintf('%s|%d|%s|%d', $name, $id, implode(' &gt; ', $path), $clang);
			}
		}

		$response = new sly_Response(implode("\n", $lines));
		$response->setContentType('text/plain', 'UTF-8');

		return $response;
	}

	public function checkPermission($action) {
		return $this->getContainer()->get('sly-service-user')->getCurrentUser() !== null;
	}

	protected function getViewFolder() {
		throw new LogicException('This controller has no views.');
	}
}
