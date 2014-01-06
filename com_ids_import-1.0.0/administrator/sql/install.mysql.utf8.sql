--
-- Table structure for table `#__ids_import_config`
--

CREATE TABLE IF NOT EXISTS `#__ids_import_config` (
  `api_key` varchar(255) NOT NULL,
  `records` int(11) NOT NULL COMMENT 'Number of records to import',
  `cat_id` int(11) NOT NULL COMMENT 'Joomla category where the imported content will be saved',
  `update_frequency` int(11) NOT NULL COMMENT 'How often the ids component will be importing new data to joomla',
  `order` varchar(10) NOT NULL COMMENT 'the sort order of the imported content',
  `cache` int(11) NOT NULL,
  `bridge_themes` text NOT NULL,
  `bridge_countries` text NOT NULL,
  `bridge_regions` text NOT NULL,
  `bridge_author` varchar(255) NOT NULL,
  `bridge_publisher` varchar(255) NOT NULL,
  `bridge_search_term` varchar(255) NOT NULL,
  `bridge_year` varchar(10) NOT NULL COMMENT 'Year of Publication',
  `bridge_published_after` datetime NOT NULL,
  `bridge_published_before` datetime NOT NULL,
  `ids_asset` varchar(100) NOT NULL,
  `eldis_themes` text NOT NULL,
  `eldis_countries` text NOT NULL,
  `eldis_regions` text NOT NULL,
  `eldis_author` varchar(255) NOT NULL,
  `eldis_publisher` varchar(255) NOT NULL,
  `eldis_search_term` varchar(255) NOT NULL,
  `eldis_year` varchar(10) NOT NULL,
  `eldis_published_after` datetime NOT NULL,
  `eldis_published_before` datetime NOT NULL,
  `publish_date_preference` varchar(100) NOT NULL,
  `auto_publish` int(11) NOT NULL,
  `last_import_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;