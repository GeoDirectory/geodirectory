<?php 
if (!function_exists('geodir_create_tables')) {
	function geodir_create_tables() {
		
		global $wpdb,$plugin_prefix;

		$wpdb->hide_errors();
	
		$collate = '';
		if($wpdb->has_cap( 'collation' )) {
			if(!empty($wpdb->charset)) $collate = "DEFAULT CHARACTER SET $wpdb->charset";
			if(!empty($wpdb->collate)) $collate .= " COLLATE $wpdb->collate";
		}
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		
		
		
// rename tables if we need to
if($wpdb->query("SHOW TABLES LIKE 'geodir_countries'")>0 && $wpdb->query("SHOW TABLES LIKE '".$wpdb->prefix."geodir_countries'")==0){$wpdb->query("RENAME TABLE geodir_countries TO ".$wpdb->prefix."geodir_countries");}
if($wpdb->query("SHOW TABLES LIKE 'geodir_custom_fields'")>0 && $wpdb->query("SHOW TABLES LIKE '".$wpdb->prefix."geodir_custom_fields'")==0){$wpdb->query("RENAME TABLE geodir_custom_fields TO ".$wpdb->prefix."geodir_custom_fields");}
if($wpdb->query("SHOW TABLES LIKE 'geodir_post_icon'")>0 && $wpdb->query("SHOW TABLES LIKE '".$wpdb->prefix."geodir_post_icon'")==0){$wpdb->query("RENAME TABLE geodir_post_icon TO ".$wpdb->prefix."geodir_post_icon");}
if($wpdb->query("SHOW TABLES LIKE 'geodir_attachments'")>0 && $wpdb->query("SHOW TABLES LIKE '".$wpdb->prefix."geodir_attachments'")==0){$wpdb->query("RENAME TABLE geodir_attachments TO ".$wpdb->prefix."geodir_attachments");}
if($wpdb->query("SHOW TABLES LIKE 'geodir_post_review'")>0 && $wpdb->query("SHOW TABLES LIKE '".$wpdb->prefix."geodir_post_review'")==0){$wpdb->query("RENAME TABLE geodir_post_review TO ".$wpdb->prefix."geodir_post_review");}
if($wpdb->query("SHOW TABLES LIKE 'geodir_custom_sort_fields'")>0 && $wpdb->query("SHOW TABLES LIKE '".$wpdb->prefix."geodir_custom_sort_fields'")==0){$wpdb->query("RENAME TABLE geodir_custom_sort_fields TO ".$wpdb->prefix."geodir_custom_sort_fields");}
if($wpdb->query("SHOW TABLES LIKE 'geodir_gd_place_detail'")>0 && $wpdb->query("SHOW TABLES LIKE '".$wpdb->prefix."geodir_gd_place_detail'")==0){$wpdb->query("RENAME TABLE geodir_gd_place_detail TO ".$wpdb->prefix."geodir_gd_place_detail");}
		
			
		// Table for storing Countries
		$GEODIR_COUNTRIES_TABLE = "CREATE TABLE ".GEODIR_COUNTRIES_TABLE." (
						CountryId smallint AUTO_INCREMENT NOT NULL ,
						Country varchar (50) NOT NULL ,
						FIPS104 varchar (2) NOT NULL ,
						ISO2 varchar (2) NOT NULL ,
						ISO3 varchar (3) NOT NULL ,
						ISON varchar (4) NOT NULL ,
						Internet varchar (2) NOT NULL ,
						Capital varchar (25) NULL ,
						MapReference varchar (50) NULL ,
						NationalitySingular varchar (35) NULL ,
						NationalityPlural varchar (35) NULL ,
						Currency varchar (30) NULL ,
						CurrencyCode varchar (3) NULL ,
						Population bigint NULL ,
						Title varchar (50) NULL ,
						Comment varchar (255) NULL ,
						PRIMARY KEY  (CountryId)) $collate ";
		$GEODIR_COUNTRIES_TABLE = apply_filters('geodir_before_country_table_create' , $GEODIR_COUNTRIES_TABLE)	 ;			
		dbDelta($GEODIR_COUNTRIES_TABLE);
		
		
		$country_table_empty = 	$wpdb->get_var("SELECT COUNT(CountryId) FROM ".GEODIR_COUNTRIES_TABLE."");
		
		if($country_table_empty == 0){
		
			$countries_insert = "INSERT INTO ".GEODIR_COUNTRIES_TABLE." (`CountryId`, `Country`, `FIPS104`, `ISO2`, `ISO3`, `ISON`, `Internet`, `Capital`, `MapReference`, `NationalitySingular`, `NationalityPlural`, `Currency`, `CurrencyCode`, `Population`, `Title`, `Comment`) VALUES
	(1, 'Afghanistan', 'AF', 'AF', 'AFG', '4', 'AF', 'Kabul ', 'Asia ', 'Afghan', 'Afghans', 'Afghani ', 'AFA', 26813057, 'Afghanistan', ''),
	(2, 'Albania', 'AL', 'AL', 'ALB', '8', 'AL', 'Tirana ', 'Europe ', 'Albanian', 'Albanians', 'Lek ', 'ALL', 3510484, 'Albania', ''),
	(3, 'Algeria', 'AG', 'DZ', 'DZA', '12', 'DZ', 'Algiers ', 'Africa ', 'Algerian', 'Algerians', 'Algerian Dinar ', 'DZD', 31736053, 'Algeria', ''),
	(4, 'American Samoa', 'AQ', 'AS', 'ASM', '16', 'AS', 'Pago Pago ', 'Oceania ', 'American Samoan', 'American Samoans', 'US Dollar', 'USD', 67084, 'American Samoa', ''),
	(5, 'Andorra', 'AN', 'AD', 'AND', '20', 'AD', 'Andorra la Vella ', 'Europe ', 'Andorran', 'Andorrans', 'Euro', 'EUR', 67627, 'Andorra', ''),
	(6, 'Angola', 'AO', 'AO', 'AGO', '24', 'AO', 'Luanda ', 'Africa ', 'Angolan', 'Angolans', 'Kwanza ', 'AOA', 10366031, 'Angola', ''),
	(7, 'Anguilla', 'AV', 'AI', 'AIA', '660', 'AI', 'The Valley ', 'Central America and the Caribbean ', 'Anguillan', 'Anguillans', 'East Caribbean Dollar ', 'XCD', 12132, 'Anguilla', ''),
	(8, 'Antarctica', 'AY', 'AQ', 'ATA', '10', 'AQ', '', 'Antarctic Region ', '', '', '', '', 0, 'Antarctica', 'ISO defines as the territory south of 60 degrees south latitude'),
	(9, 'Antigua and Barbuda', 'AC', 'AG', 'ATG', '28', 'AG', 'Saint John''s ', 'Central America and the Caribbean ', 'Antiguan and Barbudan', 'Antiguans and Barbudans', 'East Caribbean Dollar ', 'XCD', 66970, 'Antigua and Barbuda', ''),
	(10, 'Argentina', 'AR', 'AR', 'ARG', '32', 'AR', 'Buenos Aires ', 'South America ', 'Argentine', 'Argentines', 'Argentine Peso ', 'ARS', 37384816, 'Argentina', ''),
	(11, 'Armenia', 'AM', 'AM', 'ARM', '51', 'AM', 'Yerevan ', 'Commonwealth of Independent States ', 'Armenian', 'Armenians', 'Armenian Dram ', 'AMD', 3336100, 'Armenia', ''),
	(12, 'Aruba', 'AA', 'AW', 'ABW', '533', 'AW', 'Oranjestad ', 'Central America and the Caribbean ', 'Aruban', 'Arubans', 'Aruban Guilder', 'AWG', 70007, 'Aruba', ''),
	(13, 'Ashmore and Cartier', 'AT', '--', '-- ', '--', '--', '', 'Southeast Asia ', '', '', '', '', 0, 'Ashmore and Cartier', 'ISO includes with Australia'),
	(14, 'Australia', 'AS', 'AU', 'AUS', '36', 'AU', 'Canberra ', 'Oceania ', 'Australian', 'Australians', 'Australian dollar ', 'AUD', 19357594, 'Australia', 'ISO includes Ashmore and Cartier Islands,Coral Sea Islands'),
	(15, 'Austria', 'AU', 'AT', 'AUT', '40', 'AT', 'Vienna ', 'Europe ', 'Austrian', 'Austrians', 'Euro', 'EUR', 8150835, 'Austria', ''),
	(16, 'Azerbaijan', 'AJ', 'AZ', 'AZE', '31', 'AZ', 'Baku (Baki) ', 'Commonwealth of Independent States ', 'Azerbaijani', 'Azerbaijanis', 'Azerbaijani Manat ', 'AZM', 7771092, 'Azerbaijan', ''),
	(18, 'Bahrain', 'BA', 'BH', 'BHR', '48', 'BH', 'Manama ', 'Middle East ', 'Bahraini', 'Bahrainis', 'Bahraini Dinar ', 'BHD', 645361, 'Bahrain', ''),
	(19, 'Baker Island', 'FQ', '--', '-- ', '--', '--', '', 'Oceania ', '', '', '', '', 0, 'Baker Island', 'ISO includes with the US Minor Outlying Islands'),
	(20, 'Bangladesh', 'BG', 'BD', 'BGD', '50', 'BD', 'Dhaka ', 'Asia ', 'Bangladeshi', 'Bangladeshis', 'Taka ', 'BDT', 131269860, 'Bangladesh', ''),
	(21, 'Barbados', 'BB', 'BB', 'BRB', '52', 'BB', 'Bridgetown ', 'Central America and the Caribbean ', 'Barbadian', 'Barbadians', 'Barbados Dollar', 'BBD', 275330, 'Barbados', ''),
	(22, 'Bassas da India', 'BS', '--', '-- ', '--', '--', '', 'Africa ', '', '', '', '', 0, 'Bassas da India', 'ISO includes with the Miscellaneous (French) Indian Ocean Islands'),
	(23, 'Belarus', 'BO', 'BY', 'BLR', '112', 'BY', 'Minsk ', 'Commonwealth of Independent States ', 'Belarusian', 'Belarusians', 'Belarussian Ruble', 'BYR', 10350194, 'Belarus', ''),
	(24, 'Belgium', 'BE', 'BE', 'BEL', '56', 'BE', 'Brussels ', 'Europe ', 'Belgian', 'Belgians', 'Euro', 'EUR', 10258762, 'Belgium', ''),
	(25, 'Belize', 'BH', 'BZ', 'BLZ', '84', 'BZ', 'Belmopan ', 'Central America and the Caribbean ', 'Belizean', 'Belizeans', 'Belize Dollar', 'BZD', 256062, 'Belize', ''),
	(26, 'Benin', 'BN', 'BJ', 'BEN', '204', 'BJ', 'Porto-Novo  ', 'Africa ', 'Beninese', 'Beninese', 'CFA Franc BCEAO', 'XOF', 6590782, 'Benin', ''),
	(27, 'Bermuda', 'BD', 'BM', 'BMU', '60', 'BM', 'Hamilton ', 'North America ', 'Bermudian', 'Bermudians', 'Bermudian Dollar ', 'BMD', 63503, 'Bermuda', ''),
	(28, 'Bhutan', 'BT', 'BT', 'BTN', '64', 'BT', 'Thimphu ', 'Asia ', 'Bhutanese', 'Bhutanese', 'Ngultrum', 'BTN', 2049412, 'Bhutan', ''),
	(29, 'Bolivia', 'BL', 'BO', 'BOL', '68', 'BO', 'La Paz /Sucre ', 'South America ', 'Bolivian', 'Bolivians', 'Boliviano ', 'BOB', 8300463, 'Bolivia', ''),
	(30, 'Bosnia and Herzegovina', 'BK', 'BA', 'BIH', '70', 'BA', 'Sarajevo ', 'Bosnia and Herzegovina, Europe ', 'Bosnian and Herzegovinian', 'Bosnians and Herzegovinians', 'Convertible Marka', 'BAM', 3922205, 'Bosnia and Herzegovina', ''),
	(31, 'Botswana', 'BC', 'BW', 'BWA', '72', 'BW', 'Gaborone ', 'Africa ', 'Motswana', 'Batswana', 'Pula ', 'BWP', 1586119, 'Botswana', ''),
	(32, 'Bouvet Island', 'BV', 'BV', 'BVT', '74', 'BV', '', 'Antarctic Region ', '', '', 'Norwegian Krone ', 'NOK', 0, 'Bouvet Island', ''),
	(33, 'Brazil', 'BR', 'BR', 'BRA', '76', 'BR', 'Brasilia ', 'South America ', 'Brazilian', 'Brazilians', 'Brazilian Real ', 'BRL', 174468575, 'Brazil', ''),
	(34, 'British Indian Ocean Territory', 'IO', 'IO', 'IOT', '86', 'IO', '', 'World ', '', '', 'US Dollar', 'USD', 0, 'The British Indian Ocean Territory', ''),
	(35, 'British Virgin Islands', 'VI', 'VG', 'VGB', '92', 'VG', 'Road Town ', 'Central America and the Caribbean ', 'British Virgin Islander', 'British Virgin Islanders', 'US Dollar', 'USD', 20812, 'The British Virgin Islands', ''),
	(36, 'Brunei Darussalam', 'BX', 'BN', 'BRN', '96', 'BN', '', '', '', '', 'Brunei Dollar', 'BND', 372361, 'Brunei', ''),
	(37, 'Bulgaria', 'BU', 'BG', 'BGR', '100', 'BG', 'Sofia ', 'Europe ', 'Bulgarian', 'Bulgarians', 'Lev ', 'BGN', 7707495, 'Bulgaria', ''),
	(38, 'Burkina Faso', 'UV', 'BF', 'BFA', '854', 'BF', 'Ouagadougou ', 'Africa ', 'Burkinabe', 'Burkinabe', 'CFA Franc BCEAO', 'XOF', 12272289, 'Burkina Faso', ''),
	(39, 'Burma', 'BM', 'MM', 'MMR', '104', 'MM', 'Rangoon ', 'Southeast Asia ', 'Burmese', 'Burmese', 'kyat ', 'MMK', 41994678, 'Burma', 'ISO uses the name Myanmar'),
	(40, 'Burundi', 'BY', 'BI', 'BDI', '108', 'BI', 'Bujumbura ', 'Africa ', 'Burundi', 'Burundians', 'Burundi Franc ', 'BIF', 6223897, 'Burundi', ''),
	(41, 'Cambodia', 'CB', 'KH', 'KHM', '116', 'KH', 'Phnom Penh ', 'Southeast Asia ', 'Cambodian', 'Cambodians', 'Riel ', 'KHR', 12491501, 'Cambodia', ''),
	(42, 'Cameroon', 'CM', 'CM', 'CMR', '120', 'CM', 'Yaounde ', 'Africa ', 'Cameroonian', 'Cameroonians', 'CFA Franc BEAC', 'XAF', 15803220, 'Cameroon', ''),
	(43, 'Canada', 'CA', 'CA', 'CAN', '124', 'CA', 'Ottawa ', 'North America ', 'Canadian', 'Canadians', 'Canadian Dollar ', 'CAD', 31592805, 'Canada', ''),
	(44, 'Cape Verde', 'CV', 'CV', 'CPV', '132', 'CV', 'Praia ', 'World ', 'Cape Verdean', 'Cape Verdeans', 'Cape Verdean Escudo ', 'CVE', 405163, 'Cape Verde', ''),
	(45, 'Cayman Islands', 'CJ', 'KY', 'CYM', '136', 'KY', 'George Town ', 'Central America and the Caribbean ', 'Caymanian', 'Caymanians', 'Cayman Islands Dollar', 'KYD', 35527, 'The Cayman Islands', ''),
	(46, 'Central African Republic', 'CT', 'CF', 'CAF', '140', 'CF', 'Bangui ', 'Africa ', 'Central African', 'Central Africans', 'CFA Franc BEAC', 'XAF', 3576884, 'The Central African Republic', ''),
	(47, 'Chad', 'CD', 'TD', 'TCD', '148', 'TD', 'N''Djamena ', 'Africa ', 'Chadian', 'Chadians', 'CFA Franc BEAC', 'XAF', 8707078, 'Chad', ''),
	(48, 'Chile', 'CI', 'CL', 'CHL', '152', 'CL', 'Santiago ', 'South America ', 'Chilean', 'Chileans', 'Chilean Peso ', 'CLP', 15328467, 'Chile', ''),
	(49, 'China', 'CH', 'CN', 'CHN', '156', 'CN', 'Beijing ', 'Asia ', 'Chinese', 'Chinese', 'Yuan Renminbi', 'CNY', 1273111290, 'China', 'see also Taiwan'),
	(50, 'Christmas Island', 'KT', 'CX', 'CXR', '162', 'CX', 'The Settlement ', 'Southeast Asia ', 'Christmas Island', 'Christmas Islanders', 'Australian Dollar ', 'AUD', 2771, 'Christmas Island', ''),
	(51, 'Clipperton Island', 'IP', '--', '-- ', '--', '--', '', 'World ', '', '', '', '', 0, 'Clipperton Island', 'ISO includes with French Polynesia'),
	(52, 'Cocos (Keeling) Islands', 'CK', 'CC', 'CCK', '166', 'CC', 'West Island ', 'Southeast Asia ', 'Cocos Islander', 'Cocos Islanders', 'Australian Dollar ', 'AUD', 633, 'The Cocos Islands', ''),
	(53, 'Colombia', 'CO', 'CO', 'COL', '170', 'CO', 'Bogota ', 'South America, Central America and the Caribbean ', 'Colombian', 'Colombians', 'Colombian Peso ', 'COP', 40349388, 'Colombia', ''),
	(54, 'Comoros', 'CN', 'KM', 'COM', '174', 'KM', 'Moroni ', 'Africa ', 'Comoran', 'Comorans', 'Comoro Franc', 'KMF', 596202, 'Comoros', ''),
	(55, 'Congo, Democratic Republic of the', 'CG', 'CD', 'COD', '180', 'CD', 'Kinshasa ', 'Africa ', 'Congolese', 'Congolese', 'Franc Congolais', 'CDF', 53624718, 'Democratic Republic of the Congo', 'formerly Zaire'),
	(56, 'Congo, Republic of the', 'CF', 'CG', 'COG', '178', 'CG', 'Brazzaville ', 'Africa ', 'Congolese', 'Congolese', 'CFA Franc BEAC', 'XAF', 2894336, 'Republic of the Congo', ''),
	(57, 'Cook Islands', 'CW', 'CK', 'COK', '184', 'CK', 'Avarua ', 'Oceania ', 'Cook Islander', 'Cook Islanders', 'New Zealand Dollar ', 'NZD', 20611, 'The Cook Islands', ''),
	(58, 'Coral Sea Islands', 'CR', '--', '-- ', '--', '--', '', 'Oceania ', '', '', '', '', 0, 'The Coral Sea Islands', 'ISO includes with Australia'),
	(59, 'Costa Rica', 'CS', 'CR', 'CRI', '188', 'CR', 'San Jose ', 'Central America and the Caribbean ', 'Costa Rican', 'Costa Ricans', 'Costa Rican Colon', 'CRC', 3773057, 'Costa Rica', ''),
	(60, 'Cote d''Ivoire', 'IV', 'CI', 'CIV', '384', 'CI', 'Yamoussoukro', 'Africa ', 'Ivorian', 'Ivorians', 'CFA Franc BCEAO', 'XOF', 16393221, 'Cote d''Ivoire', ''),
	(61, 'Croatia', 'HR', 'HR', 'HRV', '191', 'HR', 'Zagreb ', 'Europe ', 'Croatian', 'Croats', 'Kuna', 'HRK', 4334142, 'Croatia', ''),
	(62, 'Cuba', 'CU', 'CU', 'CUB', '192', 'CU', 'Havana ', 'Central America and the Caribbean ', 'Cuban', 'Cubans', 'Cuban Peso', 'CUP', 11184023, 'Cuba', ''),
	(63, 'Cyprus', 'CY', 'CY', 'CYP', '196', 'CY', 'Nicosia ', 'Middle East ', 'Cypriot', 'Cypriots', 'Cyprus Pound', 'CYP', 762887, 'Cyprus', ''),
	(64, 'Czech Republic', 'EZ', 'CZ', 'CZE', '203', 'CZ', 'Prague ', 'Europe ', 'Czech', 'Czechs', 'Czech Koruna', 'CZK', 10264212, 'The Czech Republic', ''),
	(65, 'Denmark', 'DA', 'DK', 'DNK', '208', 'DK', 'Copenhagen ', 'Europe ', 'Danish', 'Danes', 'Danish Krone', 'DKK', 5352815, 'Denmark', ''),
	(66, 'Djibouti', 'DJ', 'DJ', 'DJI', '262', 'DJ', 'Djibouti ', 'Africa ', 'Djiboutian', 'Djiboutians', 'Djibouti Franc', 'DJF', 460700, 'Djibouti', ''),
	(67, 'Dominica', 'DO', 'DM', 'DMA', '212', 'DM', 'Roseau ', 'Central America and the Caribbean ', 'Dominican', 'Dominicans', 'East Caribbean Dollar', 'XCD', 70786, 'Dominica', ''),
	(68, 'Dominican Republic', 'DR', 'DO', 'DOM', '214', 'DO', 'Santo Domingo ', 'Central America and the Caribbean ', 'Dominican', 'Dominicans', 'Dominican Peso', 'DOP', 8581477, 'The Dominican Republic', ''),
	(69, 'East Timor', 'TT', 'TL', 'TLS', '626', 'TP', '', '', '', '', 'Timor Escudo', 'TPE', 1040880, 'East Timor', 'NULL'),
	(70, 'Ecuador', 'EC', 'EC', 'ECU', '218', 'EC', 'Quito ', 'South America ', 'Ecuadorian', 'Ecuadorians', 'US Dollar', 'USD', 13183978, 'Ecuador', ''),
	(71, 'Egypt', 'EG', 'EG', 'EGY', '818', 'EG', 'Cairo ', 'Africa ', 'Egyptian', 'Egyptians', 'Egyptian Pound ', 'EGP', 69536644, 'Egypt', ''),
	(72, 'El Salvador', 'ES', 'SV', 'SLV', '222', 'SV', 'San Salvador ', 'Central America and the Caribbean ', 'Salvadoran', 'Salvadorans', 'El Salvador Colon', 'SVC', 6237662, 'El Salvador', ''),
	(73, 'Equatorial Guinea', 'EK', 'GQ', 'GNQ', '226', 'GQ', 'Malabo ', 'Africa ', 'Equatorial Guinean', 'Equatorial Guineans', 'CFA Franc BEAC', 'XAF', 486060, 'Equatorial Guinea', ''),
	(74, 'Eritrea', 'ER', 'ER', 'ERI', '232', 'ER', 'Asmara ', 'Africa ', 'Eritrean', 'Eritreans', 'Nakfa', 'ERN', 4298269, 'Eritrea', ''),
	(75, 'Estonia', 'EN', 'EE', 'EST', '233', 'EE', 'Tallinn ', 'Europe ', 'Estonian', 'Estonians', 'Kroon', 'EEK', 1423316, 'Estonia', ''),
	(76, 'Ethiopia', 'ET', 'ET', 'ETH', '231', 'ET', 'Addis Ababa ', 'Africa ', 'Ethiopian', 'Ethiopians', 'Ethiopian Birr', 'ETB', 65891874, 'Ethiopia', ''),
	(77, 'Europa Island', 'EU', '--', '-- ', '--', '--', '', 'Africa ', '', '', '', '', 0, 'Europa Island', 'ISO includes with the Miscellaneous (French) Indian Ocean Islands'),
	(78, 'Falkland Islands (Islas Malvinas)', 'FK', 'FK', 'FLK', '238', 'FK', 'Stanley', 'South America', 'Falkland Island', 'Falkland Islanders', 'Falkland Islands Pound', 'FKP', 2895, 'The Falkland Islands ', ''),
	(79, 'Faroe Islands', 'FO', 'FO', 'FRO', '234', 'FO', 'Torshavn ', 'Europe ', 'Faroese', 'Faroese', 'Danish Krone ', 'DKK', 45661, 'The Faroe Islands', ''),
	(80, 'Fiji', 'FJ', 'FJ', 'FJI', '242', 'FJ', 'Suva ', 'Oceania ', 'Fijian', 'Fijians', 'Fijian Dollar ', 'FJD', 844330, 'Fiji', ''),
	(81, 'Finland', 'FI', 'FI', 'FIN', '246', 'FI', 'Helsinki ', 'Europe ', 'Finnish', 'Finns', 'Euro', 'EUR', 5175783, 'Finland', ''),
	(82, 'France', 'FR', 'FR', 'FRA', '250', 'FR', 'Paris ', 'Europe ', 'Frenchman', 'Frenchmen', 'Euro', 'EUR', 59551227, 'France', ''),
	(83, 'France, Metropolitan', '--', '--', '-- ', '--', 'FX', '', '', '', '', 'Euro', 'EUR', 0, 'Metropolitan France', 'ISO limits to the European part of France, excluding French Guiana, French Polynesia, French Southern and Antarctic Lands, Guadeloupe, Martinique, Mayotte, New Caledonia, Reunion, Saint Pierre and Miquelon, Wallis and Futuna'),
	(84, 'French Guiana', 'FG', 'GF', 'GUF', '254', 'GF', 'Cayenne ', 'South America ', 'French Guianese', 'French Guianese', 'Euro', 'EUR', 177562, 'French Guiana', ''),
	(85, 'French Polynesia', 'FP', 'PF', 'PYF', '258', 'PF', 'Papeete ', 'Oceania ', 'French Polynesian', 'French Polynesians', 'CFP Franc', 'XPF', 253506, 'French Polynesia', 'ISO includes Clipperton Island'),
	(86, 'French Southern and Antarctic Lands', 'FS', 'TF', 'ATF', '260', 'TF', '', 'Antarctic Region ', '', '', 'Euro', 'EUR', 0, 'The French Southern and Antarctic Lands', 'FIPS 10-4 does not include the French-claimed portion of Antarctica (Terre Adelie)'),
	(87, 'Gabon', 'GB', 'GA', 'GAB', '266', 'GA', 'Libreville ', 'Africa ', 'Gabonese', 'Gabonese', 'CFA Franc BEAC', 'XAF', 1221175, 'Gabon', ''),
	(89, 'Gaza Strip', 'GZ', '--', '-- ', '--', '--', '', 'Middle East ', '', '', 'New Israeli Shekel ', 'ILS', 1178119, 'The Gaza Strip', ''),
	(90, 'Georgia', 'GG', 'GE', 'GEO', '268', 'GE', 'T''bilisi ', 'Commonwealth of Independent States ', 'Georgian', 'Georgians', 'Lari', 'GEL', 4989285, 'Georgia', ''),
	(91, 'Germany', 'GM', 'DE', 'DEU', '276', 'DE', 'Berlin ', 'Europe ', 'German', 'Germans', 'Euro', 'EUR', 83029536, 'Deutschland', ''),
	(92, 'Ghana', 'GH', 'GH', 'GHA', '288', 'GH', 'Accra ', 'Africa ', 'Ghanaian', 'Ghanaians', 'Cedi', 'GHC', 19894014, 'Ghana', ''),
	(93, 'Gibraltar', 'GI', 'GI', 'GIB', '292', 'GI', 'Gibraltar ', 'Europe ', 'Gibraltar', 'Gibraltarians', 'Gibraltar Pound', 'GIP', 27649, 'Gibraltar', ''),
	(94, 'Glorioso Islands', 'GO', '--', '-- ', '--', '--', '', 'Africa ', '', '', '', '', 0, 'The Glorioso Islands', 'ISO includes with the Miscellaneous (French) Indian Ocean Islands'),
	(95, 'Greece', 'GR', 'GR', 'GRC', '300', 'GR', 'Athens ', 'Europe ', 'Greek', 'Greeks', 'Euro', 'EUR', 10623835, 'Greece', ''),
	(96, 'Greenland', 'GL', 'GL', 'GRL', '304', 'GL', 'Nuuk ', 'Arctic Region ', 'Greenlandic', 'Greenlanders', 'Danish Krone', 'DKK', 56352, 'Greenland', ''),
	(97, 'Grenada', 'GJ', 'GD', 'GRD', '308', 'GD', 'Saint George''s ', 'Central America and the Caribbean ', 'Grenadian', 'Grenadians', 'East Caribbean Dollar', 'XCD', 89227, 'Grenada', ''),
	(98, 'Guadeloupe', 'GP', 'GP', 'GLP', '312', 'GP', 'Basse-Terre ', 'Central America and the Caribbean ', 'Guadeloupe', 'Guadeloupians', 'Euro', 'EUR', 431170, 'Guadeloupe', ''),
	(99, 'Guam', 'GQ', 'GU', 'GUM', '316', 'GU', 'Hagatna', 'Oceania ', 'Guamanian', 'Guamanians', 'US Dollar', 'USD', 157557, 'Guam', ''),
	(100, 'Guatemala', 'GT', 'GT', 'GTM', '320', 'GT', 'Guatemala ', 'Central America and the Caribbean ', 'Guatemalan', 'Guatemalans', 'Quetzal', 'GTQ', 12974361, 'Guatemala', ''),
	(101, 'Guernsey', 'GK', '--', '-- ', '--', 'GG', 'Saint Peter Port ', 'Europe ', 'Channel Islander', 'Channel Islanders', 'Pound Sterling', 'GBP', 64342, 'Guernsey', 'ISO includes with the United Kingdom'),
	(102, 'Guinea', 'GV', 'GN', 'GIN', '324', 'GN', 'Conakry ', 'Africa ', 'Guinean', 'Guineans', 'Guinean Franc ', 'GNF', 7613870, 'Guinea', ''),
	(103, 'Guinea-Bissau', 'PU', 'GW', 'GNB', '624', 'GW', 'Bissau ', 'Africa ', 'Guinean', 'Guineans', 'CFA Franc BCEAO', 'XOF', 1315822, 'Guinea-Bissau', ''),
	(104, 'Guyana', 'GY', 'GY', 'GUY', '328', 'GY', 'Georgetown ', 'South America ', 'Guyanese', 'Guyanese', 'Guyana Dollar', 'GYD', 697181, 'Guyana', ''),
	(105, 'Haiti', 'HA', 'HT', 'HTI', '332', 'HT', 'Port-au-Prince ', 'Central America and the Caribbean ', 'Haitian', 'Haitians', 'Gourde', 'HTG', 6964549, 'Haiti', ''),
	(106, 'Heard Island and McDonald Islands', 'HM', 'HM', 'HMD', '334', 'HM', '', 'Antarctic Region ', '', '', 'Australian Dollar', 'AUD', 0, 'The Heard Island and McDonald Islands', ''),
	(107, 'Holy See (Vatican City)', 'VT', 'VA', 'VAT', '336', 'VA', 'Vatican City ', 'Europe ', '', '', 'Euro', 'EUR', 890, 'The Vatican City', ''),
	(108, 'Honduras', 'HO', 'HN', 'HND', '340', 'HN', 'Tegucigalpa ', 'Central America and the Caribbean ', 'Honduran', 'Hondurans', 'Lempira', 'HNL', 6406052, 'Honduras', ''),
	(109, 'Hong Kong (SAR)', 'HK', 'HK', 'HKG', '344', 'HK', '', 'Southeast Asia ', '', '', 'Hong Kong Dollar ', 'HKD', 0, 'Xianggang ', ''),
	(110, 'Howland Island', 'HQ', '--', '-- ', '--', '--', '', 'Oceania ', '', '', '', '', 7210505, 'Howland Island', 'ISO includes with the US Minor Outlying Islands'),
	(111, 'Hungary', 'HU', 'HU', 'HUN', '348', 'HU', 'Budapest ', 'Europe ', 'Hungarian', 'Hungarians', 'Forint', 'HUF', 10106017, 'Hungary', ''),
	(112, 'Iceland', 'IC', 'IS', 'ISL', '352', 'IS', 'Reykjavik ', 'Arctic Region ', 'Icelandic', 'Icelanders', 'Iceland Krona', 'ISK', 277906, 'Iceland', ''),
	(113, 'India', 'IN', 'IN', 'IND', '356', 'IN', 'New Delhi ', 'Asia ', 'Indian', 'Indians', 'Indian Rupee ', 'INR', 1029991145, 'India', ''),
	(114, 'Indonesia', 'ID', 'ID', 'IDN', '360', 'ID', 'Jakarta ', 'Southeast Asia ', 'Indonesian', 'Indonesians', 'Rupiah', 'IDR', 228437870, 'Indonesia', ''),
	(115, 'Iran', 'IR', 'IR', 'IRN', '364', 'IR', 'Tehran ', 'Middle East ', 'Iranian', 'Iranians', 'Iranian Rial', 'IRR', 66128965, 'Iran', ''),
	(116, 'Iraq', 'IZ', 'IQ', 'IRQ', '368', 'IQ', 'Baghdad ', 'Middle East ', 'Iraqi', 'Iraqis', 'Iraqi Dinar', 'IQD', 23331985, 'Iraq', ''),
	(117, 'Ireland', 'EI', 'IE', 'IRL', '372', 'IE', 'Dublin ', 'Europe ', 'Irish', 'Irishmen', 'Euro', 'EUR', 3840838, 'Ireland', ''),
	(118, 'Israel', 'IS', 'IL', 'ISR', '376', 'IL', 'Jerusalem', 'Middle East ', 'Israeli', 'Israelis', 'New Israeli Sheqel', 'ILS', 5938093, 'Israel', ''),
	(119, 'Italy', 'IT', 'IT', 'ITA', '380', 'IT', 'Rome ', 'Europe ', 'Italian', 'Italians', 'Euro', 'EUR', 57679825, 'Italia ', ''),
	(120, 'Jamaica', 'JM', 'JM', 'JAM', '388', 'JM', 'Kingston ', 'Central America and the Caribbean ', 'Jamaican', 'Jamaicans', 'Jamaican dollar ', 'JMD', 2665636, 'Jamaica', ''),
	(121, 'Jan Mayen', 'JN', '--', '-- ', '--', '--', '', 'Arctic Region ', '', '', 'Norway Kroner', 'NOK', 0, 'Jan Mayen', 'ISO includes with Svalbard'),
	(122, 'Japan', 'JA', 'JP', 'JPN', '392', 'JP', 'Tokyo ', 'Asia ', 'Japanese', 'Japanese', 'Yen ', 'JPY', 126771662, 'Japan', ''),
	(123, 'Jarvis Island', 'DQ', '--', '-- ', '--', '--', '', 'Oceania ', '', '', '', '', 0, 'Jarvis Island', 'ISO includes with the US Minor Outlying Islands'),
	(124, 'Jersey', 'JE', '--', '-- ', '--', 'JE', 'Saint Helier ', 'Europe ', 'Channel Islander', 'Channel Islanders', 'Pound Sterling', 'GBP', 89361, 'Jersey', 'ISO includes with the United Kingdom'),
	(125, 'Johnston Atoll', 'JQ', '--', '-- ', '--', '--', '', 'Oceania ', '', '', '', '', 0, 'Johnston Atoll', 'ISO includes with the US Minor Outlying Islands'),
	(126, 'Jordan', 'JO', 'JO', 'JOR', '400', 'JO', 'Amman ', 'Middle East ', 'Jordanian', 'Jordanians', 'Jordanian Dinar', 'JOD', 5153378, 'Jordan', ''),
	(127, 'Juan de Nova Island', 'JU', '--', '-- ', '--', '--', '', 'Africa ', '', '', '', '', 0, 'Juan de Nova Island', 'ISO includes with the Miscellaneous (French) Indian Ocean Islands'),
	(128, 'Kazakhstan', 'KZ', 'KZ', 'KAZ', '398', 'KZ', 'Astana ', 'Commonwealth of Independent States ', 'Kazakhstani', 'Kazakhstanis', 'Tenge', 'KZT', 16731303, 'Kazakhstan', ''),
	(129, 'Kenya', 'KE', 'KE', 'KEN', '404', 'KE', 'Nairobi ', 'Africa ', 'Kenyan', 'Kenyans', 'Kenyan shilling ', 'KES', 30765916, 'Kenya', ''),
	(130, 'Kingman Reef', 'KQ', '--', '-- ', '--', '--', '', 'Oceania ', '', '', '', '', 0, 'Kingman Reef', 'ISO includes with the US Minor Outlying Islands'),
	(131, 'Kiribati', 'KR', 'KI', 'KIR', '296', 'KI', 'Tarawa ', 'Oceania ', 'I-Kiribati', 'I-Kiribati', 'Australian dollar ', 'AUD', 94149, 'Kiribati', ''),
	(132, 'Korea, North', 'KN', 'KP', 'PRK', '408', 'KP', 'P''yongyang ', 'Asia ', 'Korean', 'Koreans', 'North Korean Won', 'KPW', 21968228, 'North Korea', ''),
	(133, 'Korea, South', 'KS', 'KR', 'KOR', '410', 'KR', 'Seoul ', 'Asia ', 'Korean', 'Koreans', 'Won', 'KRW', 47904370, 'South Korea', ''),
	(134, 'Kuwait', 'KU', 'KW', 'KWT', '414', 'KW', 'Kuwait ', 'Middle East ', 'Kuwaiti', 'Kuwaitis', 'Kuwaiti Dinar', 'KWD', 2041961, 'Al Kuwayt', ''),
	(135, 'Kyrgyzstan', 'KG', 'KG', 'KGZ', '417', 'KG', 'Bishkek ', 'Commonwealth of Independent States ', 'Kyrgyzstani', 'Kyrgyzstanis', 'Som', 'KGS', 4753003, 'Kyrgyzstan', ''),
	(136, 'Laos', 'LA', 'LA', 'LAO', '418', 'LA', 'Vientiane ', 'Southeast Asia ', 'Lao', 'Laos', 'Kip', 'LAK', 5635967, 'Laos', ''),
	(137, 'Latvia', 'LG', 'LV', 'LVA', '428', 'LV', 'Riga ', 'Europe ', 'Latvian', 'Latvians', 'Latvian Lats', 'LVL', 2385231, 'Latvia', ''),
	(138, 'Lebanon', 'LE', 'LB', 'LBN', '422', 'LB', 'Beirut ', 'Middle East ', 'Lebanese', 'Lebanese', 'Lebanese Pound', 'LBP', 3627774, 'Lebanon', ''),
	(139, 'Lesotho', 'LT', 'LS', 'LSO', '426', 'LS', 'Maseru ', 'Africa ', 'Basotho', 'Mosotho', 'Loti', 'LSL', 2177062, 'Lesotho', ''),
	(140, 'Liberia', 'LI', 'LR', 'LBR', '430', 'LR', 'Monrovia ', 'Africa ', 'Liberian', 'Liberians', 'Liberian Dollar', 'LRD', 3225837, 'Liberia', ''),
	(141, 'Libya', 'LY', 'LY', 'LBY', '434', 'LY', 'Tripoli ', 'Africa ', 'Libyan', 'Libyans', 'Libyan Dinar', 'LYD', 5240599, 'Libya', ''),
	(142, 'Liechtenstein', 'LS', 'LI', 'LIE', '438', 'LI', 'Vaduz ', 'Europe ', 'Liechtenstein', 'Liechtensteiners', 'Swiss Franc', 'CHF', 32528, 'Liechtenstein', ''),
	(143, 'Lithuania', 'LH', 'LT', 'LTU', '440', 'LT', 'Vilnius ', 'Europe ', 'Lithuanian', 'Lithuanians', 'Lithuanian Litas', 'LTL', 3610535, 'Lithuania', ''),
	(144, 'Luxembourg', 'LU', 'LU', 'LUX', '442', 'LU', 'Luxembourg ', 'Europe ', 'Luxembourg', 'Luxembourgers', 'Euro', 'EUR', 442972, 'Luxembourg', ''),
	(145, 'Macao', 'MC', 'MO', 'MAC', '446', 'MO', '', 'Southeast Asia ', 'Chinese', 'Chinese', 'Pataca', 'MOP', 453733, 'Macao', ''),
	(146, 'Macedonia, The Former Yugoslav Republic of', 'MK', 'MK', 'MKD', '807', 'MK', 'Skopje ', 'Europe ', 'Macedonian', 'Macedonians', 'Denar', 'MKD', 2046209, 'Makedonija', ''),
	(147, 'Madagascar', 'MA', 'MG', 'MDG', '450', 'MG', 'Antananarivo ', 'Africa ', 'Malagasy', 'Malagasy', 'Malagasy Franc', 'MGF', 15982563, 'Madagascar', ''),
	(148, 'Malawi', 'MI', 'MW', 'MWI', '454', 'MW', 'Lilongwe ', 'Africa ', 'Malawian', 'Malawians', 'Kwacha', 'MWK', 10548250, 'Malawi', ''),
	(149, 'Malaysia', 'MY', 'MY', 'MYS', '458', 'MY', 'Kuala Lumpur ', 'Southeast Asia ', 'Malaysian', 'Malaysians', 'Malaysian Ringgit', 'MYR', 22229040, 'Malaysia', ''),
	(150, 'Maldives', 'MV', 'MV', 'MDV', '462', 'MV', 'Male ', 'Asia ', 'Maldivian', 'Maldivians', 'Rufiyaa', 'MVR', 310764, 'Maldives', ''),
	(151, 'Mali', 'ML', 'ML', 'MLI', '466', 'ML', 'Bamako ', 'Africa ', 'Malian', 'Malians', 'CFA Franc BCEAO', 'XOF', 11008518, 'Mali', ''),
	(152, 'Malta', 'MT', 'MT', 'MLT', '470', 'MT', 'Valletta ', 'Europe ', 'Maltese', 'Maltese', 'Euro', 'EUR', 394583, 'Malta', ''),
	(153, 'Man, Isle of', 'IM', '--', '-- ', '--', 'IM', 'Douglas ', 'Europe ', 'Manxman', 'Manxmen', 'Pound Sterling', 'GBP', 73489, 'The Isle of Man', 'ISO includes with the United Kingdom'),
	(154, 'Marshall Islands', 'RM', 'MH', 'MHL', '584', 'MH', 'Majuro ', 'Oceania ', 'Marshallese', 'Marshallese', 'US Dollar', 'USD', 70822, 'The Marshall Islands', ''),
	(155, 'Martinique', 'MB', 'MQ', 'MTQ', '474', 'MQ', 'Fort-de-France ', 'Central America and the Caribbean ', 'Martiniquais', 'Martiniquais', 'Euro', 'EUR', 418454, 'Martinique', ''),
	(156, 'Mauritania', 'MR', 'MR', 'MRT', '478', 'MR', 'Nouakchott ', 'Africa ', 'Mauritanian', 'Mauritanians', 'Ouguiya', 'MRO', 2747312, 'Mauritania', ''),
	(157, 'Mauritius', 'MP', 'MU', 'MUS', '480', 'MU', 'Port Louis ', 'World ', 'Mauritian', 'Mauritians', 'Mauritius Rupee', 'MUR', 1189825, 'Mauritius', ''),
	(158, 'Mayotte', 'MF', 'YT', 'MYT', '175', 'YT', 'Mamoutzou ', 'Africa ', 'Mahorais', 'Mahorais', 'Euro', 'EUR', 163366, 'Mayotte', ''),
	(159, 'Mexico', 'MX', 'MX', 'MEX', '484', 'MX', 'Mexico ', 'North America ', 'Mexican', 'Mexicans', 'Mexican Peso', 'MXN', 101879171, 'Mexico', ''),
	(160, 'Micronesia, Federated States of', 'FM', 'FM', 'FSM', '583', 'FM', 'Palikir ', 'Oceania ', 'Micronesian', 'Micronesians', 'US Dollar', 'USD', 134597, 'The Federated States of Micronesia', ''),
	(161, 'Midway Islands', 'MQ', '--', '-- ', '--', '--', '', 'Oceania ', '', '', 'United States Dollars', 'USD', 0, 'The Midway Islands', 'ISO includes with the US Minor Outlying Islands'),
	(162, 'Miscellaneous (French)', '--', '--', '-- ', '--', '--', '', '', '', '', '', '', 0, 'Miscellaneous (French)', 'ISO includes Bassas da India, Europa Island, Glorioso Islands, Juan de Nova Island, Tromelin Island'),
	(163, 'Moldova', 'MD', 'MD', 'MDA', '498', 'MD', 'Chisinau ', 'Commonwealth of Independent States ', 'Moldovan', 'Moldovans', 'Moldovan Leu', 'MDL', 4431570, 'Moldova', ''),
	(164, 'Monaco', 'MN', 'MC', 'MCO', '492', 'MC', 'Monaco ', 'Europe ', 'Monegasque', 'Monegasques', 'Euro', 'EUR', 31842, 'Monaco', ''),
	(165, 'Mongolia', 'MG', 'MN', 'MNG', '496', 'MN', 'Ulaanbaatar ', 'Asia ', 'Mongolian', 'Mongolians', 'Tugrik', 'MNT', 2654999, 'Mongolia', ''),
	(166, 'Montenegro', '--', '--', '-- ', '--', '--', '', '', '', '', '', '', 0, 'Montenegro', 'now included as region within Yugoslavia'),
	(167, 'Montserrat', 'MH', 'MS', 'MSR', '500', 'MS', 'Plymouth', 'Central America and the Caribbean ', 'Montserratian', 'Montserratians', 'East Caribbean Dollar', 'XCD', 7574, 'Montserrat', ''),
	(168, 'Morocco', 'MO', 'MA', 'MAR', '504', 'MA', 'Rabat ', 'Africa ', 'Moroccan', 'Moroccans', 'Moroccan Dirham', 'MAD', 30645305, 'Morocco', ''),
	(169, 'Mozambique', 'MZ', 'MZ', 'MOZ', '508', 'MZ', 'Maputo ', 'Africa ', 'Mozambican', 'Mozambicans', 'Metical', 'MZM', 19371057, 'Mozambique', ''),
	(170, 'Myanmar', '--', '--', '-- ', '--', '--', '', '', '', '', 'Kyat', 'MMK', 0, 'Myanmar', 'see Burma'),
	(171, 'Namibia', 'WA', 'NA', 'NAM', '516', 'NA', 'Windhoek ', 'Africa ', 'Namibian', 'Namibians', 'Namibian Dollar ', 'NAD', 1797677, 'Namibia', ''),
	(172, 'Nauru', 'NR', 'NR', 'NRU', '520', 'NR', '', 'Oceania ', 'Nauruan', 'Nauruans', 'Australian Dollar', 'AUD', 12088, 'Nauru', ''),
	(173, 'Navassa Island', 'BQ', '--', '-- ', '--', '--', '', 'Central America and the Caribbean ', '', '', '', '', 0, 'Navassa Island', ''),
	(174, 'Nepal', 'NP', 'NP', 'NPL', '524', 'NP', 'Kathmandu ', 'Asia ', 'Nepalese', 'Nepalese', 'Nepalese Rupee', 'NPR', 25284463, 'Nepal', ''),
	(175, 'Netherlands', 'NL', 'NL', 'NLD', '528', 'NL', 'Amsterdam ', 'Europe ', 'Dutchman', 'Dutchmen', 'Euro', 'EUR', 15981472, 'The Netherlands', ''),
	(176, 'Netherlands Antilles', 'NT', 'AN', 'ANT', '530', 'AN', 'Willemstad ', 'Central America and the Caribbean ', 'Dutch Antillean', 'Dutch Antilleans', 'Netherlands Antillean guilder ', 'ANG', 212226, 'The Netherlands Antilles', ''),
	(177, 'New Caledonia', 'NC', 'NC', 'NCL', '540', 'NC', 'Noumea ', 'Oceania ', 'New Caledonian', 'New Caledonians', 'CFP Franc', 'XPF', 204863, 'New Caledonia', ''),
	(178, 'New Zealand', 'NZ', 'NZ', 'NZL', '554', 'NZ', 'Wellington ', 'Oceania ', 'New Zealand', 'New Zealanders', 'New Zealand Dollar', 'NZD', 3864129, 'New Zealand', ''),
	(179, 'Nicaragua', 'NU', 'NI', 'NIC', '558', 'NI', 'Managua ', 'Central America and the Caribbean ', 'Nicaraguan', 'Nicaraguans', 'Cordoba Oro', 'NIO', 4918393, 'Nicaragua', ''),
	(180, 'Niger', 'NG', 'NE', 'NER', '562', 'NE', 'Niamey ', 'Africa ', 'Nigerien', 'Nigeriens', 'CFA Franc BCEAO', 'XOF', 10355156, 'Niger', ''),
	(181, 'Nigeria', 'NI', 'NG', 'NGA', '566', 'NG', 'Abuja', 'Africa ', 'Nigerian', 'Nigerians', 'Naira', 'NGN', 126635626, 'Nigeria', ''),
	(182, 'Niue', 'NE', 'NU', 'NIU', '570', 'NU', 'Alofi ', 'Oceania ', 'Niuean', 'Niueans', 'New Zealand Dollar', 'NZD', 2124, 'Niue', ''),
	(183, 'Norfolk Island', 'NF', 'NF', 'NFK', '574', 'NF', 'Kingston ', 'Oceania ', 'Norfolk Islander', 'Norfolk Islanders', 'Australian Dollar', 'AUD', 1879, 'Norfolk Island', ''),
	(184, 'Northern Mariana Islands', 'CQ', 'MP', 'MNP', '580', 'MP', 'Saipan ', 'Oceania ', '', '', 'US Dollar', 'USD', 74612, 'The Northern Mariana Islands', ''),
	(185, 'Norway', 'NO', 'NO', 'NOR', '578', 'NO', 'Oslo ', 'Europe ', 'Norwegian', 'Norwegians', 'Norwegian Krone', 'NOK', 4503440, 'Norway', ''),
	(186, 'Oman', 'MU', 'OM', 'OMN', '512', 'OM', 'Muscat ', 'Middle East ', 'Omani', 'Omanis', 'Rial Omani', 'OMR', 2622198, 'Oman', ''),
	(187, 'Pakistan', 'PK', 'PK', 'PAK', '586', 'PK', 'Islamabad ', 'Asia ', 'Pakistani', 'Pakistanis', 'Pakistan Rupee', 'PKR', 144616639, 'Pakistan', ''),
	(188, 'Palau', 'PS', 'PW', 'PLW', '585', 'PW', 'Koror ', 'Oceania ', 'Palauan', 'Palauans', 'US Dollar', 'USD', 19092, 'Palau', ''),
	(275, 'Palestinian Territory, Occupied', '--', 'PS', 'PSE', '275', 'PS', '', '', '', '', '', '', 0, 'Palestine', 'NULL'),
	(189, 'Palmyra Atoll', 'LQ', '--', '-- ', '--', '--', '', 'Oceania ', '', '', '', '', 0, 'Palmyra Atoll', 'ISO includes with the US Minor Outlying Islands'),
	(190, 'Panama', 'PM', 'PA', 'PAN', '591', 'PA', 'Panama ', 'Central America and the Caribbean ', 'Panamanian', 'Panamanians', 'balboa ', 'PAB', 2845647, 'Panama', ''),
	(191, 'Papua New Guinea', 'PP', 'PG', 'PNG', '598', 'PG', 'Port Moresby ', 'Oceania ', 'Papua New Guinean', 'Papua New Guineans', 'Kina', 'PGK', 5049055, 'Papua New Guinea', ''),
	(192, 'Paracel Islands', 'PF', '--', '-- ', '--', '--', '', 'Southeast Asia ', '', '', '', '', 0, 'The Paracel Islands', ''),
	(193, 'Paraguay', 'PA', 'PY', 'PRY', '600', 'PY', 'Asuncion ', 'South America ', 'Paraguayan', 'Paraguayans', 'Guarani', 'PYG', 5734139, 'Paraguay', ''),
	(194, 'Peru', 'PE', 'PE', 'PER', '604', 'PE', 'Lima ', 'South America ', 'Peruvian', 'Peruvians', 'Nuevo Sol', 'PEN', 27483864, 'Peru', ''),
	(195, 'Philippines', 'RP', 'PH', 'PHL', '608', 'PH', 'Manila ', 'Southeast Asia ', 'Philippine', 'Filipinos', 'Philippine Peso', 'PHP', 82841518, 'The Philippines', ''),
	(196, 'Pitcairn Islands', 'PC', 'PN', 'PCN', '612', 'PN', 'Adamstown ', 'Oceania ', 'Pitcairn Islander', 'Pitcairn Islanders', 'New Zealand Dollar', 'NZD', 47, 'The Pitcairn Islands', ''),
	(197, 'Poland', 'PL', 'PL', 'POL', '616', 'PL', 'Warsaw ', 'Europe ', 'Polish', 'Poles', 'Zloty', 'PLN', 38633912, 'Poland', ''),
	(198, 'Portugal', 'PO', 'PT', 'PRT', '620', 'PT', 'Lisbon ', 'Europe ', 'Portuguese', 'Portuguese', 'Euro', 'EUR', 10066253, 'Portugal', ''),
	(199, 'Puerto Rico', 'RQ', 'PR', 'PRI', '630', 'PR', 'San Juan ', 'Central America and the Caribbean ', 'Puerto Rican', 'Puerto Ricans', 'US Dollar', 'USD', 3937316, 'Puerto Rico', ''),
	(200, 'Qatar', 'QA', 'QA', 'QAT', '634', 'QA', 'Doha ', 'Middle East ', 'Qatari', 'Qataris', 'Qatari Rial', 'QAR', 769152, 'Qatar', ''),
	(201, 'Romania', 'RO', 'RO', 'ROU', '642', 'RO', 'Bucharest ', 'Europe ', 'Romanian', 'Romanians', 'Leu', 'ROL', 22364022, 'Romania', ''),
	(202, 'Russia', 'RS', 'RU', 'RUS', '643', 'RU', 'Moscow ', 'Asia ', 'Russian', 'Russians', 'Russian Ruble', 'RUB', 145470197, 'Russia', ''),
	(203, 'Rwanda', 'RW', 'RW', 'RWA', '646', 'RW', 'Kigali ', 'Africa ', 'Rwandan', 'Rwandans', 'Rwanda Franc', 'RWF', 7312756, 'Rwanda', ''),
	(204, 'Saint Helena', 'SH', 'SH', 'SHN', '654', 'SH', 'Jamestown ', 'Africa ', 'Saint Helenian', 'Saint Helenians', 'Saint Helenian Pound ', 'SHP', 7266, 'Saint Helena', ''),
	(205, 'Saint Kitts and Nevis', 'SC', 'KN', 'KNA', '659', 'KN', 'Basseterre ', 'Central America and the Caribbean ', 'Kittitian and Nevisian', 'Kittitians and Nevisians', 'East Caribbean Dollar ', 'XCD', 38756, 'Saint Kitts and Nevis', ''),
	(206, 'Saint Lucia', 'ST', 'LC', 'LCA', '662', 'LC', 'Castries ', 'Central America and the Caribbean ', 'Saint Lucian', 'Saint Lucians', 'East Caribbean Dollar ', 'XCD', 158178, 'Saint Lucia', ''),
	(207, 'Saint Pierre and Miquelon', 'SB', 'PM', 'SPM', '666', 'PM', 'Saint-Pierre ', 'North America ', 'Frenchman', 'Frenchmen', 'Euro', 'EUR', 6928, 'Saint Pierre and Miquelon', ''),
	(208, 'Saint Vincent and the Grenadines', 'VC', 'VC', 'VCT', '670', 'VC', 'Kingstown ', 'Central America and the Caribbean ', 'Saint Vincentian', 'Saint Vincentians', 'East Caribbean Dollar ', 'XCD', 115942, 'Saint Vincent and the Grenadines', ''),
	(209, 'Samoa', 'WS', 'WS', 'WSM', '882', 'WS', 'Apia ', 'Oceania ', 'Samoan', 'Samoans', 'Tala', 'WST', 179058, 'Samoa', 'NULL'),
	(210, 'San Marino', 'SM', 'SM', 'SMR', '674', 'SM', 'San Marino ', 'Europe ', 'Sammarinese', 'Sammarinese', 'Euro', 'EUR', 27336, 'San Marino', ''),
	(211, 'Saudi Arabia', 'SA', 'SA', 'SAU', '682', 'SA', 'Riyadh ', 'Middle East ', 'Saudi Arabian ', 'Saudis', 'Saudi Riyal', 'SAR', 22757092, 'Saudi Arabia', ''),
	(212, 'Senegal', 'SG', 'SN', 'SEN', '686', 'SN', 'Dakar ', 'Africa ', 'Senegalese', 'Senegalese', 'CFA Franc BCEAO', 'XOF', 10284929, 'Senegal', ''),
	(213, 'Serbia', '--', '--', '-- ', '--', '--', '', '', '', '', '', '', 0, 'Serbia', 'now included as region within Yugoslavia'),
	(214, 'Serbia and Montenegro', '--', '--', '-- ', '--', '--', '', '', '', '', '', '', 0, 'Serbia and Montenegro', 'See Yugoslavia'),
	(215, 'Seychelles', 'SE', 'SC', 'SYC', '690', 'SC', 'Victoria ', 'Africa ', 'Seychellois', 'Seychellois', 'Seychelles Rupee', 'SCR', 79715, 'Seychelles', ''),
	(216, 'Sierra Leone', 'SL', 'SL', 'SLE', '694', 'SL', 'Freetown ', 'Africa ', 'Sierra Leonean', 'Sierra Leoneans', 'Leone', 'SLL', 5426618, 'Sierra Leone', ''),
	(217, 'Singapore', 'SN', 'SG', 'SGP', '702', 'SG', 'Singapore ', 'Southeast Asia ', 'Singaporeian', 'Singaporeans', 'Singapore Dollar', 'SGD', 4300419, 'Singapore', ''),
	(218, 'Slovakia', 'LO', 'SK', 'SVK', '703', 'SK', 'Bratislava ', 'Europe ', 'Slovakian', 'Slovaks', 'Euro', 'EUR', 5414937, 'Slovakia', ''),
	(219, 'Slovenia', 'SI', 'SI', 'SVN', '705', 'SI', 'Ljubljana ', 'Europe ', 'Slovenian', 'Slovenes', 'Euro', 'EUR', 1930132, 'Slovenia', ''),
	(220, 'Solomon Islands', 'BP', 'SB', 'SLB', '90', 'SB', 'Honiara ', 'Oceania ', 'Solomon Islander', 'Solomon Islanders', 'Solomon Islands Dollar', 'SBD', 480442, 'The Solomon Islands', ''),
	(221, 'Somalia', 'SO', 'SO', 'SOM', '706', 'SO', 'Mogadishu ', 'Africa ', 'Somali', 'Somalis', 'Somali Shilling', 'SOS', 7488773, 'Somalia', ''),
	(222, 'South Africa', 'SF', 'ZA', 'ZAF', '710', 'ZA', 'Pretoria', 'Africa ', 'South African', 'South Africans', 'Rand', 'ZAR', 43586097, 'South Africa', ''),
	(223, 'South Georgia and the South Sandwich Islands', 'SX', 'GS', 'SGS', '239', 'GS', '', 'Antarctic Region ', '', '', 'Pound Sterling', 'GBP', 0, 'The South Georgia and the South Sandwich Islands', ''),
	(224, 'Spain', 'SP', 'ES', 'ESP', '724', 'ES', 'Madrid ', 'Europe ', 'Spanish', 'Spaniards', 'Euro', 'EUR', 40037995, 'Spain', ''),
	(225, 'Spratly Islands', 'PG', '--', '-- ', '--', '--', '', 'Southeast Asia ', '', '', '', '', 0, 'The Spratly Islands', ''),
	(226, 'Sri Lanka', 'CE', 'LK', 'LKA', '144', 'LK', 'Colombo', 'Asia ', 'Sri Lankan', 'Sri Lankans', 'Sri Lanka Rupee', 'LKR', 19408635, 'Sri Lanka', ''),
	(227, 'Sudan', 'SU', 'SD', 'SDN', '736', 'SD', 'Khartoum ', 'Africa ', 'Sudanese', 'Sudanese', 'Sudanese Dinar', 'SDD', 36080373, 'Sudan', ''),
	(228, 'Suriname', 'NS', 'SR', 'SUR', '740', 'SR', 'Paramaribo ', 'South America ', 'Surinamese', 'Surinamers', 'Suriname Guilder', 'SRG', 433998, 'Suriname', ''),
	(229, 'Svalbard', 'SV', 'SJ', 'SJM', '744', 'SJ', 'Longyearbyen ', 'Arctic Region ', '', '', 'Norwegian Krone', 'NOK', 2332, 'Svalbard', 'ISO includes Jan Mayen'),
	(230, 'Swaziland', 'WZ', 'SZ', 'SWZ', '748', 'SZ', 'Mbabane ', 'Africa ', 'Swazi', 'Swazis', 'Lilangeni', 'SZL', 1104343, 'Swaziland', ''),
	(231, 'Sweden', 'SW', 'SE', 'SWE', '752', 'SE', 'Stockholm ', 'Europe ', 'Swedish', 'Swedes', 'Swedish Krona', 'SEK', 8875053, 'Sweden', ''),
	(232, 'Switzerland', 'SZ', 'CH', 'CHE', '756', 'CH', 'Bern ', 'Europe ', 'Swiss', 'Swiss', 'Swiss Franc', 'CHF', 7283274, 'Switzerland', ''),
	(233, 'Syria', 'SY', 'SY', 'SYR', '760', 'SY', 'Damascus ', 'Middle East ', 'Syrian', 'Syrians', 'Syrian Pound', 'SYP', 16728808, 'Syria', ''),
	(234, 'Taiwan', 'TW', 'TW', 'TWN', '158', 'TW', 'Taipei ', 'Southeast Asia ', 'Taiwanese', 'Taiwanese', 'New Taiwan Dollar', 'TWD', 22370461, 'Taiwan', ''),
	(235, 'Tajikistan', 'TI', 'TJ', 'TJK', '762', 'TJ', 'Dushanbe ', 'Commonwealth of Independent States ', 'Tajikistani', 'Tajikistanis', 'Somoni', 'TJS', 6578681, 'Tajikistan', ''),
	(236, 'Tanzania', 'TZ', 'TZ', 'TZA', '834', 'TZ', 'Dar es Salaam', 'Africa ', 'Tanzanian', 'Tanzanians', 'Tanzanian Shilling', 'TZS', 36232074, 'Tanzania', ''),
	(237, 'Thailand', 'TH', 'TH', 'THA', '764', 'TH', 'Bangkok ', 'Southeast Asia ', 'Thai', 'Thai', 'Baht', 'THB', 61797751, 'Thailand', ''),
	(238, 'The Bahamas', 'BF', 'BS', 'BHS', '44', 'BS', 'Nassau ', 'Central America and the Caribbean ', 'Bahamian', 'Bahamians', 'Bahamian Dollar ', 'BSD', 297852, 'The Bahamas', ''),
	(239, 'The Gambia', 'GA', 'GM', 'GMB', '270', 'GM', 'Banjul ', 'Africa ', 'Gambian', 'Gambians', 'Dalasi', 'GMD', 1411205, 'The Gambia', ''),
	(240, 'Togo', 'TO', 'TG', 'TGO', '768', 'TG', 'Lome ', 'Africa ', 'Togolese', 'Togolese', 'CFA Franc BCEAO', 'XOF', 5153088, 'Togo', ''),
	(241, 'Tokelau', 'TL', 'TK', 'TKL', '772', 'TK', '', 'Oceania ', 'Tokelauan', 'Tokelauans', 'New Zealand Dollar', 'NZD', 1445, 'Tokelau', ''),
	(242, 'Tonga', 'TN', 'TO', 'TON', '776', 'TO', 'Nuku''alofa ', 'Oceania ', 'Tongan', 'Tongans', 'Pa''anga', 'TOP', 104227, 'Tonga', ''),
	(243, 'Trinidad and Tobago', 'TD', 'TT', 'TTO', '780', 'TT', 'Port-of-Spain ', 'Central America and the Caribbean ', 'Trinidadian and Tobagonian', 'Trinidadians and Tobagonians', 'Trinidad and Tobago Dollar', 'TTD', 1169682, 'Trinidad and Tobago', ''),
	(244, 'Tromelin Island', 'TE', '--', '-- ', '--', '--', '', 'Africa ', '', '', '', '', 0, 'Tromelin Island', 'ISO includes with the Miscellaneous (French) Indian Ocean Islands'),
	(245, 'Tunisia', 'TS', 'TN', 'TUN', '788', 'TN', 'Tunis ', 'Africa ', 'Tunisian', 'Tunisians', 'Tunisian Dinar', 'TND', 9705102, 'Tunisia', ''),
	(246, 'Turkey', 'TU', 'TR', 'TUR', '792', 'TR', 'Ankara ', 'Middle East ', 'Turkish', 'Turks', 'Turkish Lira', 'TRL', 66493970, 'Turkey', ''),
	(247, 'Turkmenistan', 'TX', 'TM', 'TKM', '795', 'TM', 'Ashgabat ', 'Commonwealth of Independent States ', 'Turkmen', 'Turkmens', 'Manat', 'TMM', 4603244, 'Turkmenistan', ''),
	(248, 'Turks and Caicos Islands', 'TK', 'TC', 'TCA', '796', 'TC', 'Cockburn Town ', 'Central America and the Caribbean ', '', '', 'US Dollar', 'USD', 18122, 'The Turks and Caicos Islands', ''),
	(249, 'Tuvalu', 'TV', 'TV', 'TUV', '798', 'TV', 'Funafuti ', 'Oceania ', 'Tuvaluan', 'Tuvaluans', 'Australian Dollar', 'AUD', 10991, 'Tuvalu', ''),
	(250, 'Uganda', 'UG', 'UG', 'UGA', '800', 'UG', 'Kampala ', 'Africa ', 'Ugandan', 'Ugandans', 'Uganda Shilling', 'UGX', 23985712, 'Uganda', ''),
	(251, 'Ukraine', 'UP', 'UA', 'UKR', '804', 'UA', 'Kiev ', 'Commonwealth of Independent States ', 'Ukrainian', 'Ukrainians', 'Hryvnia', 'UAH', 48760474, 'The Ukraine', ''),
	(252, 'United Arab Emirates', 'AE', 'AE', 'ARE', '784', 'AE', 'Abu Dhabi ', 'Middle East ', 'Emirati', 'Emiratis', 'UAE Dirham', 'AED', 2407460, 'The United Arab Emirates', ''),
	(253, 'United Kingdom', 'UK', 'GB', 'GBR', '826', 'UK', 'London ', 'Europe ', 'British', 'Britons', 'Pound Sterling', 'GBP', 59647790, 'The United Kingdom', 'ISO includes Guernsey, Isle of Man, Jersey'),
	(254, 'United States', 'US', 'US', 'USA', '840', 'US', 'Washington, DC ', 'North America ', 'American', 'Americans', 'US Dollar', 'USD', 278058881, 'The United States', ''),
	(255, 'United States Minor Outlying Islands', '--', 'UM', 'UMI', '581', 'UM', '', '', '', '', 'US Dollar', 'USD', 0, 'The United States Minor Outlying Islands', 'ISO includes Baker Island, Howland Island, Jarvis Island, Johnston Atoll, Kingman Reef, Midway Islands, Palmyra Atoll, Wake Island'),
	(256, 'Uruguay', 'UY', 'UY', 'URY', '858', 'UY', 'Montevideo ', 'South America ', 'Uruguayan', 'Uruguayans', 'Peso Uruguayo', 'UYU', 3360105, 'Uruguay', ''),
	(257, 'Uzbekistan', 'UZ', 'UZ', 'UZB', '860', 'UZ', 'Tashkent', 'Commonwealth of Independent States ', 'Uzbekistani', 'Uzbekistanis', 'Uzbekistan Sum', 'UZS', 25155064, 'Uzbekistan', ''),
	(258, 'Vanuatu', 'NH', 'VU', 'VUT', '548', 'VU', 'Port-Vila ', 'Oceania ', 'Ni-Vanuatu', 'Ni-Vanuatu', 'Vatu', 'VUV', 192910, 'Vanuatu', ''),
	(259, 'Venezuela', 'VE', 'VE', 'VEN', '862', 'VE', 'Caracas ', 'South America, Central America and the Caribbean ', 'Venezuelan', 'Venezuelans', 'Bolivar', 'VEB', 23916810, 'Venezuela', ''),
	(260, 'Vietnam', 'VM', 'VN', 'VNM', '704', 'VN', 'Hanoi ', 'Southeast Asia ', 'Vietnamese', 'Vietnamese', 'Dong', 'VND', 79939014, 'Vietnam', ''),
	(261, 'Virgin Islands', 'VQ', 'VI', 'VIR', '850', 'VI', 'Charlotte Amalie ', 'Central America and the Caribbean ', 'Virgin Islander', 'Virgin Islanders', 'US Dollar', 'USD', 122211, 'The Virgin Islands', ''),
	(264, 'Wake Island', 'WQ', '--', '-- ', '--', '--', '', 'Oceania ', '', '', 'US Dollar', 'USD', 0, 'Wake Island', 'ISO includes with the US Minor Outlying Islands'),
	(265, 'Wallis and Futuna', 'WF', 'WF', 'WLF', '876', 'WF', 'Mata-Utu', 'Oceania ', 'Wallis and Futuna Islander', 'Wallis and Futuna Islanders', 'CFP Franc', 'XPF', 15435, 'Wallis and Futuna', ''),
	(266, 'West Bank', 'WE', '--', '-- ', '--', '--', '', 'Middle East ', '', '', 'New Israeli Shekel ', 'ILS', 2090713, 'The West Bank', ''),
	(267, 'Western Sahara', 'WI', 'EH', 'ESH', '732', 'EH', '', 'Africa ', 'Sahrawian', 'Sahrawis', 'Moroccan Dirham', 'MAD', 250559, 'Western Sahara', ''),
	(268, 'Western Samoa', '--', '--', '-- ', '--', '--', '', '', '', '', 'Tala', 'WST', 0, 'Western Samoa', 'see Samoa'),
	(269, 'Yemen', 'YM', 'YE', 'YEM', '887', 'YE', 'Sanaa ', 'Middle East ', 'Yemeni', 'Yemenis', 'Yemeni Rial', 'YER', 18078035, 'Yemen', ''),
	(270, 'Yugoslavia', 'YI', 'YU', 'YUG', '891', 'YU', 'Belgrade ', 'Europe ', 'Serbian', 'Serbs', 'Yugoslavian Dinar ', 'YUM', 10677290, 'Yugoslavia', 'NULL'),
	(271, 'Zaire', '--', '--', '-- ', '--', '--', '', '', '', '', '', '', 0, 'Zaire', 'see Democratic Republic of the Congo'),
	(272, 'Zambia', 'ZA', 'ZM', 'ZWB', '894', 'ZM', 'Lusaka ', 'Africa ', 'Zambian', 'Zambians', 'Kwacha', 'ZMK', 9770199, 'Zambia', ''),
	(273, 'Zimbabwe', 'ZI', 'ZW', 'ZWE', '716', 'ZW', 'Harare ', 'Africa ', 'Zimbabwean', 'Zimbabweans', 'Zimbabwe Dollar', 'ZWD', 11365366, 'Zimbabwe', '')";
			$countries_insert = apply_filters('geodir_before_country_data_insert' , $countries_insert)	 ;		
			$wpdb->query($countries_insert);
		
		}
		
				
		// Table for storing location attribute - these are user defined
	
		$icon_table = "CREATE TABLE ".GEODIR_ICON_TABLE." (
						id int NOT NULL AUTO_INCREMENT,
						post_id int( 10 ) NOT NULL,
						post_title varchar(254) NOT NULL,
						cat_id int( 10 ) NOT NULL,
						json text NOT NULL,
						PRIMARY KEY  (id)
						) $collate ";
		
		$icon_table = apply_filters('geodir_before_icon_table_create' , $icon_table) ;		
						
		dbDelta($icon_table);
		
		// Table for storing post custom fields - these are user defined
		
		$post_custom_fields = "CREATE TABLE ".GEODIR_CUSTOM_FIELDS_TABLE." (
							  id int(11) NOT NULL AUTO_INCREMENT,
							  post_type varchar(100) NULL,
							  data_type varchar(100) NULL DEFAULT NULL,
							  field_type varchar(255) NOT NULL COMMENT 'text,checkbox,radio,select,textarea',
							  admin_title varchar(255) NULL DEFAULT NULL,
							  admin_desc text NULL DEFAULT NULL,
							  site_title varchar(255) NULL DEFAULT NULL,
							  htmlvar_name varchar(255) NULL DEFAULT NULL,
							  default_value text NULL DEFAULT NULL,
							  sort_order int(11) NOT NULL,
							  option_values text NULL DEFAULT NULL,
							  clabels text NULL DEFAULT NULL,
							  is_active enum( '0', '1' ) NOT NULL DEFAULT '1',
							  is_default enum( '0', '1' ) NOT NULL DEFAULT '0',
							  is_admin enum( '0', '1' ) NOT NULL DEFAULT '0',
							  is_required enum( '0', '1' ) NOT NULL DEFAULT '0',
							  required_msg varchar(255) NULL DEFAULT NULL,
							  show_on_listing enum( '0', '1' ) NOT NULL DEFAULT '1',
							  show_on_detail enum( '0', '1' ) NOT NULL DEFAULT '1',
							  show_as_tab enum( '0', '1' ) NOT NULL DEFAULT '0',
							  for_admin_use enum( '0', '1' ) NOT NULL DEFAULT '0',
							  packages varchar(255) NOT NULL DEFAULT ',0,',
							  cat_sort text NULL DEFAULT NULL,
							  cat_filter text NULL DEFAULT NULL,
							  extra_fields text NULL DEFAULT NULL,
							  field_icon varchar(255) NULL DEFAULT NULL,
							  css_class varchar(255) NULL DEFAULT NULL,
							  decimal_point varchar( 10 ) NOT NULL,
							  PRIMARY KEY  (id)
							  ) $collate";
		
		$post_custom_fields = apply_filters('geodir_before_custom_field_table_create' , $post_custom_fields) ;		
		
		dbDelta($post_custom_fields);
		
		// Table for storing place attribute - these are user defined
		$post_detail = "CREATE TABLE ".$plugin_prefix."gd_place_detail (
						post_id int(11) NOT NULL,
						post_title text NULL DEFAULT NULL,
						post_status varchar(20) NULL DEFAULT NULL,
						default_category INT NULL DEFAULT NULL,
						post_tags varchar(254) NULL DEFAULT NULL,
						post_location_id int(11) NOT NULL,
						marker_json text NULL DEFAULT NULL,
						claimed enum( '1', '0' ) NULL DEFAULT '0',
						businesses enum( '1', '0' ) NULL DEFAULT '0',
						is_featured enum( '1', '0' ) NULL DEFAULT '0',
						featured_image varchar( 254 ) NULL DEFAULT NULL,
						paid_amount double NOT NULL DEFAULT '0', 
						package_id int(11) NOT NULL DEFAULT '0',
						alive_days int(11) NOT NULL DEFAULT '0',
						paymentmethod varchar(30) NULL DEFAULT NULL,
					 	expire_date varchar( 25 ) NULL DEFAULT NULL,
						submit_time varchar(15) NULL DEFAULT NULL,
						submit_ip varchar(20) NULL DEFAULT NULL,
						overall_rating float(11) DEFAULT '0', 
						rating_count int(11) DEFAULT '0', 
						post_locations varchar( 254 ) NULL DEFAULT NULL,
						post_dummy enum( '1', '0' ) NULL DEFAULT '0', 
						PRIMARY KEY  (post_id)
						) $collate ";
		$post_detail = apply_filters('geodir_before_post_detail_table_create' , $post_detail) ;		
						
		dbDelta($post_detail);
		
		// alter post_title
		//$wpdb->query("ALTER TABLE ".$wpdb->prefix."geodir_gd_place_detail MODIFY `post_title` text NULL");
		
		// Table for storing place images - these are user defined
		
		$attechment_table = "CREATE TABLE ".GEODIR_ATTACHMENT_TABLE." (
						ID int(11) NOT NULL AUTO_INCREMENT,
						post_id int(11) NOT NULL,
						title varchar(254) NULL DEFAULT NULL,
						content text NULL DEFAULT NULL,
						file varchar(254) NOT NULL, 
						mime_type varchar(150) NOT NULL,
						menu_order int(11) NOT NULL DEFAULT '0',
						is_featured enum( '1', '0' ) NULL DEFAULT '0',
						metadata text NULL DEFAULT NULL,
						PRIMARY KEY  (ID)
						) $collate ";
		$attechment_table = apply_filters('geodir_before_attachment_table_create' , $attechment_table) ;		
						
		dbDelta($attechment_table);
		
		
		$custom_sort_fields_table = "CREATE TABLE ".GEODIR_CUSTOM_SORT_FIELDS_TABLE." (
			id int(11) NOT NULL AUTO_INCREMENT,
			post_type varchar(255) NOT NULL,
			data_type varchar(255) NOT NULL,
			field_type varchar(255) NOT NULL,
			site_title varchar(255) NOT NULL,
			htmlvar_name varchar(255) NOT NULL,
			sort_order int(11) NOT NULL,
			is_active int(11) NOT NULL,
			is_default int(11) NOT NULL,
			default_order varchar(255) NOT NULL,
			sort_asc int(11) NOT NULL,
			sort_desc int(11) NOT NULL,
			asc_title varchar(255) NOT NULL,
			desc_title varchar(255) NOT NULL,
			PRIMARY KEY  (id)
			) $collate ";
			
		$custom_sort_fields_table = apply_filters('geodir_before_sort_fields_table_create' , $custom_sort_fields_table) ;		
						
		dbDelta($custom_sort_fields_table);
		
		
				
		if($wpdb->get_var("SHOW TABLES LIKE '".GEODIR_REVIEW_TABLE."'") != GEODIR_REVIEW_TABLE) 
		{ 
			$review_table ="CREATE TABLE IF NOT EXISTS ".GEODIR_REVIEW_TABLE."(
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`post_id` INT(11) DEFAULT NULL,
			`post_title` VARCHAR( 255 ) NULL DEFAULT NULL,
			`post_type` VARCHAR( 255 ) NULL DEFAULT NULL,
			`user_id` INT(11) DEFAULT NULL,
			`comment_id` INT(11) DEFAULT NULL,
			`rating_ip` VARCHAR( 50 ) NULL DEFAULT NULL,
			`ratings` TEXT NULL DEFAULT NULL,
			`overall_rating` float(11) DEFAULT NULL,
			`comment_images` TEXT NULL DEFAULT NULL,
			`wasthis_review` INT NOT NULL,
			`status` VARCHAR( 100 ) NOT NULL,
			`post_status` INT(11) DEFAULT NULL,
			`post_date` DATETIME NOT NULL,
			`post_city` varchar(30) NULL DEFAULT NULL,
			`post_region` varchar(30) NULL DEFAULT NULL,
			`post_country` varchar(30) NULL DEFAULT NULL,
			`post_latitude` varchar(20) NULL DEFAULT NULL,
			`post_longitude` varchar(20) NULL DEFAULT NULL,
			`comment_content` TEXT NULL DEFAULT NULL,
			PRIMARY KEY (id)) $collate  " ;
			$review_table = apply_filters('geodir_before_review_table_create' , $review_table) ;		
			$wpdb->query($review_table);
		}
		
		if(isset($review_table))
			$wpdb->show_errors($review_table);
		
		
	
	
		// Alter terms table
		$term_icon_column=$wpdb->get_var("SHOW COLUMNS FROM $wpdb->terms where field='term_icon'");
		if(!$term_icon_column)
		{
			$wpdb->query("ALTER TABLE $wpdb->terms ADD `term_icon` TEXT NULL DEFAULT NULL");
		}
		
		//require_once(geodir_plugin_path() . '/upgrade.php');
			
	
	}
} // END MAIN FUNCTION geodir_tables_install

