var HOST = "127.0.0.1";
var PORT = "8080";

$(function() {
    var data = {};
    ws = new WebSocket("ws://" + HOST + ":" + PORT + "/");

    /*
      接続成功
     */
    ws.onopen = function() {
	// 送信値を取得
	var lang = $('#lang').val();
	var code = $('#code').val();
	var id = $('#id').val();
	var iv = $('#iv').val();
	var user = $('#user').val();
	// 通信を開始
	var payload = {
	    'lang': lang,
	    'code': code,
	    'id': parseInt(id, 10),
	    'iv': iv,
	    'user': user
	};
	ws.send(JSON.stringify(payload));
    };

    /*
      接続エラー
    */
    ws.onerror = function() {
	// エラーポップアップを表示
	$('#error').prop("style", "display: block;");
	$('#error-message').html("サーバーとの接続に失敗しました。");
    };

    /*
      受信
    */
    ws.onmessage = function(e) {
	var info = JSON.parse(e.data);
	if ('attempt' in info) {
	    switch(info['attempt']) {

	    case -1: // こちらのデータが間違っていた
		$('#error').prop("style", "display: block;");
		$('#error-message').html("不正なデータを要求しました。");
		break;

	    default: // プログラム実行中
		$("#icon" + info['attempt'].toString()).addClass("glyphicon glyphicon-hourglass");
		break;
		
	    }
	} else if ('complete' in info) {
	    // 全て終了
	    $('#complete').prop("style", "display: block;");
	} else if ('success' in info) {
	    // 入力に成功
	    $("#icon" + info['success'].toString()).removeClass().addClass("glyphicon glyphicon-ok").css('color', 'green');
	} else if ('failure' in info) {
	    // 入力に失敗
	    $("#icon" + info['failure'].toString()).removeClass().addClass("glyphicon glyphicon-remove").css('color', 'red');
	} else if ('compile' in info) {
	    // コンパイル結果を表示
	    $('#compile').html(escapeHtml(info['compile']));
	} else if ('error' in info) {
	    // エラーが発生
	    $('#error').prop("style", "display: block;");
	    $('#error-message').html(escapeHtml(info['error']));
	} else {
	    // 不正なデータを受信
	    $('#error').prop("style", "display: block;");
	    $('#error-message').html("無効なデータを受信しました。");
	}
    };
});

function escapeHtml(text) {
  var map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}
