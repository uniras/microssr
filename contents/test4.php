<?php

$contents_data = ['test' => ['test', 'テスト2', 'テスト３'], 'test2' => 'あああ', 'test3' => ['a' => 'てすと', 'b' => 'てすと2']];

require_once(dirname(__FILE__).'/settings.php');
contents_router::router(__FILE__, $contents_data);
?>
<p>連想配列のテスト</p>
<ol>
{{#test}}
<li>{{.}}</li>
{{/test}}
</ol>
<div>{{test2}}</div>
{{#test3}}
<div>a : {{a}}</div>
<div>b : {{b}}</div>
{{/test3}}
</div>