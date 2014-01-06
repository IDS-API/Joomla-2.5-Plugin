<?php
/**
 * @version     1.0.0
 * @package     com_movies
 * @copyright   Copyright (C) 2011. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Created by com_combuilder - http://www.notwebdesign.com
 */

// no direct access
defined('_JEXEC') or die;
error_reporting(0);
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHTML::_('behavior.calendar');

$document = & JFactory::getDocument();
$document->addScript( JURI::base() . 'components/com_ids_import/assets/scripts/jquery-1.8.3.min.js');
$document->addScript( JURI::base() . 'components/com_ids_import/assets/scripts/jquery-ui.min.js');
$document->addScript( JURI::base() . 'components/com_ids_import/assets/scripts/ajax.js');
$document->addScript( JURI::base() . 'components/com_ids_import/assets/scripts/chosen.jquery.js');
$document->addStyleSheet( JURI::base() . 'components/com_ids_import/assets/css/jquery-ui-1.8.6.custom.css');
$document->addStyleSheet( JURI::base() . 'components/com_ids_import/assets/css/chosen.css');

$bridge_themes = ($this->params->bridge_themes)? $this->params->bridge_themes:'';
$bridge_countries = ($this->params->bridge_countries)? $this->params->bridge_countries:'';
$bridge_regions = ($this->params->bridge_regions)? $this->params->bridge_regions:'';
$bridge_author = ($this->params->bridge_author)? $this->params->bridge_author:'';
$bridge_publisher = ($this->params->bridge_publisher)? $this->params->bridge_publisher:'';
$bridge_search_term = ($this->params->bridge_search_term)? $this->params->bridge_search_term:'';
$bridge_published_after = ($this->params->bridge_published_after != '0000-00-00 00:00:00')? $this->params->bridge_published_after : '';
$bridge_published_before = ($this->params->bridge_published_before != '0000-00-00 00:00:00')? $this->params->bridge_published_before : '';

$eldis_themes = ($this->params->eldis_themes)? $this->params->eldis_themes:'';
$eldis_countries = ($this->params->eldis_countries)? $this->params->eldis_countries:'';
$eldis_regions = ($this->params->eldis_regions)? $this->params->eldis_regions:'';
$eldis_author = ($this->params->eldis_author)? $this->params->eldis_author:'';
$eldis_publisher = ($this->params->eldis_publisher)? $this->params->eldis_publisher:'';
$eldis_search_term = ($this->params->eldis_search_term)? $this->params->eldis_search_term:'';
$eldis_published_after = ($this->params->eldis_published_after != '0000-00-00 00:00:00')? $this->params->eldis_published_after : '';
$eldis_published_before = ($this->params->eldis_published_before != '0000-00-00 00:00:00')? $this->params->eldis_published_before : '';

$selected_order1 = ($this->params->order == 'sort_asc')? "selected='selected'": "";
$selected_order2 = ($this->params->order == 'sort_desc')? "selected='selected'": "";

$selected_cache1 = ($this->params->cache == 1)? "checked='checked'": "";
$selected_cache2 = ($this->params->cache == 0)? "checked='checked'": "";

$selected_idsasset1 = "checked='checked'";
$selected_idsasset2 = ($this->params->ids_asset == 'organisations')? "checked='checked'": "";
if($selected_idsasset2 != ''){
	$selected_idsasset1 = "";
}
$selected_publishdate1 = ($this->params->publish_date_preference == 'import')? "selected='selected'": "";
$selected_publishdate2 = ($this->params->publish_date_preference == 'updated')? "selected='selected'": "";
$selected_publishdate3 = ($this->params->publish_date_preference == 'created')? "selected='selected'": "";

$selected_autopublish1 = ($this->params->auto_publish == 1)? "checked='checked'": "";
$selected_autopublish2 = ($this->params->auto_publish == 0)? "checked='checked'": "";
?>


