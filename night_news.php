<?php
/*=========================
本程式已完成
include的程式
第一階段的程式
==========================*/
	$read_SQL=mysql_query("SELECT * FROM night_news_sgin");//尋找資料表
	$row_id_01 = mysql_fetch_row($read_SQL);//搜索列
	$number=$row_id_01[0]+1;//取得第一個列的值（id）即上次搜索到的最終資料+1
	$array_unmber="";//設定紀錄用的陣列變數
	$max_number=$number+10;
	for ($i=$number; $i <$max_number ; $i++) { //將＄i設為上次搜索到的最終資料 並以每二十個資料為一循環
		$hear=get_headers("http://night.taivs.tp.edu.tw/news/u_news_v2.asp?id={E8B9CB6F-A0BE-4C02-A5EC-93DE91A606A5}&newsid=$i");//取得網站標頭（＄i為上次搜尋到的數字累加）
		$text=implode("", array_intersect(str_split($hear[2]), array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9)));//選取網站標頭的大小（$hear[2]） 並取得其大小（KB）
		if($test>=10800){
			$array_unmber[]=$i;//將（存在的）資料 存入$array_unmber的陣列中
			$max_number=$max_number+1;
		}
	}
	if($array_unmber !=""){//當$array_unmber存在時
		$last_number=max($array_unmber);//將陣列中最大值取出
		mysql_query(" UPDATE night_news_sgin SET id='$last_number'") ;//寫回SQL
		for ($t=0; $t <$last_number ; $t++) {
			$ch=curl_init();//curl宣告(第一階段)
			curl_setopt($ch, CURLOPT_URL,"http://night.taivs.tp.edu.tw/news/u_news_v2.asp?id={E8B9CB6F-A0BE-4C02-A5EC-93DE91A606A5}&newsid=$array_unmber[$t]");//網頁來源宣告(第一階段)
			curl_setopt($ch, CURLOPT_HEADER, false);//頁面標籤顯示(第一階段)
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//顯示頭信息？(第一階段)
			$web_data=curl_exec($ch);//取網頁原始碼(第一階段)
			$doc=phpQuery::newDocumentHTML($web_data);//將抓來的資料丟到phpQuery的code(第一階段)
			$do_pq=pq('table[class="table-B01-table"]',$doc);//取得內容頁面並將不需要的程式碼刪除(第一階段)
			$web_main_top=$do_pq->html();
			$web_main_top2=str_replace('<a href="u_news_v1.asp?id=%7BE8B9CB6F-A0BE-4C02-A5EC-93DE91A606A5%7D&amp;PageNo=&amp;skeyword=">回列表</a>','最新消息(夜)',$web_main_top);
			$web_main_end=str_replace('最新消息列表','',$web_main_top2);
			//print_r($web_main_end);
			mysql_query("INSERT INTO night_news (web_id,web) VALUES ('$array_unmber[$t]','$web_main_end') ") ;//寫入資料庫
			curl_close($ch);//結束php_curl
		}
	}