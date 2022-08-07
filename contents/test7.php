<?php

$qpage = $_GET['page'] ?? '0';
$page = intval($qpage);
$base_data = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20];

$contents_data = array_slice($base_data, $page * 10, 10);

require_once(dirname(__FILE__).'/settings.php');
contents_router::router(__FILE__, $contents_data);
?>
ページネーションのテスト。
ついでにコンテンツ内リンクのテスト。
<ol>
{{#.}}
<li>{{.}}</li>
{{/.}}
</ol>
<a href="./test7.php?page=0">１</a>　<a href="./test7.php?page=1">２</a>