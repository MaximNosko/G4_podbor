<?php
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");//для предотвращения кэширования

$RAZD=";";//разделитель, который используется в файле
$txt=file_get_contents("m.csv");
ошибка
//первая строка - названия полей, должны быть уникальными
//вторая - типы данных, должны быть одним из:
//	Целое
//	Дробное
//	Выбор (если это логическое или перечень)
//	Изображение - путь к файлу
//	Название - ключевые поля, уникальная комбинация которых используется для создания выборки
//третья - единицы измерения
//четвёртая и далее - данные
$stroki=explode("\n",$txt);//разделение файла на строки
$rez=array();//общий массив, который превращается в json и передаётся клиенту
$rez["z"]=explode($RAZD,$stroki[0]);//заголовки таблицы
foreach($rez["z"] as $i => $z)
{
	$rez["z"][$i]=trim($z);
}
$rez["z"][0]=substr($rez["z"][0],3);//для уничтожения символа 65279 при использовании UTF-8 с BOM, что удобно для редактирования в Excel
$rez["types"]=explode($RAZD,$stroki[1]);//типы данных
foreach($rez["types"] as $i => $z)
{
	$rez["types"][$i]=trim($z);
}
$rez["ed"]=explode($RAZD,$stroki[2]);//единицы измерения
foreach($rez["ed"] as $i => $z)
{
	$rez["ed"][$i]=trim($z);
}
$rez["data"]=array();//сводные данные таблицы
for($i=0;$i<count($rez["types"]);$i++)
{
	$tt=trim($rez["types"][$i]);//Текущий тип
	$tz=trim($rez["z"][$i]);//Текущий заголовок
	$ted=trim($rez["ed"][$i]);//Текущая единица измерения
	if(($tt==="Название")||($tt==="Изображение")||($tt==="Выбор"))
	{
		$rez["data"][trim($tz)]=array("type"=>$tt,"nabor"=>array(),"ed"=>$ted);//для списков будет определён массив уникальных значений
	}
	else if(($tt==="Целое")||($tt==="Дробное"))
	{
		$rez["data"][trim($tz)]=array("type"=>$tt,"min"=>null,"max"=>null,"ed"=>$ted);//для чисел будут определены минимум и максимум
	}
	else
	{
		$rez["data"][trim($tz)]=array("type"=>"");//для данных неверного типа
	}
}
$rez["table"]=array();//таблица со строками, без агрегации
$rez["nazv"]=array();//Уникальные комбинации названий
for($i=3;$i<count($stroki)-1;$i++)
{
	$stroka=explode($RAZD,$stroki[$i]);
	$nazv_tm=array();
	for($i2=0;$i2<count($stroka);$i2++)
	{
		$tt=trim($rez["types"][$i2]);//Текущий тип
		$tz=trim($rez["z"][$i2]);//Текущий заголовок
		$ted=trim($rez["ed"][$i2]);
		$z=trim($stroka[$i2]);
		if(($rez["data"][$tz]["type"]==="Название")||($rez["data"][$tz]["type"]==="Выбор")||($rez["data"][$tz]["type"]==="Изображение"))
		{
			if(!in_array($z,$rez["data"][$tz]["nabor"]))
			{
				$rez["data"][$tz]["nabor"][]=$z;
			}
			if($rez["data"][$tz]["type"]==="Название")
			{
				$nazv_tm[$tz]=$z;
			}
		}
		else if(($rez["data"][$tz]["type"]==="Целое")||($rez["data"][$tz]["type"]==="Дробное"))
		{
			if($rez["data"][$tz]["type"]==="Целое")
			{
				$z=intval($z);
			}
			else
			{
				$z=str_replace(",", ".",$z);
				$z=floatval($z);
			}
			if($rez["data"][$tz]["min"]===null)
			{
				$rez["data"][$tz]["min"]=$z;
			}
			else if($z<$rez["data"][$tz]["min"])
			{
				$rez["data"][$tz]["min"]=$z;
			}
			if($rez["data"][$tz]["max"]===null)
			{
				$rez["data"][$tz]["max"]=$z;
			}
			else if($z>$rez["data"][$tz]["max"])
			{
				$rez["data"][$tz]["max"]=$z;
			}
		}
		$stroka[$i2]=array("type"=>$tt,"z"=>$z,"ed"=>$ted);
	}
	if(!in_array($nazv_tm,$rez["nazv"]))
	{
		$rez["nazv"][]=$nazv_tm;
	}
	$rez["table"][]=array_combine($rez["z"], $stroka);
}
foreach($rez["nazv"] as $i => $z)//Добавляем массивы для хранения данных по комбинациям названий
{
	$rez["nazv"][$i]=array("name"=>$z,"data"=>array(),"table"=>array());
}
foreach($rez["nazv"] as $i => $tn)
{
	$t_nazv_table=array();
	$t_nazv_data=array();
	$t_nazv_col=array();
	foreach($rez["z"] as $i_z => $z_z)
	{
		if(($rez["types"][$i_z]==="Целое")||($rez["types"][$i_z]==="Дробное"))
		{
			$t_nazv_data[$z_z]=array("type"=>$rez["types"][$i_z],"min"=>null,"max"=>null,"ed"=>$rez["ed"][$i_z]);
		}
		else if(($rez["types"][$i_z]==="Название")||($rez["types"][$i_z]==="Изображение")||($rez["types"][$i_z]==="Выбор"))
		{
			$t_nazv_data[$z_z]=array("type"=>$rez["types"][$i_z],"nabor"=>array(),"ed"=>$rez["ed"][$i_z]);
		}
		$t_nazv_col[$z_z]=array("type"=>$rez["types"][$i_z],"cols"=>array());
	}
	foreach($rez["table"] as $stroka)
	{
		//Определяем, принадлежит ли строка текущей уникальной комбинации названий
		$f=true;
		foreach($tn["name"] as $tn_k => $tn_z)
		{
			if($stroka[$tn_k]["z"]!==$tn_z)
			{
				$f=false;
			}
		}
		if($f)
		{
			$t_nazv_table[]=$stroka;
			foreach($stroka as $i2 => $z)
			{
				$t_nazv_col[$i2]["cols"][]=array("z"=>$z["z"],"new"=>null,"span"=>null,"type"=>$t_nazv_data[$i2]["type"],"ed"=>$t_nazv_data[$i2]["ed"]);
				if(($t_nazv_data[$i2]["type"]==="Целое")||($t_nazv_data[$i2]["type"]==="Дробное"))
				{
					$tz=$z["z"];
					if($t_nazv_data[$i2]["min"]===null)
					{
						$t_nazv_data[$i2]["min"]=$tz;
					}
					else if($tz<$t_nazv_data[$i2]["min"])
					{
						$t_nazv_data[$i2]["min"]=$tz;
					}
					if($t_nazv_data[$i2]["max"]===null)
					{
						$t_nazv_data[$i2]["max"]=$tz;
					}
					else if($tz>$t_nazv_data[$i2]["max"])
					{
						$t_nazv_data[$i2]["max"]=$tz;
					}
				}
				else if(($t_nazv_data[$i2]["type"]==="Изображение")||($t_nazv_data[$i2]["type"]==="Название")||($t_nazv_data[$i2]["type"]==="Выбор"))
				{
					if(!in_array($z["z"],$t_nazv_data[$i2]["nabor"]))
					{
						$t_nazv_data[$i2]["nabor"][]=$z["z"];
					}
				}
			}
		}
	}
	foreach($t_nazv_col as $t_col=>$cols)//определение повторяющихся значений в рамках одной комбинации названий
	{
		$pred=null;
		$last_new_index=null;
		$kvo=1;
		foreach($cols["cols"] as $ti=>$tz)
		{
			if(($pred===null)||($pred!==$tz["z"]))
			{
				if($last_new_index!==null)
				{
					$t_nazv_col[$t_col]["cols"][$last_new_index]["span"]=$kvo;
				}
				$last_new_index=$ti;
				$pred=$tz["z"];
				$t_nazv_col[$t_col]["cols"][$ti]["new"]=true;
				$kvo=0;
			}
			else
			{
				$t_nazv_col[$t_col]["cols"][$ti]["new"]=false;
			}
			$kvo++;
		}
		$t_nazv_col[$t_col]["cols"][$last_new_index]["span"]=$kvo;
	}
	$rez["nazv"][$i]["table"]=$t_nazv_table;
	$rez["nazv"][$i]["data"]=$t_nazv_data;
	$rez["nazv"][$i]["col"]=$t_nazv_col;
}
echo json_encode($rez);
//var_dump();
?>
