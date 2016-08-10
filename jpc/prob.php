<?php
class JPC_Prob
{
    /*
       問題の詳細を取得
     */
    function get_problem()
    {
	// 正常なポストをフィルタリング
	if (empty($_GET['id'])) return false;
	$this->id = (int)$_GET['id'];
	
	// 問題が存在しない
	if ($this->problem_exist($this->id) === false) return false;
	
	// 問題の詳細を取得
	$statement = $this->jpc->pdo->prepare('SELECT * FROM problem WHERE id=:id;');
	$statement->bindParam(':id', $this->id, PDO::PARAM_INT);
	$statement->execute();
	
	// 結果を取得
	$this->info = $statement->fetch(PDO::FETCH_ASSOC);
	$this->make_readable();
	
	return true;
    }
  
    /*
       見やすい形式に変更する
     */
    function make_readable()
    {
	// ミリ秒
	$this->info['lim_time'] = (string)($this->info['limit_time'] / 1000.0)."[sec]";

	// キロバイト
	if ($this->info['limit_memory'] >= 128) {
	    $this->info['lim_memory'] = (string)($this->info['limit_memory'] / 128.0)."[MB]";
	} else {
	    $this->info['lim_memory'] = (string)($this->info['limit_memory'])."[KB]";
	}
    }

    /*
       問題番号が存在するかを確認
     */
    function problem_exist($id)
    {
	// データベースに接続できていない
	if ($this->jpc->pdo === null) {
	    $this->jpc->log('warning', "データベースに接続できません。", false);
	    return false;
	}

	// IDを検索
	$statement = $this->jpc->pdo->prepare('SELECT id FROM problem WHERE id=:id;');
	$statement->bindParam(':id', $id, PDO::PARAM_INT);
	$statement->execute();
	if ($statement->rowCount() > 0) return true;
	return false;
    }

    /*
       コンストラクタ
     */
    function __construct($jpc)
    {
	$this->jpc = $jpc;
	$this->id = 0;
	$this->info = array();
    }
}
