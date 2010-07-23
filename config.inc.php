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

if (!$REX['REDAXO']) return;

$mypage = 'be_search';


// Suchmodus
// global => Es werden immer alle Kategorien durchsucht
// local => Es werden immer die aktuelle+Unterkategorien durchsucht
// $REX['ADDON']['searchmode'][$mypage] = 'global';
//$REX['ADDON']['searchmode'][$mypage] = 'local';

$REX['EXTPERM'][] = 'be_search[mediapool]';
$REX['EXTPERM'][] = 'be_search[structure]';


$I18N->appendFile($REX['INCLUDE_PATH'].'/addons/'.$mypage.'/lang/');

// Include Functions
require_once $REX['INCLUDE_PATH'].'/addons/be_search/functions/functions.search.inc.php';

rex_register_extension('PAGE_CHECKED', 'rex_a256_extensions_handler');