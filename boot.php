<?php
/*
 * Copyright (c) 2013, webvariants GbR, http://www.webvariants.de
 *
 * This file is released under the terms of the MIT license. You can find the
 * complete text in the attached LICENSE file or online at:
 *
 * http://www.opensource.org/licenses/mit-license.php
 */

if ($container['sly-app']->isBackend()) {
	define('SLY_BESEARCH_PATH', __DIR__);

	// register our helper service
	$container['sly-besearch-util'] = $container->share(function($container) {
		return new sly\besearch\Util($container);
	});

	$dispatcher = $container['sly-dispatcher'];
	$util       = '%sly-besearch-util%';

	// integrate with the backend
	$dispatcher->addListener('PAGE_STRUCTURE_HEADER',  array($util, 'articleSearch'));
	$dispatcher->addListener('PAGE_CONTENT_HEADER',    array($util, 'articleSearch'));
	$dispatcher->addListener('SLY_MEDIA_LIST_TOOLBAR', array($util, 'mediaToolbar'));
	$dispatcher->addListener('SLY_MEDIA_LIST_QUERY',   array($util, 'mediaQuery'));

	// clear our simple cache whenever something changed
	$dispatcher->addListener('SLY_CAT_ADDED',        array($util, 'clearCache'));
	$dispatcher->addListener('SLY_CAT_DELETED',      array($util, 'clearCache'));
	$dispatcher->addListener('SLY_CAT_MOVED',        array($util, 'clearCache'));
	$dispatcher->addListener('SLY_ART_TO_STARTPAGE', array($util, 'clearCache'));
	$dispatcher->addListener('SLY_CLANG_ADDED',      array($util, 'clearCache'));
	$dispatcher->addListener('SLY_CLANG_DELETED',    array($util, 'clearCache'));
	$dispatcher->addListener('SLY_CACHE_CLEARED',    array($util, 'clearCache'));
	$dispatcher->addListener('SLY_CAT_UPDATED',      array($util, 'clearPerLanguageCache'));
	$dispatcher->addListener('SLY_ART_ONLINE',       array($util, 'clearPerLanguageCache'));
	$dispatcher->addListener('SLY_ART_OFFLINE',      array($util, 'clearPerLanguageCache'));
	$dispatcher->addListener('SLY_ART_TOUCHED',      array($util, 'clearPerLanguageCache'));
	$dispatcher->addListener('SLY_USER_UPDATED',     array($util, 'clearPerUserCache'));
	$dispatcher->addListener('SLY_USER_DELETED',     array($util, 'clearPerUserCache'));
}