if (!function_exists('geodir_create_default_fields')) {
function geodir_create_default_fields(){
	
	$fields = array();
	
	$fields[]	= array(	'listing_type' 	=> 'gd_place', 
							'data_type' 	=> 'VARCHAR', 
							'field_type' 	=> 'taxonomy', 
							'admin_title' 	=> __('Category',  GEODIRECTORY_TEXTDOMAIN ), 
							'admin_desc' 	=> __('Select listing category from here. Select at least one category' ,  GEODIRECTORY_TEXTDOMAIN ), 
							'site_title' 	=> __('Category', GEODIRECTORY_TEXTDOMAIN ), 
							'htmlvar_name' 	=> 'gd_placecategory', 
							'default_value'	=> '', 
							'is_default'  	=> '1',
							'is_admin'			=> '1',
							'is_required'	=> '1', 
							'clabels'		=> __('Category', GEODIRECTORY_TEXTDOMAIN ));
	
	$fields[]	= array(	'listing_type' 	=> 'gd_place', 
							'data_type' 	=> 'VARCHAR', 
							'field_type' 	=> 'address', 
							'admin_title' 	=> __('Address',  GEODIRECTORY_TEXTDOMAIN  ) , 
							'admin_desc' 	=> ADDRESS_MSG, 
							'site_title' 	=> __('Address',  GEODIRECTORY_TEXTDOMAIN ), 
							'htmlvar_name' 	=> 'post', 
							'default_value'	=> '', 
							'option_values' => '', 
							'is_default'  	=> '1',
							'is_admin'			=> '1',
							'is_required'	=> '1',
							'required_msg'	=> __('Address fields are required',  GEODIRECTORY_TEXTDOMAIN  ),
							'clabels'		=> __('Address',  GEODIRECTORY_TEXTDOMAIN ),
							'extra'	=> array(	'show_city'=> 1 , 'city_lable' => __('City',  GEODIRECTORY_TEXTDOMAIN ),
												'show_region' => 1, 'region_lable' => __('Region',  GEODIRECTORY_TEXTDOMAIN ),
												'show_country' => 1, 'country_lable' => __('Country',  GEODIRECTORY_TEXTDOMAIN ),
												'show_zip' => 1, 'zip_lable' => __('Zip/Post Code',  GEODIRECTORY_TEXTDOMAIN ),
												'show_map' => 1, 'map_lable' => __('Set Address On Map',  GEODIRECTORY_TEXTDOMAIN ),
												'show_mapview' => 1, 'mapview_lable' => __('Select Map View' ,  GEODIRECTORY_TEXTDOMAIN ),
												'show_mapzoom' => 1, 'mapzoom_lable' => 'hidden',
												'show_latlng' => 1));
							
	$fields[]	= array(	'listing_type' 	=> 'gd_place', 
							'data_type' 	=> 'VARCHAR', 
							'field_type' 	=> 'text', 
							'admin_title' 	=> __('Time',  GEODIRECTORY_TEXTDOMAIN ), 
							'admin_desc' 	=> __('Enter Business or Listing Timing Information.<br/>eg. : 10.00 am to 6 pm every day',  GEODIRECTORY_TEXTDOMAIN ), 
							'site_title' 	=> __('Time' , GEODIRECTORY_TEXTDOMAIN ), 
							'htmlvar_name' 	=> 'timing', 
							'default_value'	=> '', 
							'option_values' => '',
							'is_default'  	=> '1',
							'is_admin'			=> '1', 
							'clabels'		=> __('Time',  GEODIRECTORY_TEXTDOMAIN ));
	
	$fields[]	= array(	'listing_type' 	=> 'gd_place', 
							'data_type' 	=> 'VARCHAR', 
							'field_type' 	=> 'phone', 
							'admin_title' 	=> __('Phone' ,  GEODIRECTORY_TEXTDOMAIN ), 
							'admin_desc' 	=> __('You can enter phone number,cell phone number etc.',  GEODIRECTORY_TEXTDOMAIN ), 
							'site_title' 	=> __('Phone',  GEODIRECTORY_TEXTDOMAIN  ) , 
							'htmlvar_name' 	=> 'contact', 
							'default_value'	=> '', 
							'option_values' => '',
							'is_default'  	=> '1',
							'is_admin'			=> '1',
							'clabels'		=> __('Phone',  GEODIRECTORY_TEXTDOMAIN ));
	
	$fields[]	= array(	'listing_type' 	=> 'gd_place', 
							'data_type' 	=> 'VARCHAR', 
							'field_type' 	=> 'email', 
							'admin_title' 	=> __('Email',  GEODIRECTORY_TEXTDOMAIN ) , 
							'admin_desc' 	=> __('You can enter your business or listing email.',  GEODIRECTORY_TEXTDOMAIN ), 
							'site_title' 	=> __('Email' ,  GEODIRECTORY_TEXTDOMAIN ), 
							'htmlvar_name' 	=> 'email', 
							'default_value'	=> '', 
							'option_values' => '',
							'is_default'  	=> '1',
							'is_admin'			=> '1', 
							'clabels'		=> __('Email',  GEODIRECTORY_TEXTDOMAIN ));												
							
	$fields[]	= array(	'listing_type' 	=> 'gd_place', 
							'data_type' 	=> 'VARCHAR', 
							'field_type' 	=> 'url', 
							'admin_title' 	=> __('Website',  GEODIRECTORY_TEXTDOMAIN ), 
							'admin_desc' 	=> __('You can enter your business or listing website.',  GEODIRECTORY_TEXTDOMAIN ), 
							'site_title' 	=> __('Website',  GEODIRECTORY_TEXTDOMAIN ), 
							'htmlvar_name' 	=> 'website', 
							'default_value'	=> '', 
							'option_values' => '',
							'is_default'  	=> '1',
							'is_admin'			=> '1', 
							'clabels'		=> __('Website',  GEODIRECTORY_TEXTDOMAIN ));
	
	$fields[]	= array(	'listing_type' 	=> 'gd_place', 
							'data_type' 	=> 'VARCHAR', 
							'field_type' 	=> 'url', 
							'admin_title' 	=> __('Twitter',  GEODIRECTORY_TEXTDOMAIN ), 
							'admin_desc' 	=> __('You can enter your business or listing twitter url.',  GEODIRECTORY_TEXTDOMAIN ), 
							'site_title' 	=> __('Twitter' ,  GEODIRECTORY_TEXTDOMAIN ) , 
							'htmlvar_name' 	=> 'twitter', 
							'default_value'	=> '', 
							'option_values' => '',
							'is_default'  	=> '1',
							'is_admin'			=> '1', 
							'clabels'		=> __('Twitter',  GEODIRECTORY_TEXTDOMAIN  ) );
	
	$fields[]	= array(	'listing_type' 	=> 'gd_place', 
							'data_type' 	=> 'VARCHAR', 
							'field_type' 	=> 'url', 
							'admin_title' 	=> __('Facebook',  GEODIRECTORY_TEXTDOMAIN ), 
							'admin_desc' 	=> __('You can enter your business or listing facebook url.',  GEODIRECTORY_TEXTDOMAIN ), 
							'site_title' 	=> __('Facebook',  GEODIRECTORY_TEXTDOMAIN ), 
							'htmlvar_name' 	=> 'facebook', 
							'default_value'	=> '', 
							'option_values' => '',
							'is_default'  	=> '1',
							'is_admin'			=> '1', 
							'clabels'		=> __('Facebook', GEODIRECTORY_TEXTDOMAIN ));
							
	$fields[]	= array(	'listing_type' 	=> 'gd_place', 
							'data_type' 	=> 'TEXT', 
							'field_type' 	=> 'textarea', 
							'admin_title' 	=> __('Video',  GEODIRECTORY_TEXTDOMAIN ), 
							'admin_desc' 	=> __('Add video code here, YouTube etc.',  GEODIRECTORY_TEXTDOMAIN ), 
							'site_title' 	=> __('Video',  GEODIRECTORY_TEXTDOMAIN ), 
							'htmlvar_name' 	=> 'video', 
							'default_value'	=> '', 
							'option_values' => '',
							'is_default'  	=> '0',
							'is_admin'			=> '1', 
							'clabels'		=> __('Video',  GEODIRECTORY_TEXTDOMAIN ));
	
	$fields[]	= array(	'listing_type' 	=> 'gd_place', 
							'data_type' 	=> 'TEXT', 
							'field_type' 	=> 'textarea', 
							'admin_title' 	=> __('Special Offers' ,  GEODIRECTORY_TEXTDOMAIN ), 
							'admin_desc' 	=> __('Note: List out any special offers (optional)' ,  GEODIRECTORY_TEXTDOMAIN ), 
							'site_title' 	=> __('Special Offers' ,  GEODIRECTORY_TEXTDOMAIN ), 
							'htmlvar_name' 	=> 'special_offers', 
							'default_value'	=> '', 
							'option_values' => '',
							'is_default'  	=> '0',
							'is_admin'			=> '1', 
							'clabels'		=> __('Special Offers' ,  GEODIRECTORY_TEXTDOMAIN ));																								
	
	$fields = apply_filters('geodir_before_default_custom_fields_saved'  , $fields)	 ;		
	foreach($fields as $field_index => $field )
	{ 
		geodir_custom_field_save( $field ); 
		
	}							
}
}