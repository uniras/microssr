<?php

$contents_data = ['test' => 'ใในใ'];

require_once(dirname(__FILE__).'/settings.php');
contents_router::router(__FILE__, $contents_data);
?>
{{> mustache/test6}}