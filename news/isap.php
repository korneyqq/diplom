<?php

$xhe_host ="127.0.0.1:7012";

// The following code is required to properly run XWeb Human Emulator
require("../../Templates/xweb_human_emulator.php");

// navigate to isap.vstu.by
$browser->navigate("https://isap.vstu.by/index.php/media-content/blog");
$anchor->click_by_inner_html("Больше новостей",false);

$path_news="./res/isap.txt";
$file_os->delete($path_news);

require_once("functions.php");
$dbg=true;
debug_mess(date("\[ d.m.y H:i:s\] ")." скрипт запустили");

$res_path="/res/";
$site = "https://isap.vstu.by/index.php/media-content/blog";

for ($n=317;$n<397;)
{
	$news=$textfile->read_file($res_path."isap.txt",60) ;
	
for ($i=72;$i<82;$i++)
	{
	$news=$textfile->read_file($res_path."isap.txt",60) ;
	$date = $element->get_inner_text_by_number($n);
	$inf = $anchor->get_inner_text_by_number($i);
	$textfile->add_string_to_file($res_path."isap.txt","\n".$date,60);
	$textfile->add_string_to_file($res_path."isap.txt","\n".$inf."\n",60);
	$n=$n+8;
	}		
}

debug_mess(date("\[ d.m.y H:i:s\] ")." скрипт закончил работу<br>");
// Quit
$app->quit();
?>