<?php

$xhe_host ="127.0.0.1:7012";

// The following code is required to properly run XWeb Human Emulator
require("../../Templates/xweb_human_emulator.php");

// navigate to isap.vstu.by
$browser->navigate("https://ptm.vstu.by/");

$path_news="./res/ptm.txt";
$file_os->delete($path_news);

require_once("functions.php");
$dbg=true;
debug_mess(date("\[ d.m.y H:i:s\] ")." Скрипт начал работу");

$res_path="/res/";
$site = "https://ptm.vstu.by/";

	
for ($i=31;$i<61;)
	{
	$news=$textfile->read_file($res_path."ptm.txt",60) ;
	$inf = $anchor->get_inner_text_by_number($i);
	$textfile->add_string_to_file($res_path."ptm.txt","\n".$inf."\n",60);
	$i=$i+6;
	}		

// Quit
debug_mess(date("\[ d.m.y H:i:s\] ")." Скрипт закончил работу");
$app->quit();
?>