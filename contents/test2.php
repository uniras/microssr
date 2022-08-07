<?php

$contents_data = ['test' => 'テスト２'];

require_once(dirname(__FILE__).'/settings.php');
contents_router::router(__FILE__, $contents_data);
?>
<div>{{test}}</div>
<div>{{test}}</div>