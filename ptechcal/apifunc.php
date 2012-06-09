<?php
/*
WebサービスAPIアプリ用functionファイル Ver.1.2（2010/03/26）
by　WebサービスAPI's　wakcey　http://web-service-api.jp/

■使い方
WebサービスAPIを利用したアプリケーションを開発するときに共通して利用しそうな関数をこちらでまとめています。
それぞれの関数を呼び出します。
*/

/*  Copyright 2009-2010 wackey

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


// ■htmlspecialchars：HTMLを無効化します
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}

// ■Amazon APIリクエスト用：リクエストURL組み立てするときに使用
function urlencode_rfc3986($str)
{
    return str_replace('%7E', '~', rawurlencode($str));
}

// ■セレクトメニュー作成：<select>タグによるメニュー描画、パラメータから選択されているものを選択状態にする。
// select name、select option配列、選択、オプション
function DrawSelectMenu($name, $source_arr, $select_value, $option) {
	echo "<select name=\"$name\">$option";
	foreach($source_arr as $key => $value) {
		echo "<option value=\"$key\"";
		if ($key == $select_value) {
			echo " selected";
		}
		echo ">$value</option>";
	}
	echo "</select>";
}

// ■ナビゲーションリンク作成：現在ページ数、総ページ数、検索条件パラメータ、検索結果数を受け取りリンクを出力
function pagenavilink($genzai_page, $totalpage, $params, $resultcount) {

if (!$resultcount==0) { // 検索結果があれば（0件じゃなければ）
	echo "検索結果" . number_format($resultcount) . "件<br />";// 検索結果数表示
		if(!isset($genzai_page)) {// もし現在のページ数が設定されてないなら
			$genzai_page = 1;
		}
		if($genzai_page > 1) {// もし現在のページ数が「１」より大きい、つまり２以上であれば前のページに戻るリンクを書く
			$page = $genzai_page - 1;
			echo "<a href=\"" . $params . "&page=$page\">前へ</a>&nbsp;";
		}
		if($genzai_page < $totalpage) {// もし現在のページ数が総ページ数より小さいのであれば次のページに進むリンクを書く
			$page = $genzai_page + 1;
			echo "<a href=\"" . $params . "&page=$page\">次へ</a>";
		}
}// if !$resultcount

//検索結果がゼロの時は何もしない「検索結果０件」という文字も表示しない

}


// ■じゃらんアフィリエイトリンク作成関数PC用(リンクシェア)
function jalan_pc_aflink($jalanurl,$lstoken) {
$url="http://feed.linksynergy.com/createcustomlink.shtml?token=$lstoken&mid=24834&murl=$jalanurl";
$link = file_get_contents($url);
return $link;
}

// ■じゃらんアフィリエイトリンク作成関数ケータイ用(リンクシェア)
function jalan_ktai_aflink($jalanurl) {
$url="http://feed.linksynergy.com/createcustomlink.shtml?token=$lstoken&mid=24835&murl=$jalanurl";
$link = file_get_contents($url);
return $link;
}

?>
