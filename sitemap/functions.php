<?php
// ссылка на страницу и информация по ней
class PageLinks
{
	// страница
	var $page;
	// информация по ней
	var $link_info;
}

// создать xml 
function create_xml($url)
{
	global $res_path,$textfile;
	
	$xml_str="<?xml version=\"1.0\" encoding=\"UTF-8\"?>
	
	<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">
	
	<url>
	<loc>$url</loc>
	<changefreq>daily</changefreq>
	<priority>1.0</priority>
	</url>\n\n"; 
	
	// запишем файл
	$textfile->write_file($res_path."sitemap.xml",$xml_str,60) ;
}

// добавит в xml url
function add_to_xml($url)
{
	global $res_path,$textfile;
	// получим приоритет страницы
   $prior=get_priority($url);
	$xml_str="";
	if($prior=="0.5")
	{
		$xml_str="<url>
		<loc>$url</loc>
		</url>\n\n";
   }
	else
	{
		$xml_str="<url>
		<loc>$url</loc>
		<changefreq>".get_changefreq($prior)."</changefreq>
		<priority>$prior</priority>
		</url>\n\n";
	}
	
   // запишем в файл
	$textfile->add_string_to_file($res_path."sitemap.xml",$xml_str,60) ;
}
// получить приоритет для страницы
function get_priority($url)
{
    global $site;
    // убираем домен из урл 
    $url=str_replace($site,"",$url);
    // получить степень вложености страницы
    $num=1;
    get_num_inc($num,$url);

    if($num==1)
       return "0.8";
    else if($num==2)
       return "0.6";
    else
       return "0.5";
}
// степень вложености страницы
function get_num_inc(&$num,$url,$st_ind=0)
{  
    // получить индекс
    $ind=strpos($url,"/",$st_ind);
    //echo $ind." ".$num." for ".$url."<br>";
    if($ind!==false)
    {
        $num=$num+1;
        return get_num_inc($num,$url,$ind+1);
    }
  
    return true;
}
// получить частоту обновления для страницы
function get_changefreq($prior)
{
	  switch($prior)
	  {
          case "0.8": return "weekly";
          case "0.6": return "monthly";
	  }
}

// проверить есть ли в массиве страница
function is_a_exists($page)
{
   global $a_links;

   if(count($a_links)<=1)
       return false;

   // пройдёмся по массиву и проверим есть ли заданный элемент
   for($i=0;$i<count($a_links);$i++)
   {
       if($a_links[$i]->page==$page)
         return true;  
   }

   return false;
}

// get string by prefix
function get_string($str1, $pr1, $pr2, $ind_st=0)
{
     //echo $str1."<br>";
     $ind1 = strpos ($str1,$pr1,$ind_st);
    // echo "index 1 ".$ind1."<br>";
    if($ind1===false)
      return "";
     
     $ind2 = strpos ($str1,$pr2,$ind1);
    //echo "index 2 ".$ind2."<br>";
     if($ind2===false)
        return "";

     $sres = substr($str1,$ind1+strlen($pr1), $ind2-$ind1-strlen($pr1));
     //echo $sres; 

    return trim($sres); 
}
// проверить текущюю страницу заданный текст
function check_page_404($str)
{
   global $error_404,$webpage,$path_bad_links,$textfile;

   if(!strpos($webpage->get_body(),$error_404))
      return false;
   
   // добавить ссылку во временный файл
   //$textfile->add_string_to_file($path_bad_links,trim($str)."\n",60) ;
     
  return true;
}
// проверить строку на фильтр
function check_filter($page)
{
	global $filter;
	
	if($filter=="")
	 return true;
	
	// разобрать фильтры в массив
	$a_flt=explode(",",$filter);
	
   for($i=0;$i<count($a_flt);$i++)
   {
		if(strpos($page,$a_flt[$i]))
			return false;
   }
  
   return true;
}

// show message in debug panel
function  debug_mess($mess)
{
   global $dbg;
   // debug message
   if($dbg)
      echo $mess."<br>";
}
?>