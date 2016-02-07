<?
	include"phpQuery-onefile.php";//引入phpQuery的code
	$mysql_sign=mysql_connect("localhost","1234","1234","myDB");//登入SQL
	$mysql_check=mysql_select_db("Daan-X",$mysql_sign);//選擇資料厙
	mysql_query("SET NAMES utf8");//資料庫編碼設定
	include"night_news.php";//引入"寫入資料庫的頁面內容1"
	include"night_learn.php";//引入"寫入資料庫的頁面內容2"
	include"night_race.php";//引入"寫入資料庫的頁面內容3"
	mysql_close($mysql_sign);//登出SQL
?>