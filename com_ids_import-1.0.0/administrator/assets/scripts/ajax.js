jQuery.noConflict();

jQuery(document).ready(function() {
    jQuery("#accordion").accordion({"autoHeight":false, active: 0 });

    var api_key = jQuery('#api_key').val();
    var eldis_server = 'eldis';
    var bridge_server = 'bridge';

/* START Load the countries */ 

	eldis_countries=document.getElementById('eldis_countries');
	bridge_countries=document.getElementById('bridge_countries');

	saved_eldis_countries = jQuery('#eldis_selected_countries').val();
	saved_bridge_countries = jQuery('#bridge_selected_countries').val();

	jQuery.get("components/com_ids_import/assets/scripts/loadcountry.php", {type:api_key,server:eldis_server,saved_countries:saved_eldis_countries},
	function(theResponse){
		if(eldis_countries!=null){
			jQuery('#eldis_countries').html(theResponse);
			jQuery('#eldis_countries').chosen({width: "372px"});
		}
    });

    jQuery.get("components/com_ids_import/assets/scripts/loadcountry.php", {type:api_key,server:bridge_server,saved_countries:saved_bridge_countries},
	function(theResponse){
		if(bridge_countries!=null){
			jQuery('#bridge_countries').html(theResponse);
			jQuery('#bridge_countries').chosen({width: "372px"});
		}
    });

/* END Load the countries */


/* START Load the themes */

	eldis_themes=document.getElementById('eldis_themes');
	bridge_themes=document.getElementById('bridge_themes');

	saved_eldis_themes = jQuery('#eldis_selected_themes').val();
	saved_bridge_themes = jQuery('#bridge_selected_themes').val();

	jQuery.get("components/com_ids_import/assets/scripts/loadthemes.php", {type:api_key,server:eldis_server,saved_themes:saved_eldis_themes},
	function(theResponse){
		if(eldis_themes!=null){
			jQuery('#eldis_themes').html(theResponse);
			jQuery('#eldis_themes').chosen({width: "372px"});
		}
    });

    jQuery.get("components/com_ids_import/assets/scripts/loadthemes.php", {type:api_key,server:bridge_server,saved_themes:saved_bridge_themes},
	function(theResponse){
		if(bridge_themes!=null){
			jQuery('#bridge_themes').html(theResponse);
			jQuery('#bridge_themes').chosen({width: "372px"});
		}
    });

/* END Load the themes */


/* START Load the regions */

	eldis_regions=document.getElementById('eldis_regions');
	bridge_regions=document.getElementById('bridge_regions');

	saved_eldis_regions = jQuery('#eldis_selected_regions').val();
	saved_bridge_regions = jQuery('#bridge_selected_regions').val();

	jQuery.get("components/com_ids_import/assets/scripts/loadregions.php", {type:api_key,server:eldis_server,saved_regions:saved_eldis_regions},
	function(theResponse){
		if(eldis_regions!=null){
			jQuery('#eldis_regions').html(theResponse);
			jQuery('#eldis_regions').chosen({width: "372px"});
		}
    });

    jQuery.get("components/com_ids_import/assets/scripts/loadregions.php", {type:api_key,server:bridge_server,saved_regions:saved_bridge_regions},
	function(theResponse){
		if(bridge_regions!=null){
			jQuery('#bridge_regions').html(theResponse);
			jQuery('#bridge_regions').chosen({width: "372px"});
		}
    });

/* END Load the regions */

/* START Load the available years */
	
	var saved_eldis_year = jQuery('#saved_eldis_year_of_publication').val();
	jQuery.get("components/com_ids_import/assets/scripts/loadyears.php", {type:api_key,server:eldis_server,year:saved_eldis_year},
	function(theResponse){

		jQuery('#eldis_year_of_publication').html(theResponse);
		jQuery('#eldis_year_of_publication').chosen({width: "150px"});

    });

	var saved_bridge_year = jQuery('#saved_bridge_year_of_publication').val();
    jQuery.get("components/com_ids_import/assets/scripts/loadyears.php", {type:api_key,server:eldis_server,year:saved_bridge_year},
	function(theResponse){

		jQuery('#bridge_year_of_publication').html(theResponse);
		jQuery('#bridge_year_of_publication').chosen({width: "150px"});

    });

/* END Load the available years */


});


jQuery(function() {
	jQuery('#bridge_themes').change(function() {
		var bridge_themes = jQuery('#bridge_themes').val();
		jQuery('#bridge_selected_themes').val(bridge_themes);
	});
});

jQuery(function() {
	jQuery('#bridge_countries').change(function() {
		var bridge_countries = jQuery('#bridge_countries').val();
		jQuery('#bridge_selected_countries').val(bridge_countries);
	});
});

jQuery(function() {
	jQuery('#bridge_regions').change(function() {
		var bridge_regions = jQuery('#bridge_regions').val();
		jQuery('#bridge_selected_regions').val(bridge_regions);
	});
});

jQuery(function() {
	jQuery('#eldis_themes').change(function() {
		var eldis_themes = jQuery('#eldis_themes').val();
		jQuery('#eldis_selected_themes').val(eldis_themes);
	});
});

jQuery(function() {
	jQuery('#eldis_countries').change(function() {
		var eldis_countries = jQuery('#eldis_countries').val();
		jQuery('#eldis_selected_countries').val(eldis_countries);
	});
});

jQuery(function() {
	jQuery('#eldis_regions').change(function() {
		var eldis_regions = jQuery('#eldis_regions').val();
		jQuery('#eldis_selected_regions').val(eldis_regions);
	});
});

//submit form when user clicks on the submit button
jQuery(function() {
	jQuery('#submit_form').click(function() {
		jQuery('#import_form').submit();
	});
});