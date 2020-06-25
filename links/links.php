<?php

$xhe_host ="127.0.0.1:7012";

// The following code is required to properly run XWeb Human Emulator
require("../../Templates/xweb_human_emulator.php");

//настройки скрипта
// проверяемый сайт
$site="https://isap.vstu.by/";
// путь к файлу с плохими ссыками
$path_bad_links="/res/bad_links.txt";
// проверочная строка 
$error_404="Error 404: File Not Found";
// фильтр по словам в href
// задавать через ,
$filter="forum,doc,jpg,png,rar";

// режим отладки
$dbg=true;

// дополнительные модули
// функции 
require_once("functions.php");

// Начало скрипта
debug_mess(date("\[ m.d.y H:i:s\] ")." Cкрипт запустили");

// добавим главную страницу в массив
$pgl=new PageLinks();
$pgl->page=$site;
$pgl->link_info=$site;
$a_links=array($pgl);

// чистим данные
$file_os->delete($path_bad_links);

// собираем и проверяем 
for($k=0;$k<count($a_links);$k++)
{
    // получаем страницу
    $pg=$a_links[$k]->page;
      // проверяем фильтры
    if(!check_filter($pg))
       continue;
    
    // переходим на сайт
    $browser->navigate($pg);
    // проверить на 404
    if(check_page_404($a_links[$k]->link_info))
       continue;

	 // получим все href-ы на странице
	$hrefs=$anchor->get_all_hrefs();
	// преобразуем в массив
	$hrefs=explode("<br>",$hrefs);

    // пройтись по всем hrefs и удалим лишнее
	for($ii=0; $ii<count($hrefs); $ii++)
	{
        $pg_href=trim($hrefs[$ii]);
		  // проверяем внутренняя ли ссылка
		  if(strpos($pg_href,$site)===false)
					continue;

        // строка для временного файла
        $pg_href1=str_replace($site,"",$pg_href);
        // строка для запилси в фа
        $str="$pg;$pg_href;".$anchor->get_inner_text_by_href($pg_href1,false);
        // если ещё не проверяли проверим
        // добавим проверяемую страницу
        if(!is_a_exists($pg_href))
        {
			 $pgl=new PageLinks();
			 $pgl->page=$pg_href;
			 $pgl->link_info=$str;
			 $a_links[]=$pgl;   
        }
	}
}
debug_mess(date("\[ d.m.y H:i:s\] ")." Cкрипт закончил работу<br>");

// Quit
$app->quit();
?>