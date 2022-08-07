<?php

//設定ファイルの読み込み
require_once(dirname(__FILE__).'/basesettings.php');

//サーバールーター
class contents_router
{
    public static function router($contents_file, $contents_data = null)
    {
        //GET以外またはgetcontents=trueでない場合(getcontents=trueの付いたGETはコンテンツテンプレートの取得)
        if (empty($_GET['__getcontents']) || $_GET['__getcontents'] != 'true') {
            //GETかつgatdata=trueでない場合(GET以外またはgatdata=trueの付いたGETはJSONデータの取得)
            if ($_SERVER['REQUEST_METHOD'] == 'GET' && (empty($_GET['__getdata']) || $_GET['__getdata'] != 'true')) {
                //ベースファイルとコンテンツのレンダリング(SSR)
                contents_render::base_render($contents_file, $contents_data);
            } else {
                //データJSONの出力(SPA用)
                contents_render::data_render($contents_data);
            }
            exit();
        }
        //コンテンツテンプレートの出力(SPA用) => 何もせずそのままrequire元ファイルの下にあるMustacheテンプレート(HTML)を出力
    }
}

//サーバーレンダー
class contents_render
{
    public static function base_render($contents_file, $contents_data = null)
    {
        //Mustacheクラスのロード
        if (MUSTACHEDIR != '') {
            //composerやautoloadの設定をしている場合は不要
            require_once(MUSTACHEDIR.'/Autoloader.php');
            Mustache_Autoloader::register();
        }

        //Mustacheエンジンのロード
        $template_engine = new Mustache_Engine(
            array(
                'loader' => new Mustache_Loader_StringLoader(),
                'partials_loader' => new Mustache_Loader_FilesystemLoader(dirname($contents_file)),
            ),
            array('entity_flags' => ENT_QUOTES)
        );

        //コンテンツ部分のコンパイル($contents_dataがnullの場合はテンプレートではないということでPHP部分を除いたファイルを出力)
        $contents_source = file_get_contents($contents_file);
        $contents_source = preg_replace('/\<\?(.*?)[?]>/s', '', $contents_source); //PHPコード部分を削除
        if ($contents_data != null) {
            $contents_render = $template_engine->render($contents_source, $contents_data);
            $contents_data['contents_data'] = '<div id="'.CONTENTID.'">'."\n".$contents_render."\n".'</div>';
        } else {
            $contents_data['contents_data'] = '<div id="'.CONTENTID.'">'."\n".$contents_source."\n".'</div>';
        }

        //ベース部分のコンパイル
        if (BASEHTMLFILE != '') {
            $base_source = file_get_contents(BASEHTMLFILE);
            $base_js_source = file_get_contents(BASEJSFILE);
            $contents_data['contents_base_js'] =
                '<script type="text/javascript" src="'.BASEJSMUSTACHE.'"></script>'."\n".
                '<script>'."\n".$base_js_source."\n".
                'contents_render.contents_id = '."'".CONTENTID."';\n".
                '</script>';
            $contents_render = $template_engine->render($base_source, $contents_data);
        }

        //最終結果の変換
        $contents_render = preg_replace('/__FILE__/', basename($contents_file), $contents_render); //__FILE__をテンプレートPHPのファイル名に

        //出力
        echo $contents_render;
    }

    //JSONデータの出力
    public static function data_render($contents_data = null)
    {
        header('Content-Type: application/json');
        if ($contents_data != null) {
            echo json_encode($contents_data);
        } else {
            echo '{}';
        }
    }
}
