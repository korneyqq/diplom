<?php

$xhe_host ="127.0.0.1:7012";

// The following code is required to properly run XWeb Human Emulator
require("../../Templates/xweb_human_emulator.php");

// ////////// настройки скрипта///////////////////
// провер€емый сайт
$site="https://isap.vstu.by/";
// главна€ страница сайта
$main_page=$site."index.php";
// путь к шаблону дл€ создани€ карты сайта
$temp_path="/data/template.php";
// папка с результатами
$res_path="/res/";

// проверочна€ строка 
$error_404="ERROR 404 NOT FOUND";

// фильтр по словам в href
// задавать через , 
// какие ссылки не обрабатывать
$filter="/forum/,/images/,#,.jpg,.mp4,sitemap,.pdf";
// пежим отладки
$dbg=true;

// /////////////////// дополнительные модули /////////////////////
// функции 
require_once("functions.php");

// ///////////////////// script /////////////////////////////////////////////////////////
debug_mess(date("\[ d.m.y H:i:s\] ")." скрипт запустили");

// чистим предыдущие данные
$file_os->delete($res_path."sitemap.php");
$file_os->delete($res_path."sitemap.xml");
$file_os->delete($res_path."tmp.txt");

// создать sitemap.xml
create_xml($main_page);
// добавим главную страницу в массив
$pgl=new PageLinks();
$pgl->page=$main_page;
$pgl->link_info=$main_page;
$a_links=array($pgl);
// собираем и провер€ем 
for($k=0;$k<count($a_links);$k++)
{
    // получаем страницу
    $pg=trim($a_links[$k]->page);

      // провер€ем фильтры
    if(!check_filter($pg)) 
       continue;
  
    // переходим на сайт
    $browser->navigate($pg);

    // проверить на 404
    if(check_page_404($pg))
       continue;

    // добавим в sitemap.xml 
    if($k>0)
    {
       add_to_xml($pg);
       // запишем во временный файл
		$textfile->add_string_to_file($res_path."tmp.txt",$a_links[$k]->link_info."\n",60) ;
    }
	 // получим все href-ы на странице
	 $hrefs=$anchor->get_all_hrefs();
	 // преобразуем в массив
	 $hrefs=explode("<br>",$hrefs);

    // пройтись по всем hrefs и удалим лишнее
	for($ii=0; $ii<count($hrefs); $ii++)
	{
        $pg_href=trim($hrefs[$ii]);
		  // провер€ем внутренн€€ ли ссылка
		  if(strpos($pg_href,$site)===false)
					continue;
       
        // если ещЄ не провер€ли проверим
        // добавим провер€емую страницу
        if(!is_a_exists($pg_href))
        {
            // строка дл€ временного файла
            $pg_href1=str_replace($site,"",$pg_href);
				// строка дл€ записи во временный файл
				$in_txt=$anchor->get_inner_text_by_href($pg_href1,false);
				$str="";
				// если нет текста вместо него href 
				if($in_txt=="")
					$str="<a href=\"/$pg_href1\">$pg_href</a><br>";
				else
					$str="<a href=\"/$pg_href1\">$in_txt</a><br>";
				
				$pgl=new PageLinks();
				$pgl->page=$pg_href;
				$pgl->link_info=$str;
				$a_links[]=$pgl;   
        }
	}
}

// запишем в файл закрывающий тэг
$textfile->add_string_to_file($res_path."sitemap.xml","\n</urlset>\n",60);

// создадим sitemap.php файл 
$links=$textfile->read_file($res_path."tmp.txt",60) ;
$templ =$textfile->read_file($temp_path,60);
$templ=str_replace("{SITE_MAP}",$links,$templ);
// запишем файл
$textfile->write_file($res_path."sitemap.php",$templ,60) ;

debug_mess(date("\[ d.m.y H:i:s\] ")." скрипт закончил работу<br>");

// Quit
$app->quit();
?>