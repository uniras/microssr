//クライアントルーター
class content_router {
    //初期化
    static init() {
        //イベントハンドラの設定
        window.addEventListener('popstate', () => {
            content_router.onBack(location.href);
        });

        document.querySelectorAll('a').forEach(a => {
            a.onclick = event => {
                event.preventDefault();
                window.history.pushState(null, '', a.href);
                content_router.onClick(a.href);
            };
        });
    }

    //クライアントレンダリングしたページのaタグにイベントを設定
    static setRenderPageEvent(id)
    {
        document.querySelectorAll(`#${id} > a`).forEach(a => {
            a.onclick = event => {
                event.preventDefault();
                window.history.pushState(null, '', a.href);
                content_router.onClick(a.href);
            };
        });
    }

    //ブラウザバックした時の動作
    static onBack(href) {
        contents_render.render(href);
    }

    //アンカーリンクをクリックしたときの動作
    static onClick(href) {
        contents_render.render(href);
    }
}

//クライアントレンダー
class contents_render {
    static contents_source = {};
    static contents_is_template = {};
    static contents_id = 'contents'; //サーバーレンダーにより設定したidに再代入される

    //データの取得
    static async get_request(url, type, param) {
        let data;
        let addchr = '';
        if (param !== '') addchr = url.indexOf('?') === -1 ? '?' : '&'
        const response = await fetch(`${url}${addchr}${param}`);
        if (type === 'json') {
            data = response.json();
        } else {
            data = response.text();
        }
        return data;
    }

    //コンテンツテンプレートの取得
    static async get_contents(contents_url) {
        const data = await contents_render.get_request(contents_url, 'text', '__getcontents=true');
        return data;
    }

    //JSONデータの取得
    static async get_data(contents_url) {
        const data = await contents_render.get_request(contents_url, 'json', '__getdata=true');
        return data;
    }

    //pertialでincludeするための処理
    static get_partial(file) {
        let data = '';
        const getfile = `./${file}.mustache`;

        //mustacheテンプレートの取得(すでに取得済みのテンプレートは取得しない)
        if (typeof (contents_render.contents_source[getfile]) !== 'string') {
            //非同期関数はうまく動かないので同期のXHRを使う        
            let request = new XMLHttpRequest();
            request.open('GET', getfile, false);
            request.send(null);
            if (request.status == 200) {
                data = request.responseText;
            }
            contents_render.contents_source[getfile] = data;
        } else {
            data = contents_render.contents_source[getfile];
        }

        return data;
    }

    //コンテンツHTMLの描画
    static async render(contents_url) {
        let template;
        let data = null;
        let output = '';
        let get_url;

        //テンプレート取得配列用にクエリストリングを除いたURLを取得
        const query = contents_url.indexOf('?');
        if(query !== -1) {
            get_url = contents_url.substring(0, query);
        } else {
            get_url = contents_url;
        }

        //コンテンツテンプレートの取得(すでに取得済みのテンプレートは取得しない)
        if (typeof (contents_render.contents_source[get_url]) !== 'string') {
            contents_render.contents_source[get_url] = await contents_render.get_contents(contents_url);
            //'{{'がどこかに存在すればテンプレートとみなす。
            template = contents_render.contents_source[get_url].indexOf('{{') === -1 ? false : true;
            contents_render.contents_is_template[get_url] = template;
        } else {
            template = contents_render.contents_is_template[get_url];            
        }

        //テンプレートであればデータの取得
        if (template) data = await contents_render.get_data(contents_url);

        //出力
        const element = document.getElementById(contents_render.contents_id);
        if (element !== null) {
            if (template) {
                output = Mustache.render(contents_render.contents_source[get_url], data, contents_render.get_partial);
            } else {
                output = contents_render.contents_source[get_url];
            }
            element.innerHTML = output;
            content_router.setRenderPageEvent(contents_render.contents_id);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    content_router.init();
});