<form action="" method="post" name="adminForm" id="import_form" class="form-validate" enctype="multipart/form-data">
	<div id="accordion"><!-- Start accordion div -->
		<h3><a href="javascript:void(0);">General settings:</a></h3>
		<div><!-- Start Div of first panel -->
			<table width="60%">
				<tr>
					<td width="30%"><img src="components/com_ids_import/assets/images/tooltip.png"  title="Your IDS API Key or Token-GUID. To request one, visit http://api.ids.ac.uk/accounts/register/"/>&nbsp;&nbsp;<b >API key:</b></td>
					<td>
						<input type="text" name="api_key" id="api_key" disabled value="<?php echo $this->api_key; ?>" size="40"/>
					</td>
				</tr>
				<tr>
					<td><img src="components/com_ids_import/assets/images/tooltip.png"  title="Number of records to be imported from the IDS KS API datasets."/>&nbsp;&nbsp;<b >Number of Records:</b></td>
					<td width="">
						<input type="text" name="number_of_records" id="number_of_records" value="<?php echo ($this->params->records)? $this->params->records:10; ?>" size="40"/>
					</td>
				</tr>
				<tr>
					<td><img src="components/com_ids_import/assets/images/tooltip.png"  title="Select a category you would like imported assets to be mapped to? You can add a new one to the list through the Category Manager."/>&nbsp;&nbsp;<b >Joomla Content Category:</b></td>
					<td width="">
						<?php echo JHTML::_('select.genericlist',  $this->article_categories, 'category', 'class="inputbox"', 'id', 'title', $this->params->cat_id); ?> 
					</td>
				</tr>
				<tr>
					<td><img src="components/com_ids_import/assets/images/tooltip.png"  title="After how many days would you like the plugin to look for more content from the IDS KS API?"/>&nbsp;&nbsp;<b >Update frequency:</b></td>
					<td width="">
						<input type="text" name="frequency" id="frequency" value="<?php echo ($this->params->update_frequency)? $this->params->update_frequency:10; ?>" size="40"/>
					</td>
				</tr>
				<tr>
					<td><img src="components/com_ids_import/assets/images/tooltip.png"  title="How would you like results from the IDS KS API to be sorted?"/>&nbsp;&nbsp;<b>Sort order of results:</b></td>
					<td width="">
						<select name="order">
							<option <?php echo $selected_order1; ?> value="sort_asc">Ascending</option>
							<option <?php echo $selected_order2; ?> value="sort_desc">Descending</option>
						</select>
					</td>
				</tr>
				<tr>
					<td width="30%"><img src="components/com_ids_import/assets/images/tooltip.png" title="Would you like results from the IDS KS API to be cached by default?"/>&nbsp;&nbsp;<b >Cache results:</b></td>
					<td>
						Yes<input type="radio" name="cache" value="yes" <?php echo $selected_cache1; ?> />&nbsp;&nbsp;&nbsp;No<input type="radio" name="cache" value="no"  <?php echo $selected_cache2; ?>>
					</td>
				</tr>
			</table>
		</div><!-- End Div of first panel -->

		<h3><a href="javascript:void(0);">BRIDGE Import Settings:</a></h3>
		<div><!-- Start Div of second panel -->
			<table width="60%">
				<tr>
					<td width="30%"><img src="components/com_ids_import/assets/images/tooltip.png" title="Themes which the imported content focuses on."/>&nbsp;&nbsp;<b >Themes:</b></td>
					<td>
						<select name="bridge_themes[]" id="bridge_themes" class="chzn-select" multiple style="width:372;" tabindex="4">
							<option value=""></option>
							<option value=""></option>
						</select>
					</td>
				</tr>
				<tr style="display:none;">
					<td width="30%"><b>Selected Themes:</b></td>
					<td><textarea name="bridge_selected_themes" id="bridge_selected_themes" cols="50" rows="7"><?php echo str_replace('|', ';', urldecode($bridge_themes)); ?></textarea></td>
				</tr>
				<tr>
					<td width="30%"><img src="components/com_ids_import/assets/images/tooltip.png"  title="Countries which the imported content focuses on."/>&nbsp;&nbsp;<b >Countries:</b></td>
					<td>
						<select name="bridge_countries[]" id="bridge_countries" multiple style="width:372px; height:100px;">
							<option value=""></option>
							<option value=""></option>
						</select>
					</td>
				</tr>
				<tr style="display:none;">
					<td width="30%"><b>Selected Countries:</b></td>
					<td><textarea name="bridge_selected_countries" id="bridge_selected_countries" cols="50" rows="7"><?php echo str_replace('|', ';', urldecode($bridge_countries)); ?></textarea></td>
				</tr>
				<tr>
					<td width="30%"><img src="components/com_ids_import/assets/images/tooltip.png" title="Regions which the imported content focuses on."/>&nbsp;&nbsp;<b >Regions:</b></td>
					<td>
						<select name="bridge_regions[]" id="bridge_regions" multiple>
							<option value=""></option>
							<option value=""></option>
						</select>
					</td>
				</tr>
				<tr style="display:none;">
					<td width="30%"><b>Selected Regions:</b></td>
					<td><textarea name="bridge_selected_regions" id="bridge_selected_regions" cols="50" rows="7"><?php echo str_replace('|', ';', urldecode($bridge_regions)); ?></textarea></td>
				</tr>
				<tr>
					<td><img src="components/com_ids_import/assets/images/tooltip.png"   title="Name of the author of content to be included in the search."/>&nbsp;&nbsp;<b>Name of Author:</b></td>
					<td width="">
						<input type="text" name="bridge_author" id="bridge_author" value="<?php echo $bridge_author; ?>" size="40"/>
					</td>
				</tr>
				<tr>
					<td><img src="components/com_ids_import/assets/images/tooltip.png" title="Name of the publisher of content to be included in the search."/>&nbsp;&nbsp;<b >Name of Publisher:</b></td>
					<td width="">
						<input type="text" name="bridge_publisher" id="bridge_publisher" value="<?php echo $bridge_publisher; ?>" size="40"/>
					</td>
				</tr>
				<tr>
					<td><img src="components/com_ids_import/assets/images/tooltip.png" title="Keyword or search term to be included in the search."/>&nbsp;&nbsp;<b >Search term/Keyword:</b></td>
					<td width="">
						<input type="text" name="bridge_search_term" id="bridge_search_term" value="<?php echo $bridge_search_term; ?>" size="40"/>
					</td>
				</tr>
				
				<tr>
					<td><img src="components/com_ids_import/assets/images/tooltip.png" title="Navigate to the preferred year of publication using the datepicker beside."/>&nbsp;&nbsp;<b >Year of Publication:</b></td>
					<td width="">
						
						<select name="bridge_year_of_publication" id="bridge_year_of_publication" >
							<option value=""></option>
							<option value=""></option>
						</select>
						<input type="hidden" name="saved_bridge_year_of_publication" id="saved_bridge_year_of_publication" value="<?php echo $this->params->bridge_year; ?>">
					</td>
				</tr>
				<tr>
					<td><img src="components/com_ids_import/assets/images/tooltip.png"  title="Date after which documents were published specified as year, month, day i.e. YYYY-MM-DD."/>&nbsp;&nbsp;<b>Published after:</b></td>
					<td width="">
						<?php echo JHtml::calendar($bridge_published_after, 'bridge_published_after', 'bridge_published_after', '%Y-%m-%d');?>
					</td>
				</tr>
				<tr>
					<td><img src="components/com_ids_import/assets/images/tooltip.png"  title="Date before which documents were published specified as year, month, day i.e. YYYY-MM-DD."/>&nbsp;&nbsp;<b >Published before:</b></td>
					<td width="">
						<?php echo JHtml::calendar($bridge_published_before, 'bridge_published_before', 'bridge_published_before', '%Y-%m-%d');?>
					</td>
				</tr>
				
			</table>
		</div><!-- End Div of second panel -->

		<h3><a href="javascript:void(0);">Eldis Import Settings:</a></h3>
		<div><!-- Start Div of third panel -->
			<table width="60%">
				<tr>
					<td width="30%"><b>IDS Asset:</b></td>
					<td>
						Documents<input type="radio" name="ids_asset" value="documents" <?php echo $selected_idsasset1; ?> />&nbsp;&nbsp;&nbsp;Organisations<input type="radio" name="ids_asset" value="organisations" <?php echo $selected_idsasset2; ?> />
					</td>
				</tr>
				<tr>
					<td width="30%"><b>Themes:</b></td>
					<td>
						<select name="eldis_themes[]" id="eldis_themes" multiple style="width:372px; height:100px;">
							<option value=""></option>
							<option value=""></option>
						</select>
					</td>
				</tr>
				<tr style="display:none;">
					<td width="30%"><b>Selected Themes:</b></td>
					<td><textarea name="eldis_selected_themes" id="eldis_selected_themes" cols="50" rows="7"><?php echo str_replace('|', ';', urldecode($eldis_themes)); ?></textarea></td>
				</tr>
				<tr>
					<td width="30%"><b>Countries:</b></td>
					<td>
						<select name="eldis_countries[]" id="eldis_countries" multiple style="width:372px; height:100px;">
							<option value=""></option>
							<option value=""></option>
						</select>
					</td>
				</tr>
				<tr style="display:none;">
					<td width="30%"><b>Selected Countries:</b></td>
					<td><textarea name="eldis_selected_countries" id="eldis_selected_countries" cols="50" rows="7"><?php echo str_replace('|', ';', urldecode($eldis_countries)); ?></textarea></td>
				</tr>
				<tr>
					<td width="30%"><b>Regions:</b></td>
					<td>
						<select name="eldis_regions[]"  id="eldis_regions" multiple style="width:372px; height:100px;">
							<option value=""></option>
							<option value=""></option>
						</select>
					</td>
				</tr>
				<tr style="display:none;">
					<td width="30%"><b>Selected Regions:</b></td>
					<td><textarea name="eldis_selected_regions" id="eldis_selected_regions" cols="50" rows="7"><?php echo str_replace('|', ';', urldecode($eldis_regions)); ?></textarea></td>
				</tr>
				<tr>
					<td><b>Name of Author:</b></td>
					<td width="">
						<input type="text" name="eldis_author" id="eldis_author" value="<?php echo $eldis_author; ?>" size="40"/>
					</td>
				</tr>
				<tr>
					<td><b>Name of Publisher:</b></td>
					<td width="">
						<input type="text" name="eldis_publisher" id="eldis_publisher" value="<?php echo $eldis_publisher; ?>" size="40"/>
					</td>
				</tr>
				<tr>
					<td><b>Search term/Keyword:</b></td>
					<td width="">
						<input type="text" name="eldis_search_term" id="eldis_search_term" value="<?php echo $eldis_search_term; ?>" size="40"/>
					</td>
				</tr>
				
				<tr>
					<td><b>Year of Publication:</b></td>
					<td width="">
						
						<select name="eldis_year_of_publication"  id="eldis_year_of_publication">
							<option value=""></option>
							<option value=""></option>
						</select>
						<input type="hidden" name="saved_eldis_year_of_publication" id="saved_eldis_year_of_publication" value="<?php echo $this->params->eldis_year; ?>">
					</td>
				</tr>
				<tr>
					<td><b>Published after:</b></td>
					<td width="">
						<?php echo JHtml::calendar($eldis_published_after, 'eldis_published_after', 'eldis_published_after', '%Y-%m-%d');?>
					</td>
				</tr>
				<tr>
					<td><b>Published before:</b></td>
					<td width="">
						<?php echo JHtml::calendar($eldis_published_before, 'eldis_published_before', 'eldis_published_before', '%Y-%m-%d');?>
					</td>
				</tr>
				
			</table>
		</div><!-- End Div of third panel -->

		<h3><a href="javascript:void(0);">Display Options:</a></h3>
		<div><!-- Start Div of fourth panel -->
			<table width="60%">
				<tr>
					<td width="30%"><img src="components/com_ids_import/assets/images/tooltip.png"  title="Which date would you like to use as the publish date for your imported Joomla content?"/>&nbsp;&nbsp;<b>Publish Date Preference<b></td>
					<td>
						<select name="publish_preference">
							<option <?php echo $selected_publishdate1;?> value="import">Date of import</option>
							<option <?php echo $selected_publishdate2;?> value="updated">Date updated in IDS dataset</option>
							<option <?php echo $selected_publishdate3;?> value="created">Date of creation in IDS dataset</option>
						</select>
					</td>
				</tr>
				<tr>
					<td width="30%"><img src="components/com_ids_import/assets/images/tooltip.png" title="Would you like imported content to be automatically published on your website?"/>&nbsp;&nbsp;<b>Auto-Publish:</b></td>
					<td>
						Yes<input type="radio" name="auto_publish" value="1" <?php echo $selected_autopublish1;?> />&nbsp;&nbsp;&nbsp;No<input type="radio" name="auto_publish" value="0" <?php echo $selected_autopublish2;?>>
					</td>
				</tr>
			</table>
		</div><!-- End Div of third panel -->

	</div><!-- End accordion div -->	
	<fieldset>
		<legend></legend>
				<table width="60%">
					<tr>
						<td>
							<!--  -->
							<button id="submit_form" style="width:150px;height:50px;">SUBMIT</button>
							<!-- <input type="reset" name="reset" id="reset" value="Reset form" style="width:150px;height:50px;"/> -->
						</td>
					</tr>
				</table>
   
	</fieldset>
	<input type="hidden" name="api_key" value="<?php echo $this->api_key; ?>"/>
	<input type="hidden" name="option" value="com_ids_import" />
	<input type="hidden" name="task" value="import" />	
	<input type="hidden" name="view" value="import" />	
	<?php echo JHtml::_('form.token'); ?>
	
</form>

