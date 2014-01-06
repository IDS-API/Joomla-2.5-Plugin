<?php
//******************************************************************************
//* $Id:: idsplugin.php 06-05-2013 @iLabAfrica                 $
//* $Revision:: 2                                                      $ 
//* $Author:: @iLabAfrica                                                   $
//******************************************************************************/


// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Import library dependencies
jimport('joomla.plugin.plugin');
jimport('joomla.filesystem.file');
jimport('joomla.html.parameter');
 
class plgContentIdsplugin extends JPlugin
{

    /**
    * check here http://docs.joomla.org/Plugin#Content for more events/info
    */
      
     public function onContentPrepare($context, &$row, &$params, $limitstart)
    {
        # when you want to execute your function when the content is being prepared for display
    }

    public function onContentAfterTitle($context, &$row, &$params, $limitstart)
    {
       # when you want to execute your function after the title of the/an article has been displayed
    }

    public function onContentBeforeDisplay($context, &$row, &$params, $limitstart)
    {
        # when you want to execute your function before the/an article/content has been displayed
    }

    public function onContentAfterDisplay($context, &$row, &$params, $limitstart)
    {
        # when you want to execute your function after the/an article/content has been displayed

        $app = JFactory::getApplication();
        //Only run if on the front end, never the admin side
        if($app->isSite()){
		
            $ids_params  = $this->getIDSParams();
            $last_update = $ids_params['last_import_date'];
            $frequency   = $ids_params['update_frequency'];
            $today = date('Y-m-d', time());
            $next_update_date = date('Y-m-d', strtotime($last_update." +".$frequency." days"));

            if($today == $next_update_date){
              //call the functions to import data into joomla database
             $this->importFromIDS($ids_params, 'eldis'); 
             $this->importFromIDS($ids_params, 'bridge');
            }
        }
    }

    //get the saved api key from idsplugin params
    public function getAPIKey()
    {
        $db =& JFactory::getDBO();
        $db->setQuery("SELECT `params` FROM `#__extensions` WHERE `element` = 'idsplugin'");
        $params = $db->LoadResult(); 
        $params = json_decode($params);
        $api_key = $params->api_key;

        return $api_key;
    }

    public function getIDSParams(){
       $api_key = $this->getAPIKey();

        $db =& JFactory::getDBO();
        $db->setQuery("SELECT * FROM `#__ids_import_config` WHERE `api_key`='$api_key'");
        $ids_params = $db->LoadObject();
        $ids_params = (array) $ids_params;
        
        return $ids_params;
    }

