<?php

	include "config.php";
	include "../public_html/site/database/database_layer.inc";
	include "../public_html/site/database/" . DB_TYPE . "_database_layer.inc";
	$db_class = DB_TYPE . "_database_layer";
	$database = new $db_class();
	
	$link = $database->database_connect();
	$statement = $database->select_query_multiple("select index_link, site_address, site_licence from oer_site_list where url_type = :type", array(":type" => "RSS"), $link);
	$data = $database->get_all_rows($statement);

	foreach($data as $row){

		$file = "<?php \n";
		$file .= " include 'xml_parse.php';\n";
		$file .= " include 'curl_get.php';\n";
		$file .= " \$data = curl_data('" . $row['site_address'] . "', '" . $row['index_link'] . "');\n";
		$file .= " if(!empty(\$data)){\n";
		$file .= " \$xml_ingest = new xml_ingest(); \$xml_ingest->xml_process(\$data,'" . $row['site_licence'] . "','" . $row['index_link'] . "','" . $row['site_address'] . "');\n";
		$file .= " }else{ \n";
		$file .= "  echo 'empty data'; \n";
		$file .= " }\n";
		$file .= "?>";
		file_put_contents("rssscripts/" . $row['index_link'] . "_harvestscript.php",$file);

	}
	
	$statement = $database->select_query_multiple("select index_link, site_address, site_licence from oer_site_list where url_type = :type", array(":type" => "OAI"), $link);
	$data = $database->get_all_rows($statement);

	foreach($data as $row){

		$file = "<?php \n";
		$file .= " include 'xml_ingest_oai.php';\n";
		$file .= " \$xml_ingest = new xml_ingest_oai(); \$xml_ingest->get_url('" . $row['site_address'] . "','" . $row['site_address'] . "','" . $row['site_licence'] . "');\n";
		$file .= "?>";

		file_put_contents("oaiscripts/" . $row['index_link'] . "_harvestscript.php",$file);

	}
	
	$statement = $database->select_query_multiple("select index_link, site_address, site_licence from oer_site_list where url_type = :type", array(":type" => "FLICKR"), $link);
	$data = $database->get_all_rows($statement);

	foreach($data as $row){

		$file = "<?php \n";		
		$file .= " include 'flickr_ingest.php';\n";
		$file .= " \$flickr_ingest = new flickr_ingest(); \$flickr_ingest->flickr_search('" . $row['site_address'] . "');\n";
		$file .= "?>";

		file_put_contents("flickrscripts/" . $row['index_link'] . "_harvestscript.php",$file);

	}
	
	$statement = $database->select_query_multiple("select index_link, site_address, site_licence from oer_site_list where url_type = :type", array(":type" => "TUMBLR"), $link);
	$data = $database->get_all_rows($statement);

	foreach($data as $row){

		$file = "<?php \n";
		$file .= " include 'tumblr_ingest.php';\n";
		$file .= " \$tumblr_ingest = new tumblr_ingest(); \$tumblr_ingest->tumblr_search('" . $row['site_licence'] . "','" . $row['site_address'] . "');\n";
		$file .= "?>";

		file_put_contents("tumblrscripts/" . $row['index_link'] . "_harvestscript.php",$file);

	}
	
	$statement = $database->select_query_multiple("select index_link, site_address, site_licence from oer_site_list where url_type = :type", array(":type" => "SLIDESHARE"), $link);
	$data = $database->get_all_rows($statement);

	foreach($data as $row){

		$file = "<?php \n";
		$file .= " include 'slideshare_ingest.php';\n";
		$file .= " \$slideshare_ingest = new slideshare_ingest(); \$slideshare_ingest->slideshare_search('" . $row['site_address'] . "','" . $row['site_licence'] . "');\n";
		$file .= "?>";

		file_put_contents("slidesharescripts/" . $row['index_link'] . "_harvestscript.php",$file);

	}
	
	$statement = $database->select_query_multiple("select index_link, site_address, site_licence from oer_site_list where url_type = :type", array(":type" => "YOUTUBE"), $link);
	$data = $database->get_all_rows($statement);

	foreach($data as $row){

		$file = "<?php \n";
		$file .= " include 'youtube_ingest.php';\n";
		$file .= " \$youtube_ingest = new youtube_ingest(); \$youtube_ingest->youtube_search('" . $row['site_address'] . "','" . $row['site_licence'] . "');\n";
		$file .= "?>";

		file_put_contents("youtubescripts/" . $row['index_link'] . "_harvestscript.php",$file);

	}
