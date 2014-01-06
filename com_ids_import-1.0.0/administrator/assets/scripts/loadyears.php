<?php 
//******************************************************************************
//* $Id:: loadthemes.php 107 2012-07-24 simonc			             		  	$
//* $Revision:: 107                                                    			$ 
//* $Author:: simonc                                                   			$
//* $LastChangedDate:: 2012-07-24									   			$
//******************************************************************************/

$type=$_REQUEST["type"];
$server=$_REQUEST["server"];
$saved_year = $_REQUEST["year"];

$api_url ="http://api.ids.ac.uk/openapi/".$server."/count/documents/publication_year?_token_guid=".$type."&_accept=application/xml";
$xml = @simplexml_load_file($api_url);
if($xml)
{
	$content_elements = $xml->xpath('/root/publication_year_count/list-item');
	if($content_elements)
	{  
		
		foreach ($content_elements as $list_item)
		{
			$year = (array) $list_item->object_name;
			$years[] = $year[0];
		}

		//asort($years); //low to high
		arsort($years); //high to low
		echo "<option value=''>Select Year</option>";
		foreach ($years as $year)
		{
			if($year == $saved_year)
	        	echo "<option value=\"".$year ."\" selected>".$year . "</option>";
	        else
	        	echo "<option value=\"".$year ."\">".$year . "</option>";
		}
	}
}	
 ?>