<?php

$contents_data = ['test', 'テスト2', 'テスト３'];

require_once(dirname(__FILE__).'/settings.php');
contents_router::router(__FILE__, $contents_data);
?>
<p>配列のテスト</p>
<ol>
{{#.}}
<li>{{.}}</li>
{{/.}}
</ol>
