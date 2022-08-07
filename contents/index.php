<?php
require_once(dirname(__FILE__).'/settings.php');
contents_router::router(__FILE__);
?>
<h3>PHPによるSSR付きマイクロSPAライブラリ</h3>
<p>このライブラリはPHPによるSSRをサポートした簡易SPAライブラリです。</p>
<p>Javascriptによる画面遷移のない快適なナビゲーションとファーストビューやSEOに適したPHPによるサーバーサイドレンダリングが簡単に両立できます。</p>
<p>Mustacheテンプレート採用により、PHP/Javascript双方で同じレンダリング結果が得られるようになっています。</p>