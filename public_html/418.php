<?php
header( "HTTP/1.1 418 I'm a teapot" );
if(strpos(request_path,"'") !== false){
    echo <<<'EOT'
    <!DOCTYPE html>
    <html lang="ja" dir="ltr">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
            <meta name="ROBOTS" content="noindex, nofollow">
            <meta name="favicon" content="https://www.activetk.jp/icon/index_32_32.ico">
            <title>【418】I’m a teapot.</title>
            <style>
                *,:after,:before {
                    -webkit-box-sizing: inherit;
                    box-sizing: inherit
                }
    
                .btn,a.btn,button.btn {
                    font-size: 1.6rem;
                    font-weight: 700;
                    line-height: 1.5;
                    position: relative;
                    display: inline-block;
                    padding: 1rem 4rem;
                    cursor: pointer;
                    -webkit-user-select: none;
                    -moz-user-select: none;
                    -ms-user-select: none;
                    user-select: none;
                    -webkit-transition: all .3s;
                    transition: all .3s;
                    text-align: center;
                    vertical-align: middle;
                    text-decoration: none;
                    letter-spacing: .1em;
                    color: #212529;
                    border-radius: .5rem
                }
    
                a.btn--blue.btn--border-double {
                    border: 8px double #0090bb
                }
    
                a {
                    color: #c71585;
                    position: relative;
                    display: inline-block
                }
    
                a,a:after {
                    transition: .3s
                }
    
                a:after {
                    position: absolute;
                    bottom: 0;
                    left: 50%;
                    content: '';
                    width: 0;
                    height: 2px;
                    background-color: #31aae2;
                    transform: translateX(-50%)
                }
    
                a:hover:after {
                    width: 100%
                }
    
                a.btn--orange {
                    color: #000;
                    background-color: #eb6100;
                    border-bottom: 5px solid #b84c00
                }
    
                a.btn--orange:hover {
                    margin-top: 3px;
                    color: #000;
                    background: #f56500;
                    border-bottom: 2px solid #b84c00
                }
    
                a.btn--lime {
                    color: #000;
                    background-color: #0f0;
                    border-bottom: 5px solid #0f0
                }
    
                a.btn--lime:hover {
                    margin-top: 3px;
                    color: #000;
                    background: #0f0;
                    border-bottom: 2px solid #0f0
                }
    
                a.btn--shadow {
                    -webkit-box-shadow: 0 3px 5px rgba(0,0,0,.3);
                    box-shadow: 0 3px 5px rgba(0,0,0,.3)
                }
    
                a.btn--darkblue.btn--border-solid {
                    border: 2px solid #00008b;
                    height: 20px;
                    width: auto;
                    font-size: 1rem!important;
                    font-weight: 300!important;
                    line-height: 1!important
                }
    
                a.btn--red.btn--border-inset {
                    border: 6px inset #b9000e;
                    font-size: .8rem!important;
                    font-weight: 300!important;
                    line-height: 1!important
                }
            </style>
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
                        <a href="https://www.goggle.com/" class="btn btn--lime btn--cubic btn--shadow" onclick=\'javascript:alert("☆*: .｡. o(≧▽≦)o .｡.:*☆");window.open("data:text/plain,YOU ARE AN IDIOT!");\' rel="noopener noreferrer">Goggleへ戻る</a>
                        <a href="bitcoin:bc1qgwvl678zvr7k5xegfcfs8mz46c34n7r9qckkqf" class="btn btn--orange btn--cubic btn--shadow">満州鉄道を爆破</a>
                    </div>
                </div>
                <hr size="1" color="#7fffd4">
            </div>
            <script type="text/javascript" src="https://code.activetk.jp/particles.min.js"></script>
            <script>
    
                particlesJS("animearea", {
                    particles: {
                        number: {
                            value: 40,
                            density: {
                                enable: true,
                                value_area: 200
                            }
                        },
                        shape: {
                            type: "star",
                            stroke: {
                                width: 0,
                                color: "#ffcc00"
                            }
                        },
                        color: {
                            value: "#ffffff"
                        },
                        opacity: {
                            value: 1,
                            random: false,
                            anim: {
                                enable: false,
                                speed: 10,
                                opacity_min: 0.1,
                                sync: false
                            }
                        },
                        size: {
                            value: 5,
                            random: true,
                            anim: {
                                enable: false,
                                speed: 40,
                                size_min: 0.1,
                                sync: false
                            }
                        },
                        line_linked: {
                            enable: true,
                            distance: 150,
                            color: "#ffffff",
                            opacity: 0.4,
                            width: 1
                        },
                        move: {
                            speed: 12,
                            straight: false,
                            direction: "none",
                            out_mode: "bounce"
                        }
                    },
                    interactivity: {
                        detect_on: "canvas",
                        events: {
                            onhover: {
                                enable: true,
                                mode: "repulse"
                            },
                            onclick: {
                                enable: true,
                                mode: "push"
                            }
                        },
                        modes: {
                            grab: {
                                distance: 400,
                                line_linked: {
                                    opacity: 1
                                }
                            },
                            repulse: {
                                distance: 200
                            },
                            bubble: {
                                distance: 400,
                                size: 40,
                                opacity: 8,
                                duration: 2,
                                speed: 3
                            },
                            push: {
                                particles_nb: 4
                            },
                            remove: {
                                particles_nb: 2
                            }
                        }
                    },
                    retina_detect: true,
                    resize: true
                });
            </script>
        </body>
    </html>
    EOT;
}
else{
    echo <<<'EOT'
    <!DOCTYPE html>
    <!--
    
      ActiveTK.jp / (c) 2023 ActiveTK.
    
      Server-Side Time: 0.0019409656524658s
      Cached Date: 2023-09-18 11:44:15+00:00
      ↑なんかこんな感じの表記ってカッコイイよね(厨二病という名の15歳)
    
    -->
    <html lang="ja" dir="ltr">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
            <meta name="ROBOTS" content="noindex, nofollow">
            <meta name="favicon" content="https://www.activetk.jp/icon/index_32_32.ico">
            <title>【418】I’m a teapot.</title>
            <style>
                *,:after,:before {
                    -webkit-box-sizing: inherit;
                    box-sizing: inherit
                }
    
                .btn,a.btn,button.btn {
                    font-size: 1.6rem;
                    font-weight: 700;
                    line-height: 1.5;
                    position: relative;
                    display: inline-block;
                    padding: 1rem 4rem;
                    cursor: pointer;
                    -webkit-user-select: none;
                    -moz-user-select: none;
                    -ms-user-select: none;
                    user-select: none;
                    -webkit-transition: all .3s;
                    transition: all .3s;
                    text-align: center;
                    vertical-align: middle;
                    text-decoration: none;
                    letter-spacing: .1em;
                    color: #212529;
                    border-radius: .5rem
                }
    
                a.btn--blue.btn--border-double {
                    border: 8px double #0090bb
                }
    
                a {
                    color: #c71585;
                    position: relative;
                    display: inline-block
                }
    
                a,a:after {
                    transition: .3s
                }
    
                a:after {
                    position: absolute;
                    bottom: 0;
                    left: 50%;
                    content: '';
                    width: 0;
                    height: 2px;
                    background-color: #31aae2;
                    transform: translateX(-50%)
                }
    
                a:hover:after {
                    width: 100%
                }
    
                a.btn--orange {
                    color: #000;
                    background-color: #eb6100;
                    border-bottom: 5px solid #b84c00
                }
    
                a.btn--orange:hover {
                    margin-top: 3px;
                    color: #000;
                    background: #f56500;
                    border-bottom: 2px solid #b84c00
                }
    
                a.btn--lime {
                    color: #000;
                    background-color: #0f0;
                    border-bottom: 5px solid #0f0
                }
    
                a.btn--lime:hover {
                    margin-top: 3px;
                    color: #000;
                    background: #0f0;
                    border-bottom: 2px solid #0f0
                }
    
                a.btn--shadow {
                    -webkit-box-shadow: 0 3px 5px rgba(0,0,0,.3);
                    box-shadow: 0 3px 5px rgba(0,0,0,.3)
                }
    
                a.btn--darkblue.btn--border-solid {
                    border: 2px solid #00008b;
                    height: 20px;
                    width: auto;
                    font-size: 1rem!important;
                    font-weight: 300!important;
                    line-height: 1!important
                }
    
                a.btn--red.btn--border-inset {
                    border: 6px inset #b9000e;
                    font-size: .8rem!important;
                    font-weight: 300!important;
                    line-height: 1!important
                }
            </style>
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
                        <a href="/home" class="btn btn--lime btn--cubic btn--shadow">ホームへ戻る</a>
                        <a href="/report?data=%7B%22Type%22%3A%22HTTP.ERR_NOT_FOUND%22%2C%22Title%22%3A%22%5Cu3010418%5Cu3011I%5Cu2019m%20a%20teapot.%22%2C%22Path%22%3A%22%5C%2F418%22%2C%22GET%22%3A%22%7B%5C%22request%5C%22%3A%5C%22418%5C%22%7D%22%7D" class="btn btn--orange btn--cubic btn--shadow">エラーを報告</a>
                    </div>
                </div>
                <hr size="1" color="#7fffd4">
                <div align="center">
                    <p>
                        <a href="/home" style="color:#00ff00 !important;">ホーム</a>
                        ・ 
    <a href="/about" style="color:#0403f9 !important;">本サイトについて</a>
                        ・ 
    <a href="/license" style="color:#ffa500 !important;">利用規約</a>
                        ・ 
    <a href="/privacy" style="color:#ff00ff !important;">プライバシー</a>
                        ・ 
    <a href="https://profile.activetk.jp/" style="color:#0403f9 !important;">開発者</a>
                        (c) 2023 ActiveTK.
                    </p>
                    <div class='Msg4PC'>
                        <p>
                            Onion Mirror: 
    
                            <a href='http://activetkqz22r3lvvvqeos5qnbrwfwzjajlaljbrqmybsooxjpkccpid.onion/'>
                                <span style='color:#000000 !important;'>
                                    http://<b>ActiveTK</b>
                                    qz22r3lvvvqeos5qnbrwfwzjajlaljbrqmybsooxjpkccpid.onion
                                </span>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            <script type="text/javascript" src="https://code.activetk.jp/particles.min.js" nonce="5a55h1c9fz110aa89693a5a495x7136d3e92"></script>
            <script nonce="5a55h1c9fz110aa89693a5a495x7136d3e92">
    
                particlesJS("animearea", {
                    particles: {
                        number: {
                            value: 40,
                            density: {
                                enable: true,
                                value_area: 200
                            }
                        },
                        shape: {
                            type: "star",
                            stroke: {
                                width: 0,
                                color: "#ffcc00"
                            }
                        },
                        color: {
                            value: "#ffffff"
                        },
                        opacity: {
                            value: 1,
                            random: false,
                            anim: {
                                enable: false,
                                speed: 10,
                                opacity_min: 0.1,
                                sync: false
                            }
                        },
                        size: {
                            value: 5,
                            random: true,
                            anim: {
                                enable: false,
                                speed: 40,
                                size_min: 0.1,
                                sync: false
                            }
                        },
                        line_linked: {
                            enable: true,
                            distance: 150,
                            color: "#ffffff",
                            opacity: 0.4,
                            width: 1
                        },
                        move: {
                            speed: 12,
                            straight: false,
                            direction: "none",
                            out_mode: "bounce"
                        }
                    },
                    interactivity: {
                        detect_on: "canvas",
                        events: {
                            onhover: {
                                enable: true,
                                mode: "repulse"
                            },
                            onclick: {
                                enable: true,
                                mode: "push"
                            }
                        },
                        modes: {
                            grab: {
                                distance: 400,
                                line_linked: {
                                    opacity: 1
                                }
                            },
                            repulse: {
                                distance: 200
                            },
                            bubble: {
                                distance: 400,
                                size: 40,
                                opacity: 8,
                                duration: 2,
                                speed: 3
                            },
                            push: {
                                particles_nb: 4
                            },
                            remove: {
                                particles_nb: 2
                            }
                        }
                    },
                    retina_detect: true,
                    resize: true
                });
            </script>
            <script defer src="https://static.cloudflareinsights.com/beacon.min.js/v8b253dfea2ab4077af8c6f58422dfbfd1689876627854" integrity="sha512-bjgnUKX4azu3dLTVtie9u6TKqgx29RBwfj3QXYt5EKfWM/9hPSAI/4qcV5NACjwAo8UtTeWefx6Zq5PHcMm7Tg==" nonce="5a55h1c9fz110aa89693a5a495x7136d3e92" data-cf-beacon='{"rayId":"80895601bc3e8d1c","version":"2023.8.0","r":1,"token":"9e7e1c71e640433f86bfdbc2a80187a0","si":100}' crossorigin="anonymous"></script>
        </body>
    </html>
    EOT;
}
?>
