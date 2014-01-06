<?php
/**
 * @version     1.0.0
 * @package     com_ids_import
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      @iLabAfrica <ilabafrica@strathmore.edu> - ilabafrica.ac.ke
 */


// No direct access
defined('_JEXEC') or die;

class Ids_importController extends JController
{
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			$cachable	If true, the view output will be cached
	 * @param	array			$urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT.'/helpers/ids_import.php';

		$view		= JFactory::getApplication()->input->getCmd('view', 'imports');
        JFactory::getApplication()->input->set('view', $view);

		parent::display($cachable, $urlparams);

		return $this;
	}

	function importNew(){
		$this->setRedirect('index.php?option=com_ids_import&view=import');
	}

	function cancel()
	{
		$this->setRedirect('index.php?option=com_ids_import&view=imports');
	}

	function eldisImport(){
		
		$data = JRequest::get('post');

		$server="eldis";
		$GUID=$data['api_key'];
		$ids_asset = $data['ids_asset'];
		$catid = $data['category'];
		$publish_preference = $data['publish_preference'];
		$auto_publish = $data['auto_publish'];

	   	$db =& JFactory::getDBO();

		if($this->checkParams($server)){ //check at least one search parameter exists for an API call to be made
			$eldis_selected_countries = ($data["eldis_selected_countries"])? explode(',',$data["eldis_selected_countries"]): '';
			$countries= $eldis_selected_countries;
			if($countries !="")
			{
				$countries= urlencode(implode('|',$countries));
				$countries="&country=".$countries;
			}
			else 
			{
				$countries ="";
			}

			$eldis_selected_themes = ($data["eldis_selected_themes"])? explode(',',$data["eldis_selected_themes"]): '';
			$themes= $eldis_selected_themes;
			if($themes !="")
			{
				$themes= urlencode(implode('|',$themes));
				$themes="&theme=".$themes;
			}
			else
			{
				$themes ="";
			}


			$eldis_selected_regions = ($data["eldis_selected_regions"])? explode(',',$data["eldis_selected_regions"]): '';
			//$regions= ($data["eldis_regions"])? $data["eldis_regions"] : $eldis_selected_regions;
			$regions= $eldis_selected_regions;
			if($regions !="")
			{
				$regions= urlencode(implode('|',$regions));
				$regions="&region=".$regions;
			}
			else
			{
				$regions ="";
			}

			//These fields are only valid when searching for documents, ie when the URL starts with /openapi/search/documents/.
			if($ids_asset == 'documents'){ 
				$before=$data["eldis_published_before"];
				if($before !="")
				{   $before="&document_published_before=".$before;  }
				else
				{   $before="";   } 


				$after=$data["eldis_published_after"];
				if($after !="")
				{   $after="&document_published_after=".$after;   }
				else
				{   $after="";   } 


				$year= $data["eldis_year_of_publication"];
				if($year !="")
				{   $year="&document_published_year=".$year;   }
				else
				{   $year="";   }


				$author = $data["eldis_author"];
				if($author !="")
				{   $author="&author=".$author;   }
				else
				{   $author="";   }


				$publisher = $data["eldis_publisher"];
				if($publisher !="")
				{   $publisher="&publisher_name=".$publisher;   }
				else
				{   $publisher="";   }
			}	

			$search_term = $data["eldis_search_term"];
			if($search_term !="")
			{   $search_term="&q=".$search_term;   }
			else
			{   $search_term="";   }


			$number_of_records = $data["number_of_records"];

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
					// echo "<div><a href='index.php?option=com_ids_import'><button style='width:150px;height:50px;'>Close</button></a></div>";
					// echo "<div style='overflow:scroll; width:900px; height:580px;'> ";

					$user = JFactory::getUser();
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
							$newrecord->created_by	= $user->id;
							$newrecord->created = $publish_date;
							$newrecord->modified = $date_updated[0];
				    		$newrecord->fulltext = $description[0];
				    		$newrecord->catid = $catid;
				    		$newrecord->state = $auto_publish;
				    		$newrecord->access = 1;
				    		$newrecord->created_by_alias = implode(', ',$new_author['list-item']);
				    		$newrecord->metadata = '{"robots":"","author":"'.implode(', ',$new_author['list-item']).'","rights":"","xreference":"'.$website_url[0].'"}';
				    		$newrecord->metakey = implode(', ', $keywords['list-item']);
				    		$newrecord->metadesc = "IDS Asset: ".$object_type[0];
				    		$newrecord->language = '*';
				    		$newrecord->xreference = $website_url[0];

				    		if($db->insertObject($articles_table,$newrecord,'id')){
				    			$msg = $i." eldis records imported successfully";
				    			$i++;
				    		}else{
				    			//$this->setRedirect('index.php?option=com_ids_import&view=import', $db->stderr());
				    		}
							
						}else{
							$msg = "eldis records that match your criteria already exist in the database";
						}
					}
					 //echo "</div>";
					
				}else{
					$msg = "No matching records found on eldis. Please try and search again with more generic search terms.";
				}
			}
			else
			{
				
				$msg = "No matching records found on eldis. Please try and search again with more generic search terms.";
			}
		}else{
			$msg = "No records imported from eldis since no criteria was set.";
		}

		return $msg;
	}



	function bridgeImport(){
		
		$data = JRequest::get('post');
		
		$server="bridge";
		$GUID=$data['api_key'];
		$ids_asset = "documents";
		$catid = $data['category'];
		$publish_preference = $data['publish_preference'];
		$auto_publish = $data['auto_publish'];

		if($this->checkParams($server)){ //check atleast one search parameter exists for an API call to be made

			$bridge_selected_countries = ($data["bridge_selected_countries"])? explode(';',$data["bridge_selected_countries"]): '';
			$countries= $bridge_selected_countries;
			if($countries !="")
			{
				$countries= urlencode(implode('|',$countries));
				$countries="&country=".$countries;
			}
			else 
			{
				$countries="";
			}


			$bridge_selected_themes = ($data["bridge_selected_themes"])? explode(';',$data["bridge_selected_themes"]): '';
			$themes= $bridge_selected_themes;
			if($themes !="")
			{
				$themes= urlencode(implode('|',$themes));
				$themes="&theme=".$themes;
			}
			else
			{
				$themes ="";
			}


			$bridge_selected_regions = ($data["bridge_selected_regions"])? explode(';',$data["bridge_selected_regions"]): '';
			$regions= $bridge_selected_regions;
			if($regions !="")
			{
				$regions= urlencode(implode('|',$regions));
				$regions="&region=".$regions;
			}
			else
			{
				$regions ="";
			}


			//These fields are only valid when searching for documents, ie when the URL starts with /openapi/search/documents/.
			$before=$data["bridge_published_before"];
			if($before !="")
			{   $before="&document_published_before=".$before;  }
			else
			{   $before="";   } 


			$after=$data["bridge_published_after"];
			if($after !="")
			{   $after="&document_published_after=".$after;   }
			else
			{   $after="";   } 


			$year= $data["bridge_year_of_publication"];
			if($year !="")
			{   $year="&document_published_year=".$year;   }
			else
			{   $year="";   }


			$author = $data["bridge_author"];
			if($author !="")
			{   $author="&author=".$author;   }
			else
			{   $author="";   }


			$publisher = $data["bridge_publisher"];
			if($publisher !="")
			{   $publisher="&publisher_name=".$publisher;   }
			else
			{   $publisher="";   }


		   $search_term = $data["bridge_search_term"];
		   if($search_term !="")
			{   $search_term="&q=".$search_term;   }
		   else
		   {   $search_term="";   }


		   $number_of_records = $data["number_of_records"];
		   
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
					// echo "<div><a href='index.php?option=com_ids_import'><button style='width:150px;height:50px;'>Close</button></a></div>";
					// echo "<div style='overflow:scroll; width:900px; height:580px;'> ";

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
							$newrecord->created_by	= $user->id;
							$newrecord->created = $publish_date;
							$newrecord->modified = $date_updated[0];
				    		$newrecord->fulltext = $description[0];
				    		$newrecord->catid = $catid;
				    		$newrecord->state = $auto_publish;
				    		$newrecord->access = 1;
				    		$newrecord->created_by_alias = implode(', ',$new_author['list-item']);
				    		$newrecord->metadata = '{"robots":"","author":"'.implode(', ',$new_author['list-item']).'","rights":"","xreference":"'.$website_url[0].'"}';
				    		$newrecord->metakey = implode(', ', $keywords['list-item']);
				    		$newrecord->metadesc = "IDS Asset: ".$object_type[0];
				    		$newrecord->language = '*';
				    		$newrecord->xreference = $website_url[0];

				    		if($db->insertObject($articles_table,$newrecord,'id')){
				    			$msg = $i." BRIDGE records imported successfully";
				    			$i++;
				    		}else{
				    			//$this->setRedirect('index.php?option=com_ids_import&view=import', $db->stderr());
				    		}
							
						}else{
							$msg = "BRIDGE records that match your criteria already exist in the database";
						}
					}
					 //echo "</div>";
					
				}else{
					$msg = "No matching records found on BRIDGE. Please try and search again with more generic search terms.";
				}
			}
			else
			{
					$msg = "No matching records found on BRIDGE. Please try and search again with more generic search terms.";
			}
		}else{
			$msg = "No records imported from BRIDGE since no criteria was set.";
		}

		return $msg;
	}


	//to be used to check if the content/article already exists
    public function getContentUrl($website_url){
        $db =& JFactory::getDBO();
        $db->setQuery("SELECT `id` FROM `#__content` WHERE `metadata` LIKE '%$website_url%'");
        $article_id = $db->LoadResult(); 
       
        return $article_id;
    }

    //check atleast one search parameter exists for an API call to be made
    public function checkParams($server){
    	$data = JRequest::get('post');
    	if($data[$server."_selected_themes"] || $data[$server."_selected_countries"] || $data[$server."_selected_regions"] || $data[$server."_author"] || $data[$server."_publisher"] || $data[$server."_search_term"] || $data[$server."_year_of_publication"] || $data[$server."_published_after"] || $data[$server."_published_before"]){
    		return true;
    	}else{
    		return false;
    	}

    }

    public function import(){
    	$data = JRequest::get('post');
		$server="eldis";
		$GUID=$data['api_key'];
		$ids_asset = $data['ids_asset'];
		$catid = $data['category'];
		$publish_preference = $data['publish_preference'];
		$auto_publish = $data['auto_publish'];

		//save the config and search params to the database
		$ids_import_config_table = '#__ids_import_config';
	   	$db =& JFactory::getDBO();
	   	$params = new stdClass();
		$params->api_key                 = $GUID;
		$params->records                 = $data["number_of_records"];
		$params->cat_id	                 = $catid;
		$params->update_frequency        = $data["frequency"];
		$params->order                   = $data["order"];
		$params->cache                   = $data["cache"];
		$params->bridge_themes           = $data["bridge_selected_themes"];
		$params->bridge_countries        = $data["bridge_selected_countries"];
		$params->bridge_regions          = $data["bridge_selected_regions"];
		$params->bridge_author           = $data["bridge_author"];
		$params->bridge_publisher        = $data["bridge_publisher"];
		$params->bridge_search_term      = $data["bridge_search_term"];
		$params->bridge_year             = ($data["bridge_year_of_publication"])? $data["bridge_year_of_publication"] : '';
		$params->bridge_published_after  = $data["bridge_published_after"];
		$params->bridge_published_before = $data["bridge_published_before"];
		$params->ids_asset               = $ids_asset;
		$params->eldis_themes            = $data["eldis_selected_themes"];
		$params->eldis_countries         = $data["eldis_selected_countries"];
		$params->eldis_regions           = $data["eldis_selected_regions"];
		$params->eldis_author            = $data["eldis_author"];
		$params->eldis_publisher         = $data["eldis_publisher"];
		$params->eldis_search_term       = $data["eldis_search_term"];
		$params->eldis_year              = ($data["eldis_year_of_publication"])? $data["eldis_year_of_publication"] : '';
		$params->eldis_published_after   = $data["eldis_published_after"];
		$params->eldis_published_before  = $data["eldis_published_before"];
		$params->publish_date_preference = $publish_preference;
		$params->auto_publish            = $auto_publish;
		$params->last_import_date        = date('Y-m-d H:i:s', time());

		$model =& $this->getModel( 'import' );
		$stored_params = $model->getIDSImportParams();

		if($stored_params){
			$db->updateObject($ids_import_config_table,$params,'api_key');
		}else{
			$db->insertObject($ids_import_config_table,$params,'api_key');
		}

		$bridge_msg = $this->bridgeImport(); //call the function to import bridge data into joomla database
		$eldis_msg = $this->eldisImport(); //call the function to import eldis data into joomla database

		$this->setRedirect('index.php?option=com_content&view=articles', $bridge_msg."<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$eldis_msg);
    }
}
