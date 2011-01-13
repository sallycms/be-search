<?php
/*
 * Copyright (C) 2009 REDAXO
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License Version 2 as published by the
 * Free Software Foundation.
 */

/**
 * Backend Search Addon
 *
 * @author  markus[dot]staab[at]redaxo[dot]de Markus Staab
 * @package redaxo4
 */

if (!sly_Core::isBackend()) return;

sly_Core::getI18N()->appendFile(SLY_INCLUDE_PATH.'/addons/be_search/lang/');
require_once SLY_INCLUDE_PATH.'/addons/be_search/functions/functions.search.inc.php';
sly_Core::dispatcher()->register('PAGE_CHECKED', 'rex_a256_extensions_handler');
