<?php

  // SQLi時に表示するジョークページ

  header( "HTTP/1.1 418 I'm a teapot" );
  if( defined( "request_path" ) && strpos( request_path, "'" ) !== false ) {

  ?>
<!DOCTYPE html>
    <html lang="ja" dir="ltr">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
            <meta name="robots" content="noindex, nofollow, noarchive">
            <title>【418】I’m a teapot.</title>
            <style>*,:after,:before{-webkit-box-sizing:inherit;box-sizing:inherit}.btn,a.btn,button.btn{font-size:1.6rem;font-weight:700;line-height:1.5;position:relative;display:inline-block;padding:1rem 4rem;cursor:pointer;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;-webkit-transition:all .3s;transition:all .3s;text-align:center;vertical-align:middle;text-decoration:none;letter-spacing:.1em;color:#212529;border-radius:.5rem}a.btn--blue.btn--border-double{border:8px double #0090bb}a{color:#c71585;position:relative;display:inline-block}a,a:after{transition:.3s}a:after{position:absolute;bottom:0;left:50%;content:'';width:0;height:2px;background-color:#31aae2;transform:translateX(-50%)}a:hover:after{width:100%}a.btn--orange{color:#000;background-color:#eb6100;border-bottom:5px solid #b84c00}a.btn--orange:hover{margin-top:3px;color:#000;background:#f56500;border-bottom:2px solid #b84c00}a.btn--lime{color:#000;background-color:#0f0;border-bottom:5px solid #0f0}a.btn--lime:hover{margin-top:3px;color:#000;background:#0f0;border-bottom:2px solid #0f0}a.btn--shadow{-webkit-box-shadow:0 3px 5px rgba(0,0,0,.3);box-shadow:0 3px 5px rgba(0,0,0,.3)}a.btn--darkblue.btn--border-solid{border:2px solid #00008b;height:20px;width:auto;font-size:1rem!important;font-weight:300!important;line-height:1!important}a.btn--red.btn--border-inset{border:6px inset #b9000e;font-size:.8rem!important;font-weight:300!important;line-height:1!important}</style>
        </head>
        <body style="background-color:#6495ed;color:#080808;">
            <div id="animearea" style="position:fixed;left:0px;width:100%;height:100%;"></div>
            <div style="position:fixed;left:0px;top:0px;width:100%;background-color:transparent;z-index:1;">
                <br>
                <div align="center">
                    <span style="background-color:#e6e6fa;text:#363636;text-align:center;vertical-align:middle;">
                        <h1>【418】I’m a teapot.</h1>
                    </span>
                </div>
                <div align="center" style="width:95%;">
                    <hr size="1" color="#7fffd4">
                    <div align="center" style="width:70%;vertical-align:middle;text-align:center;">
                        <b>あの・・なんだろう..。</b>
                        <br>
                        <b>適当に人のウェブサイトでSQLiしようとするのやめてもらっていいですか？</b>
                        <br>
                        <br>
                        <a href="https://www.google.com/" class="btn btn--lime btn--cubic btn--shadow" onclick=\'javascript:alert("☆*: .｡. o(≧▽≦)o .｡.:*☆");window.open("data:text/plain,YOU ARE AN IDIOT!");\' rel="noopener noreferrer">Goggleへ戻る</a>
                        <a href="bitcoin:bc1qgwvl678zvr7k5xegfcfs8mz46c34n7r9qckkqf" class="btn btn--orange btn--cubic btn--shadow">満州鉄道を爆破</a>
                    </div>
                </div>
                <hr size="1" color="#7fffd4">
            </div>
            <script type="text/javascript" src="https://code.activetk.jp/particles.min.js"></script>
            <script>particlesJS("animearea",{particles:{number:{value:40,density:{enable:!0,value_area:200}},shape:{type:"star",stroke:{width:0,color:"#ffcc00"}},color:{value:"#ffffff"},opacity:{value:1,random:!1,anim:{enable:!1,speed:10,opacity_min:.1,sync:!1}},size:{value:5,random:!0,anim:{enable:!1,speed:40,size_min:.1,sync:!1}},line_linked:{enable:!0,distance:150,color:"#ffffff",opacity:.4,width:1},move:{speed:12,straight:!1,direction:"none",out_mode:"bounce"}},interactivity:{detect_on:"canvas",events:{onhover:{enable:!0,mode:"repulse"},onclick:{enable:!0,mode:"push"}},modes:{grab:{distance:400,line_linked:{opacity:1}},repulse:{distance:200},bubble:{distance:400,size:40,opacity:8,duration:2,speed:3},push:{particles_nb:4},remove:{particles_nb:2}}},retina_detect:!0,resize:!0});</script>
        </body>
    </html>
  <?php

  } else {

  ?>
  <!DOCTYPE html>
    <html lang="ja" dir="ltr">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
            <meta name="ROBOTS" content="noindex, nofollow, noarchive">
            <title>【418】I’m a teapot.</title>
            <style>*,:after,:before{-webkit-box-sizing:inherit;box-sizing:inherit}.btn,a.btn,button.btn{font-size:1.6rem;font-weight:700;line-height:1.5;position:relative;display:inline-block;padding:1rem 4rem;cursor:pointer;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;-webkit-transition:all .3s;transition:all .3s;text-align:center;vertical-align:middle;text-decoration:none;letter-spacing:.1em;color:#212529;border-radius:.5rem}a.btn--blue.btn--border-double{border:8px double #0090bb}a{color:#c71585;position:relative;display:inline-block}a,a:after{transition:.3s}a:after{position:absolute;bottom:0;left:50%;content:'';width:0;height:2px;background-color:#31aae2;transform:translateX(-50%)}a:hover:after{width:100%}a.btn--orange{color:#000;background-color:#eb6100;border-bottom:5px solid #b84c00}a.btn--orange:hover{margin-top:3px;color:#000;background:#f56500;border-bottom:2px solid #b84c00}a.btn--lime{color:#000;background-color:#0f0;border-bottom:5px solid #0f0}a.btn--lime:hover{margin-top:3px;color:#000;background:#0f0;border-bottom:2px solid #0f0}a.btn--shadow{-webkit-box-shadow:0 3px 5px rgba(0,0,0,.3);box-shadow:0 3px 5px rgba(0,0,0,.3)}a.btn--darkblue.btn--border-solid{border:2px solid #00008b;height:20px;width:auto;font-size:1rem!important;font-weight:300!important;line-height:1!important}a.btn--red.btn--border-inset{border:6px inset #b9000e;font-size:.8rem!important;font-weight:300!important;line-height:1!important}</style>
        </head>
        <body style="background-color:#6495ed;color:#080808;">
            <div id="animearea" style="position:fixed;left:0px;width:100%;height:100%;"></div>
            <div style="position:fixed;left:0px;top:0px;width:100%;background-color:transparent;z-index:1;">
                <br>
                <div align="center">
                    <span style="background-color:#e6e6fa;text:#363636;text-align:center;vertical-align:middle;">
                        <h1>【418】I’m a teapot.</h1>
                    </span>
                </div>
                <div align="center" style="width:95%;">
                    <hr size="1" color="#7fffd4">
                    <div align="center" style="width:70%;vertical-align:middle;text-align:center;">
                        <b>ご迷惑をおかけしてしまい、申し訳ございません。</b>
                        <br>
                        <b>
                            このエラーは、<a href="https://ja.wikipedia.org/wiki/Hyper_Text_Coffee_Pot_Control_Protocol" target="_blank">HTCPCP</a>
                            のページへHTTPリクエストを受け付けた場合に発生します。
                        </b>
                        <br>
                        <br>
                        <a href="/" class="btn btn--lime btn--cubic btn--shadow">ホームへ戻る</a>
                    </div>
                </div>
                <hr size="1" color="#7fffd4">
            </div>
            <script type="text/javascript" src="https://code.activetk.jp/particles.min.js"></script>
            <script>particlesJS("animearea",{particles:{number:{value:40,density:{enable:!0,value_area:200}},shape:{type:"star",stroke:{width:0,color:"#ffcc00"}},color:{value:"#ffffff"},opacity:{value:1,random:!1,anim:{enable:!1,speed:10,opacity_min:.1,sync:!1}},size:{value:5,random:!0,anim:{enable:!1,speed:40,size_min:.1,sync:!1}},line_linked:{enable:!0,distance:150,color:"#ffffff",opacity:.4,width:1},move:{speed:12,straight:!1,direction:"none",out_mode:"bounce"}},interactivity:{detect_on:"canvas",events:{onhover:{enable:!0,mode:"repulse"},onclick:{enable:!0,mode:"push"}},modes:{grab:{distance:400,line_linked:{opacity:1}},repulse:{distance:200},bubble:{distance:400,size:40,opacity:8,duration:2,speed:3},push:{particles_nb:4},remove:{particles_nb:2}}},retina_detect:!0,resize:!0});</script>
       </body>
    </html>
  <?php
  
  }
