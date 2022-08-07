<?php
//ベースHTML・Javascriptの場所
define('BASEHTMLFILE', dirname(__FILE__).'/base.html');
define('BASEJSFILE', dirname(__FILE__).'/base.js');
define('BASEJSMUSTACHE', 'https://cdn.jsdelivr.net/npm/mustache@4/mustache.min.js');

//PHPのMustacheライブラリがインストールされているフォルダ
//(autoload設定がされていて明示的にロードする必要がない場合は空文字にする)
define('MUSTACHEDIR', dirname(__FILE__).'/Mustache');

//コンテンツID(idに別の名前を使いたい場合はここを変更)
define('CONTENTID', 'content');