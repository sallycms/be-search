<?php
/*
 * Copyright (c) 2013, webvariants GbR, http://www.webvariants.de
 *
 * This file is released under the terms of the MIT license. You can find the
 * complete text in the attached LICENSE file or online at:
 *
 * http://www.opensource.org/licenses/mit-license.php
 */

if (!sly_Core::isBackend()) return;
define('BESEARCH_PATH', rtrim(dirname(__FILE__), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR);

sly_Loader::addLoadPath(BESEARCH_PATH.'lib');

$dispatcher = sly_Core::dispatcher();
$dispatcher->register('PAGE_STRUCTURE_HEADER', array('besearch_Util', 'articleSearch'));
$dispatcher->register('PAGE_CONTENT_HEADER', array('besearch_Util', 'articleSearch'));
$dispatcher->register('SLY_MEDIA_LIST_TOOLBAR', array('besearch_Util', 'mediaToolbar'));
$dispatcher->register('SLY_MEDIA_LIST_QUERY', array('besearch_Util', 'mediaQuery'));