    public function importFromIDS($ids_params, $server){

        $data = $ids_params;

        $GUID=$data['api_key'];
        $ids_asset = $data['ids_asset'];
        $catid = $data['cat_id'];
        $publish_preference = $data['publish_date_preference'];
        $auto_publish = $data['auto_publish'];

        $countries=$data[$server."_countries"];
        if($countries !="")
        {
            $countries= str_replace('+', ' ',$countries);
            $countries="&country=".$countries;
        }
        else 
        {
            $countries ="";
        }
        

        $themes=$data[$server."_themes"];
        if($themes !="")
        {
            $themes= str_replace('+', ' ',$themes);
            $themes="&theme=".$themes;
        }
        else
        {
            $themes ="";
        }

        $regions=$data[$server."_regions"];
        if($regions !="")
        {
            $regions= str_replace('+', ' ',$regions);
            $regions="&region=".$regions;
        }
        else
        {
            $regions ="";
        }

        $regions;
        //These fields are only valid when searching for documents, ie when the URL starts with /openapi/search/documents/.
        if($ids_asset == 'documents'){ 
            $before=$data[$server."_published_before"];
            if($before !="" and $before !="0000-00-00 00:00:00")
            {  $before="&document_published_before=".$before;  }
            else
            {   $before="";   } 


            $after=$data[$server."_published_after"];
            if($after !="" and $after !="0000-00-00 00:00:00")
            {   $after="&document_published_after=".$after;   }
            else
            {   $after="";   } 


            $year= $data[$server."_year"];
            if($year !="")
            {   $year="&document_published_year=".$year;   }
            else
            {   $year="";   }


            $author = $data[$server."_author"];
            if($author !="")
            {   $author="&author=".$author;   }
            else
            {   $author="";   }


            $publisher = $data[$server."_publisher"];
            if($publisher !="")
            {   $publisher="&publisher_name=".$publisher;   }
            else
            {   $publisher="";   }
        } 

        $search_term = $data[$server."_search_term"];
        if($search_term !="")
        {   $search_term="&q=".$search_term;   }
        else
        {   $search_term="";   }


        $number_of_records = $data["records"];

        $sort= "&".$data["order"]."=date_created";
        $requesttype="&_accept=application/xml";

        $baseurl ="http://api.ids.ac.uk/openapi/".$server."/search/".$ids_asset."/full?num_results=".$number_of_records;
        $guid="&_token_guid=".$GUID;

        $extra = "";
        $api_url =$baseurl .$guid. $themes.$countries.$regions.$search_term.$author.$publisher.$before.$after.$year.$sort.$extra.$requesttype;
        
        $xml = @simplexml_load_file($api_url);
        if($xml)
        {
            $content_elements = $xml->xpath('/root/results/list-item');
            if($content_elements)
            { 
              
                $user = JFactory::getUser();
                $db =& JFactory::getDBO();
                $articles_table = '#__content';

                $i = 1;
                foreach ($content_elements as $list_item) {
                    $title = (array) $list_item->title; //converts object to an array
                    $author = (array) $list_item->author; 
                    $corporate_author = (array) $list_item->corporate_author; 
                    $new_author = ($author)? $author:$corporate_author; 
                    $date_created = (array) $list_item->date_created;
                    $date_updated = (array) $list_item->date_updated;
                    $description = (array) $list_item->description;
                    $website_url = (array) $list_item->website_url;
                    $keywords = (array) $list_item->keyword;
                    $object_type = (array) $list_item->object_type;

                    if(!$this->getContentUrl($website_url[0])){

                        //Date to be used as the publish date for the imported content
                        switch ($publish_preference) {
                          case 'import':
                            $publish_date = date('Y-m-d H:i:s', time());
                            break;
                          case 'updated':
                            $publish_date = $date_updated[0];
                            break;
                          case 'created':
                            $publish_date = $date_created[0];
                            break;
                          default:
                            $publish_date = $date_created[0];
                            break;
                        }

                        $newrecord = new stdClass();
                        $newrecord->id  = NULL;
                        $newrecord->title = $title[0];
                        $newrecord->created_by  = $user->id;
                        $newrecord->created = $publish_date;
                        $newrecord->modified = $date_updated[0];
                        $newrecord->fulltext = $description[0];
                        $newrecord->catid = $catid;
                        $newrecord->state = $auto_publish;
                        $newrecord->access = 1;
                        $newrecord->created_by_alias = implode(',',$new_author['list-item']);
                        $newrecord->metadata = '{"robots":"","author":"'.implode(',',$new_author['list-item']).'","rights":"","xreference":"'.$website_url[0].'"}';
                        $newrecord->metakey = implode(', ', $keywords['list-item']);
                        $newrecord->metadesc = "IDS Asset: ".$object_type[0];
                        $newrecord->language = '*';
                        $newrecord->xreference = $website_url[0];

                        $db->insertObject($articles_table,$newrecord,'id');

                        if($i == 1){ //only update once to avoid unnecessary calls to the db
                          //update the import date
                          $import_date = new stdClass();
                          $import_date->api_key = $GUID;
                          $import_date->last_import_date = date('Y-m-d H:i:s', time());
                          $db->updateObject('#__ids_import_config',$import_date,'api_key');
                        }

                    }

                    $i++;
                }
            }
        }
        
    }

    //to be used to check if the content/article already exists
    public function getContentUrl($website_url){
        $db =& JFactory::getDBO();
        $db->setQuery("SELECT `id` FROM `#__content` WHERE `metadata` LIKE '%$website_url%'");
        $article_id = $db->LoadResult(); 
       
        return $article_id;
    }

}
?>