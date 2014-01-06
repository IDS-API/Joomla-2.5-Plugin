<?php 
//******************************************************************************
//* $Id:: loadthemes.php 107 2012-07-24 simonc			             		  	$
//* $Revision:: 107                                                    			$ 
//* $Author:: simonc                                                   			$
//* $LastChangedDate:: 2012-07-24									   			$
//******************************************************************************/
$type=$_REQUEST["type"];
$server=$_REQUEST["server"];
$saved_themes = explode(',',$_REQUEST["saved_themes"]);

$api_url ="http://api.ids.ac.uk/openapi/".$server."/get_all/themes/?extra_fields=level&num_results=1200&_token_guid=".$type."&_accept=application/xml";
$xml = @simplexml_load_file($api_url);
if($xml)
{
	$content_elements = $xml->xpath('/root/results/list-item');
	if($content_elements)
	{  
		foreach ($content_elements as $list_item)
		{
		 $title = $list_item->title;
		 $level = $list_item->level;
		 $object_id = $list_item->object_id;
		   
		   if ($level==1)
                  {
                  	if(in_array($object_id, $saved_themes))
			        	echo "<option  style=\"background-color:#eeeeee;\" value=\"".$object_id ."\" selected>".$title . "</option>";
			        else
			        	echo "<option  style=\"background-color:#eeeeee;\" value=\"".$object_id ."\">".$title . "</option>";
                 
                  }
                  elseif ($level==2)
                  {
                     
                	if(in_array($object_id, $saved_themes))
			       		echo "<option value=\"".$object_id ."\" selected>&nbsp;&nbsp;".$title . "</option>";
			        else
			        	echo "<option value=\"".$object_id ."\">&nbsp;&nbsp;".$title . "</option>";
		          }
				  else 
				  {
				  echo "";
				  }
                  /*elseif ($level==3)
                  {
                  echo "<option value=\"".$title ."\">&nbsp;&nbsp;&nbsp;&nbsp;".$title . "</option>";   
                  }
                  elseif ($level==4)
                  {
                  echo "<option value=\"".$title ."\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$title . "</option>";   
                  }*/
		}
	}
}	
 ?>