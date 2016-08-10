var HOST = "127.0.0.1";
var PORT = "8080";

$(function() {
    var data = {};
    ws = new WebSocket("ws://" + HOST + ":" + PORT + "/");

    ws.onopen = function() {
	// 値を取得
	var lang = $('#lang').val();
	var code = $('#code').val();
	var id = $('#id').val();
	// 通信を開始
	var payload = {
	    'lang': lang,
	    'code': code,
	    'id': parseInt(id, 10)
	};
	ws.send(JSON.stringify(payload));
    };

    /* 接続エラー */
    ws.onerror = function() {
	// エラーポップアップを表示
	$('#error').prop("style", "display: block;");
	$('#error-message').html("サーバーとの接続に失敗しました。");
    };

    /* 受信 */
    ws.onmessage = function(e) {
	var info = JSON.parse(e.data);
	// attemptを確認
	if ('attempt' in info) {
	    switch(info['attempt']) {
	    case -1:
		// エラーポップアップを表示
		$('#error').prop("style", "display: block;");
		$('#error-message').html("不正なデータを要求しました。");
		break;
	    }
	} else {
	    // エラーポップアップを表示
	    $('#error').prop("style", "display: block;");
	    $('#error-message').html("不正なデータを受信しました。");
	}
    };
});


