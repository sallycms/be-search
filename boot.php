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

	// make sure the addOn loads fine when not installed via Composer (i.e. when developing)
	$container['sly-classloader']->add('sly\besearch\\', SLY_BESEARCH_PATH.'/lib');
	$container['sly-classloader']->add('sly_Controller_', SLY_BESEARCH_PATH.'/lib');

	// register our helper service
	$container['sly-besearch-util'] = $container->share(function($container) {
		return new sly\besearch\Util($container);
	});

	// integrate with the backend
	$container['sly-dispatcher']->addListener('SLY_CONTROLLER_FOUND', array('%sly-besearch-util%', 'controllerFound'));
}
