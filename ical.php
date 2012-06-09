<?php
require_once("apifunc.php");//機能定義ファイル読み込み

//$ptcUrl = "http://www.pasonatech.co.jp/event/rss_event.jsp";
$ptcUrl = "http://rss.pasonatech.co.jp/rss2/465";

// ■並列通信用マルチハンドルを用意■
$mh = curl_multi_init();

//通信先ごとにCurl Handleを作り、それを $mh にaddしていく
//（PasonaTech Event RSSY）
$ch_ptcurl = curl_init($ptcUrl);
curl_setopt($ch_ptcurl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch_ptcurl, CURLOPT_TIMEOUT, 5);
curl_multi_add_handle($mh, $ch_ptcurl);

// せーので複数の通信を同時実行。whileで全て返ってくるのを待ちます  
do { curl_multi_exec($mh, $running); } while ( $running );

// 個々のXMLは、それぞれのCurl Handleを指定することで取得できる  
$xml_ptcurl  = curl_multi_getcontent($ch_ptcurl);

// 後始末  
curl_multi_remove_handle($mh, $ch_ptcurl);
curl_close($ch_ptcurl);

curl_multi_close($mh);
// ■並列通信ここまで■


// ■各社データを配列変数へ格納■
// ■PasonaTech Event RSS ■
$xml = simplexml_load_string ($xml_ptcurl);
	$hits = $xml->channel->item;
foreach ($hits as $hit) {
$title[] = $hit->title;
$linkurl[] = h($hit->link);
$description[] = h($hit->description);
$pubdate[] = h($hit->pubDate);
$location[] = h($hit->category[2]);
}
//header("Content-Type: text/Calendar");
//header("Content-Disposition: inline; filename=snickerjp.ics");
require_once './iCalcreator.class.php';
$v = new vcalendar( array( 'unique_id' => 'snicker-jp.info' ));
                                                // initiate new CALENDAR
$v->setProperty( 'method'
               , 'PUBLISH' );
$v->setProperty( 'X-WR-CALNAME'
               , '勝手にPTイベント(β)' );          // set some X-properties, name, content.. .
$v->setProperty( 'X-WR-TIMEZONE'
               , 'Asia/Tokyo' );
$v->setProperty( 'X-WR-CALDESC'
               , 'パソナテックのイベントRSSをiCal形式に変換しました！（※現在テストUP中です。）' );
foreach ($title as $key => $value) {
//$pattern = '/[0-9][0-9][0-9][0-9]\\/[0-9][0-9]\\/[0-9][0-9]\(.*\)/';
$pattern = '/[0-9][0-9][0-9][0-9]\\/[0-9][0-9]\\/[0-9][0-9].*[0-9][0-9]:.*:[0-9][0-9]/';
preg_match($pattern, $description[$key], $matches);
$split_pattern = '/[\s-\/:]+|\(.*\)[\s]+/';
$split_pattern = '/[\D]+|\(.*\)[\s]+/';
$split_matches = preg_split($split_pattern, $matches[0]);

$pattern2 = '/\\[.*\\][\s]+/';
preg_match($pattern2, $title[$key], $matches2);
$sym = array("[", "]");
$matches2 = str_replace($sym, "", $matches2[0]);

$e = & $v->newComponent( 'vevent' );           // initiate a new EVENT
$e->setProperty( 'DTSTART'
               , $split_matches[0], $split_matches[1], $split_matches[2], $split_matches[3], $split_matches[4], 00, "+090000" );   // 24 dec 2007 19.30
$e->setProperty( 'DTEND'
               , $split_matches[0], $split_matches[1], $split_matches[2], $split_matches[5], $split_matches[6], 00, "+090000" );   // 24 dec 2007 19.30
$e->setProperty( 'CREATED'
               , $split_matches[0], $split_matches[1], $split_matches[2], $split_matches[3], $split_matches[4], 00, "+090000" ); 
/*
$e->setProperty( 'description'
               , $description[$key] );    // describe the event
*/
$e->setProperty( 'description'
               , $linkurl[$key] );    // describe the event
$e->setProperty( 'LAST-MODIFIED'
               , $split_matches[0], $split_matches[1], $split_matches[2], $split_matches[3], $split_matches[4], 00, "+090000" ); 
$e->setProperty( 'location'
               , $matches2 );                     // locate the event
$e->setProperty( 'STATUS'
               , 'CONFIRMED' ); //
$e->setProperty( 'SUMMARY'
               , $title[$key] ); // SUMMARY

}
/* alt. production */
$v->returnCalendar();                          // generate and redirect output to user browser
/* alt. dev. and test */
//echo nl2br( $v->createCalendar()) ;            // generate and get output in string, for testing?
//echo "<br />\n\n";
//print_r($matches2); 
?>
