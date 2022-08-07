<?php
//直接アクセスの禁止
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );
    exit;
}

//これをコンテンツの各フォルダに置いてrequire_onceすれば、フォルダ構成が変わっても
//このファイルを編集するだけでコンテンツファイルを編集しなくてもよくなる。
define('BASELIBDIR', '/var/www/html/app/microssr/library');
require_once(BASELIBDIR.'/base.php');