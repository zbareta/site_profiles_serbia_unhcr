		<!--
		This application (site_profiles.php) pulls SITE PROFILE data from kobo.unhcr.org, stores it in an array and displays it using charts, maps, traffic lights, plain text etc.
		For any assistance feel free to contact:

		Zeljko Bareta - IM Associate, Serbia

		work e-mail:	BARETA@unhcr.org
		private e-mail:	zbareta@gmail.com
		mobile:			+38163 366 158
		skype: 			zeljko.bareta
		-->

<html lang="en">
	<head><meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
		<!-- 
		Getting data from the kobo.unhcr.org API. Enter your USERNAME, PASSWORD and API LINK
		-->
		<?php
		$username = "user";
		$password = "pass";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_URL, 'https://kobocat.unhcr.org/bareta/forms/apo6vzP9sHTqa5eYS7cL7g/api');
		curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		$resp = curl_exec($curl);
		$locations = json_decode($resp,true);
		if($errno = curl_errno($curl)) {
			$error_message = curl_strerror($errno);
			//echo "Error ({$errno}):\n {$error_message}";
			echo "Error: Could not connect to KoBo database. Please try again later.";
		}
		curl_close($curl); 

		//Storing HTML table columns (traffic lights) in PHP variables that will be displayed based on data pulled from KoBo
		//the webdings font is used for displaying traffic lights (https://en.wikipedia.org/wiki/Webdings)
		$traffic_yellow = "<td style='width:10;font-family: webdings; font-size: 10; color: yellow'>g</td>";
		$traffic_red = "<td style='width:10;font-family: webdings; font-size: 10; color: red'>g</td>";
		$traffic_green = "<td style='width:10;font-family: webdings; font-size: 10; color: green'>g</td>";
		$traffic_gray = "<td style='width:10;font-family: webdings; font-size: 10; color: gray'>g</td>";
		
		//This creates a function getRow where $field is used to store location name data pulled from KoBo
		//the loop goes through the entire array of pulled data, compares the location name $field with the $search results POSTed through the HTML form
		//it first checks if something was POSTed 
		//when there is a match (location from the form == location name from the array), it returns the row number of the locaiton which will be referrenced to display data only for the selected location
		$field = 'GENERAL_INFO/Location_Name';
		$locationId = 0;
		if ($_SERVER["REQUEST_METHOD"] == "POST"){
			function getRow($locations, $field, $search)
				{
				   foreach($locations as $key => $location)
				   {
				      if ( $location[$field] == $search )
				         return $key;
				   }
				   return false;
				}
		//this calls the above function (if there was a POST method) and stores the selected locations row number in the $locationId variable
			$locationId = getRow($locations, 'GENERAL_INFO/Location_Name', $_POST["search"]);

		}
		//This checks if elements pulled from the KoBo array are empty (based on the $locationId and element name), and if so, provides them with default data (this is to avoid erorrs in calculatuing/displaying data later... for example, text fields are set to  "No data", 3W data to Blank etc...).
		if (!isset($locations[$locationId]['_3W/Agencies_Education'])){$locations[$locationId]['_3W/Agencies_Education']="";}
		if (!isset($locations[$locationId]['_3W/Agencies_Food_and_Nutrition'])){$locations[$locationId]['_3W/Agencies_Food_and_Nutrition']="";}
		if (!isset($locations[$locationId]['_3W/Agencies_Health'])){$locations[$locationId]['_3W/Agencies_Health']="";}
		if (!isset($locations[$locationId]['_3W/Agencies_Local_Community_Support'])){$locations[$locationId]['_3W/Agencies_Local_Community_Support']="";}
		if (!isset($locations[$locationId]['_3W/Agencies_NFI'])){$locations[$locationId]['_3W/Agencies_NFI']="";}
		if (!isset($locations[$locationId]['_3W/Agencies_Protection'])){$locations[$locationId]['_3W/Agencies_Protection']="";}
		if (!isset($locations[$locationId]['_3W/Agencies_Shelter'])){$locations[$locationId]['_3W/Agencies_Shelter']="";}
		if (!isset($locations[$locationId]['_3W/Agencies_Water_Sanitation_and_Hygene'])){$locations[$locationId]['_3W/Agencies_Water_Sanitation_and_Hygene']="";}
		if (!isset($locations[$locationId]['_3W/Agencies_Admin_Legal_Info'])){$locations[$locationId]['_3W/Agencies_Admin_Legal_Info']="";}
		if (!isset($locations[$locationId]['ASYLUM_IDENTIFICATION/Accommodated_Persons_Registers_Available'])){$locations[$locationId]['ASYLUM_IDENTIFICATION/Accommodated_Persons_Registers_Available']="No data";}
		if (!isset($locations[$locationId]['ASYLUM_IDENTIFICATION/Centres_Issuing_any_kind_of_ID'])){$locations[$locationId]['ASYLUM_IDENTIFICATION/Centres_Issuing_any_kind_of_ID']="No data";}
		if (!isset($locations[$locationId]['ASYLUM_IDENTIFICATION/Number_of_Persons_In_Applying_for_Asylum'])){$locations[$locationId]['ASYLUM_IDENTIFICATION/Number_of_Persons_In_Applying_for_Asylum']="No data";}
		if (!isset($locations[$locationId]['ASYLUM_IDENTIFICATION/Number_of_Persons_In_ng_to_Designated_ACs'])){$locations[$locationId]['ASYLUM_IDENTIFICATION/Number_of_Persons_In_ng_to_Designated_ACs']="No data";}
		if (!isset($locations[$locationId]['CHILD_PROTECTION/Access_to_CSW_Services'])){$locations[$locationId]['CHILD_PROTECTION/Access_to_CSW_Services']="No data";}
		if (!isset($locations[$locationId]['CHILD_PROTECTION/BIAs_Conducted'])){$locations[$locationId]['CHILD_PROTECTION/BIAs_Conducted']="No data";}
		if (!isset($locations[$locationId]['CHILD_PROTECTION/Separate_Accommodation_for_UASC'])){$locations[$locationId]['CHILD_PROTECTION/Separate_Accommodation_for_UASC']="No data";}
		if (!isset($locations[$locationId]['CHILD_PROTECTION/Separate_Accommodati_n_for_UAS_Boys_Girls'])){$locations[$locationId]['CHILD_PROTECTION/Separate_Accommodati_n_for_UAS_Boys_Girls']="No data";}
		if (!isset($locations[$locationId]['CHILD_PROTECTION/Can_Children_at_Risk_d_in_Foster_Families'])){$locations[$locationId]['CHILD_PROTECTION/Can_Children_at_Risk_d_in_Foster_Families']="No data";}
		if (!isset($locations[$locationId]['CHILD_PROTECTION/Child_Protection_SOP_lace_and_Functioning'])){$locations[$locationId]['CHILD_PROTECTION/Child_Protection_SOP_lace_and_Functioning']="No data";}
		if (!isset($locations[$locationId]['CHILD_PROTECTION/Designated_Child_Friendly_Space'])){$locations[$locationId]['CHILD_PROTECTION/Designated_Child_Friendly_Space']="No data";}
		if (!isset($locations[$locationId]['CHILD_PROTECTION/Designated_Mother_and_Baby_Areas'])){$locations[$locationId]['CHILD_PROTECTION/Designated_Mother_and_Baby_Areas']="No data";}
		if (!isset($locations[$locationId]['CHILD_PROTECTION/Legal_Guardian_Appointed_for_every_UASC'])){$locations[$locationId]['CHILD_PROTECTION/Legal_Guardian_Appointed_for_every_UASC']="No data";}
		if (!isset($locations[$locationId]['CHILD_PROTECTION/Outdoor_Playground'])){$locations[$locationId]['CHILD_PROTECTION/Outdoor_Playground']="No data";}
		if (!isset($locations[$locationId]['CHILD_PROTECTION/Separate_Accommodation_for_UASC'])){$locations[$locationId]['CHILD_PROTECTION/Separate_Accommodation_for_UASC']="No data";}
		if (!isset($locations[$locationId]['CHILD_PROTECTION/Staff_with_Child_Pro_ction_Skills_Present'])){$locations[$locationId]['CHILD_PROTECTION/Staff_with_Child_Pro_ction_Skills_Present']="No data";}
		if (!isset($locations[$locationId]['COMMUNICATION/Community_Structure_Established'])){$locations[$locationId]['COMMUNICATION/Community_Structure_Established']="No data";}
		if (!isset($locations[$locationId]['COMMUNICATION/Complaints_Mechanism_in_Place'])){$locations[$locationId]['COMMUNICATION/Complaints_Mechanism_in_Place']="No data";}
		if (!isset($locations[$locationId]['COMMUNICATION/House_Rules_Clearly_Displayed'])){$locations[$locationId]['COMMUNICATION/House_Rules_Clearly_Displayed']="No data";}
		if (!isset($locations[$locationId]['COMMUNICATION/Information_about_Available_Services'])){$locations[$locationId]['COMMUNICATION/Information_about_Available_Services']="No data";}
		if (!isset($locations[$locationId]['COMMUNICATION/Legal_Counselling_Available'])){$locations[$locationId]['COMMUNICATION/Legal_Counselling_Available']="No data";}
		if (!isset($locations[$locationId]['COMMUNICATION/Private_Rooms_for_Counselling'])){$locations[$locationId]['COMMUNICATION/Private_Rooms_for_Counselling']="No data";}
		if (!isset($locations[$locationId]['COMMUNICATION/Psycho_social_Counselling_Available'])){$locations[$locationId]['COMMUNICATION/Psycho_social_Counselling_Available']="No data";}
		if (!isset($locations[$locationId]['COMMUNICATION/Sufficient_Number_of_Interpreters'])){$locations[$locationId]['COMMUNICATION/Sufficient_Number_of_Interpreters']="No data";}
		if (!isset($locations[$locationId]['COORDINATION_MANAGEMENT/Date_of_Participatory_Assessment'])){$locations[$locationId]['COORDINATION_MANAGEMENT/Date_of_Participatory_Assessment']="No data";}
		if (!isset($locations[$locationId]['COORDINATION_MANAGEMENT/Office_Space_for_Caregivers'])){$locations[$locationId]['COORDINATION_MANAGEMENT/Office_Space_for_Caregivers']="No data";}
		if (!isset($locations[$locationId]['COORDINATION_MANAGEMENT/Referral_System_that_Ensures_Follow_up'])){$locations[$locationId]['COORDINATION_MANAGEMENT/Referral_System_that_Ensures_Follow_up']="No data";}
		if (!isset($locations[$locationId]['COORDINATION_MANAGEMENT/Site_specific_Coordination_Mechanism'])){$locations[$locationId]['COORDINATION_MANAGEMENT/Site_specific_Coordination_Mechanism']="No data";}
		if (!isset($locations[$locationId]['EDUCATION_LEISURE/Area_for_Educational_upational_Activities'])){$locations[$locationId]['EDUCATION_LEISURE/Area_for_Educational_upational_Activities']="No data";}
		if (!isset($locations[$locationId]['EDUCATION_LEISURE/School_Start_Allowance_In_kind'])){$locations[$locationId]['EDUCATION_LEISURE/School_Start_Allowance_In_kind']="No data";}
		if (!isset($locations[$locationId]['EDUCATION_LEISURE/Area_for_Practicing_Religion'])){$locations[$locationId]['EDUCATION_LEISURE/Area_for_Practicing_Religion']="No data";}
		if (!isset($locations[$locationId]['EDUCATION_LEISURE/Area_for_Recreational_Activities'])){$locations[$locationId]['EDUCATION_LEISURE/Area_for_Recreational_Activities']="No data";}
		if (!isset($locations[$locationId]['EDUCATION_LEISURE/Children_Attending_School'])){$locations[$locationId]['EDUCATION_LEISURE/Children_Attending_School']="No data";}
		if (!isset($locations[$locationId]['EDUCATION_LEISURE/Frequency_of_Activities_for_Adults'])){$locations[$locationId]['EDUCATION_LEISURE/Frequency_of_Activities_for_Adults']="No data";}
		if (!isset($locations[$locationId]['EDUCATION_LEISURE/Frequency_of_Activities_for_Children'])){$locations[$locationId]['EDUCATION_LEISURE/Frequency_of_Activities_for_Children']="No data";}
		if (!isset($locations[$locationId]['EDUCATION_LEISURE/Frequency_of_Language_Classes'])){$locations[$locationId]['EDUCATION_LEISURE/Frequency_of_Language_Classes']="No data";}
		if (!isset($locations[$locationId]['EDUCATION_LEISURE/Incentives_for_Parti_in_Centre_Operation'])){$locations[$locationId]['EDUCATION_LEISURE/Incentives_for_Parti_in_Centre_Operation']="No data";}
		if (!isset($locations[$locationId]['FAMILY_UNITY/Families_Women_Child_quately_Accommodated'])){$locations[$locationId]['FAMILY_UNITY/Families_Women_Child_quately_Accommodated']="No data";}
		if (!isset($locations[$locationId]['FAMILY_UNITY/Family_Unity_Maintained'])){$locations[$locationId]['FAMILY_UNITY/Family_Unity_Maintained']="No data";}
		if (!isset($locations[$locationId]['FAMILY_UNITY/Referral_Mechanism_f_Family_Reunification'])){$locations[$locationId]['FAMILY_UNITY/Referral_Mechanism_f_Family_Reunification']="No data";}
		if (!isset($locations[$locationId]['FOOD_NFI/Adequate_Cooking_Dining_Space'])){$locations[$locationId]['FOOD_NFI/Adequate_Cooking_Dining_Space']="No data";}
		if (!isset($locations[$locationId]['FOOD_NFI/Adequate_Meals'])){$locations[$locationId]['FOOD_NFI/Adequate_Meals']="No data";}
		if (!isset($locations[$locationId]['FOOD_NFI/Adequate_NFI_Distribution'])){$locations[$locationId]['FOOD_NFI/Adequate_NFI_Distribution']="No data";}
		if (!isset($locations[$locationId]['FOOD_NFI/Adequate_Storage_Distribution_Space'])){$locations[$locationId]['FOOD_NFI/Adequate_Storage_Distribution_Space']="No data";}
		if (!isset($locations[$locationId]['FOOD_NFI/Food_Prepared_On_site_or_Catered'])){$locations[$locationId]['FOOD_NFI/Food_Prepared_On_site_or_Catered']="No data";}
		if (!isset($locations[$locationId]['FOOD_NFI/Provision_of_NFIs_Ta_ed_to_Specific_Needs'])){$locations[$locationId]['FOOD_NFI/Provision_of_NFIs_Ta_ed_to_Specific_Needs']="No data";}
		if (!isset($locations[$locationId]['FREEDOM_MOVEMENT/Access_after_prolonged_absence'])){$locations[$locationId]['FREEDOM_MOVEMENT/Access_after_prolonged_absence']="No data";}
		if (!isset($locations[$locationId]['FREEDOM_MOVEMENT/Access_to_all_Public_Services'])){$locations[$locationId]['FREEDOM_MOVEMENT/Access_to_all_Public_Services']="No data";}
		if (!isset($locations[$locationId]['FREEDOM_MOVEMENT/Maximum_length_of_ab_ce_allowed_in_hours'])){$locations[$locationId]['FREEDOM_MOVEMENT/Maximum_length_of_ab_ce_allowed_in_hours']="No data";}
		if (!isset($locations[$locationId]['FREEDOM_MOVEMENT/Permits_Required_to_e_Outside_the_Centre'])){$locations[$locationId]['FREEDOM_MOVEMENT/Permits_Required_to_e_Outside_the_Centre']="No data";}
		if (!isset($locations[$locationId]['FREEDOM_MOVEMENT/Restricted_Freedom_o_nt_Inside_the_Centre'])){$locations[$locationId]['FREEDOM_MOVEMENT/Restricted_Freedom_o_nt_Inside_the_Centre']="No data";}
		if (!isset($locations[$locationId]['GENERAL_INFO/Centre_Address'])){$locations[$locationId]['GENERAL_INFO/Centre_Address']="No data";}
		if (!isset($locations[$locationId]['GENERAL_INFO/Centre_e_mail_Address'])){$locations[$locationId]['GENERAL_INFO/Centre_e_mail_Address']="No data";}
		if (!isset($locations[$locationId]['GENERAL_INFO/Centre_Phone_Number'])){$locations[$locationId]['GENERAL_INFO/Centre_Phone_Number']="No data";}
		if (!isset($locations[$locationId]['GENERAL_INFO/Data_Source'])){$locations[$locationId]['GENERAL_INFO/Data_Source']="No data";}
		if (!isset($locations[$locationId]['GENERAL_INFO/Distance_from_Belgrade'])){$locations[$locationId]['GENERAL_INFO/Distance_from_Belgrade']="No data";}
		if (!isset($locations[$locationId]['GENERAL_INFO/Distance_from_Public_Services'])){$locations[$locationId]['GENERAL_INFO/Distance_from_Public_Services']="No data";}
		if (!isset($locations[$locationId]['GENERAL_INFO/Enumenator'])){$locations[$locationId]['GENERAL_INFO/Enumenator']="No data";}
		if (!isset($locations[$locationId]['GENERAL_INFO/Focal_Point_Phone'])){$locations[$locationId]['GENERAL_INFO/Focal_Point_Phone']="No data";}
		if (!isset($locations[$locationId]['GENERAL_INFO/Function_Type_of_Building'])){$locations[$locationId]['GENERAL_INFO/Function_Type_of_Building']="No data";}
		if (!isset($locations[$locationId]['GENERAL_INFO/Latitude'])){$locations[$locationId]['GENERAL_INFO/Latitude']="No data";}
		if (!isset($locations[$locationId]['GENERAL_INFO/Location_Focal_Point'])){$locations[$locationId]['GENERAL_INFO/Location_Focal_Point']="No data";}
		if (!isset($locations[$locationId]['GENERAL_INFO/Location_Name'])){$locations[$locationId]['GENERAL_INFO/Location_Name']="No data";}
		if (!isset($locations[$locationId]['GENERAL_INFO/Longitude'])){$locations[$locationId]['GENERAL_INFO/Longitude']="No data";}
		if (!isset($locations[$locationId]['GENERAL_INFO/Managed_by'])){$locations[$locationId]['GENERAL_INFO/Managed_by']="No data";}
		if (!isset($locations[$locationId]['GENERAL_INFO/Occupancy'])){$locations[$locationId]['GENERAL_INFO/Occupancy']="No data";}
		if (!isset($locations[$locationId]['GENERAL_INFO/Property_of'])){$locations[$locationId]['GENERAL_INFO/Property_of']="No data";}
		if (!isset($locations[$locationId]['GENERAL_INFO/Type'])){$locations[$locationId]['GENERAL_INFO/Type']="No data";}
		if (!isset($locations[$locationId]['HEALTH/Adequate_Medical_Treatment'])){$locations[$locationId]['HEALTH/Adequate_Medical_Treatment']="No data";}
		if (!isset($locations[$locationId]['HEALTH/Health_Costs_Covered_or_Reimbursed'])){$locations[$locationId]['HEALTH/Health_Costs_Covered_or_Reimbursed']="No data";}
		if (!isset($locations[$locationId]['HEALTH/Health_Spot_on_the_Premises'])){$locations[$locationId]['HEALTH/Health_Spot_on_the_Premises']="No data";}
		if (!isset($locations[$locationId]['HEALTH/Quarantine_due_to_Medical_Reasons'])){$locations[$locationId]['HEALTH/Quarantine_due_to_Medical_Reasons']="No data";}
		if (!isset($locations[$locationId]['HEALTH/Referral_Mechanism_to_Public_Healthcare'])){$locations[$locationId]['HEALTH/Referral_Mechanism_to_Public_Healthcare']="No data";}
		if (!isset($locations[$locationId]['PSNS/PSNs_Referral_System_in_Place'])){$locations[$locationId]['PSNS/PSNs_Referral_System_in_Place']="No data";}
		if (!isset($locations[$locationId]['PSNS/SGBV_SOPs_in_Place_and_Functioning'])){$locations[$locationId]['PSNS/SGBV_SOPs_in_Place_and_Functioning']="No data";}
		if (!isset($locations[$locationId]['PSNS/Special_Services_for_SGBV_Survivors'])){$locations[$locationId]['PSNS/Special_Services_for_SGBV_Survivors']="No data";}
		if (!isset($locations[$locationId]['PSNS/Special_Services_for_Sub_Groups'])){$locations[$locationId]['PSNS/Special_Services_for_Sub_Groups']="No data";}
		if (!isset($locations[$locationId]['SAFETY_SECURITY/Centre_Adequately_Lit'])){$locations[$locationId]['SAFETY_SECURITY/Centre_Adequately_Lit']="No data";}
		if (!isset($locations[$locationId]['SAFETY_SECURITY/Centre_Secured_with_a_Fence'])){$locations[$locationId]['SAFETY_SECURITY/Centre_Secured_with_a_Fence']="No data";}
		if (!isset($locations[$locationId]['SAFETY_SECURITY/Centre_Secured_with_Video_Surveillance'])){$locations[$locationId]['SAFETY_SECURITY/Centre_Secured_with_Video_Surveillance']="No data";}
		if (!isset($locations[$locationId]['SAFETY_SECURITY/Fire_Safety_Insured'])){$locations[$locationId]['SAFETY_SECURITY/Fire_Safety_Insured']="No data";}
		if (!isset($locations[$locationId]['SAFETY_SECURITY/Physical_Risks'])){$locations[$locationId]['SAFETY_SECURITY/Physical_Risks']="No data";}
		if (!isset($locations[$locationId]['SAFETY_SECURITY/Security_Personnel_Present'])){$locations[$locationId]['SAFETY_SECURITY/Security_Personnel_Present']="No data";}
		if (!isset($locations[$locationId]['SHELTER/Adequate_Heating_System'])){$locations[$locationId]['SHELTER/Adequate_Heating_System']="No data";}
		if (!isset($locations[$locationId]['SHELTER/Adequate_Level_of_Privacy'])){$locations[$locationId]['SHELTER/Adequate_Level_of_Privacy']="No data";}
		if (!isset($locations[$locationId]['SHELTER/Adequate_Ventilation'])){$locations[$locationId]['SHELTER/Adequate_Ventilation']="No data";}
		if (!isset($locations[$locationId]['SHELTER/Centre_Accessible_for_all'])){$locations[$locationId]['SHELTER/Centre_Accessible_for_all']="No data";}
		if (!isset($locations[$locationId]['SHELTER/Internet_Available'])){$locations[$locationId]['SHELTER/Internet_Available']="No data";}
		if (!isset($locations[$locationId]['SHELTER/Number_of_Places_per_3_5_m2_p'])){$locations[$locationId]['SHELTER/Number_of_Places_per_3_5_m2_p']="No data";}
		if (!isset($locations[$locationId]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'])){$locations[$locationId]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p']="No data";}
		if (!isset($locations[$locationId]['SHELTER/Sufficient_Electrical_Capacity'])){$locations[$locationId]['SHELTER/Sufficient_Electrical_Capacity']="No data";}
		if (!isset($locations[$locationId]['STATISTICS/Number_of_Children'])){$locations[$locationId]['STATISTICS/Number_of_Children']="No data";}
		if (!isset($locations[$locationId]['STATISTICS/Number_of_Men'])){$locations[$locationId]['STATISTICS/Number_of_Men']="No data";}
		if (!isset($locations[$locationId]['STATISTICS/Number_of_Persons_from_Afghanistan'])){$locations[$locationId]['STATISTICS/Number_of_Persons_from_Afghanistan']="No data";}
		if (!isset($locations[$locationId]['STATISTICS/Number_of_Persons_from_Iraq'])){$locations[$locationId]['STATISTICS/Number_of_Persons_from_Iraq']="No data";}
		if (!isset($locations[$locationId]['STATISTICS/Number_of_Persons_from_Pakistan'])){$locations[$locationId]['STATISTICS/Number_of_Persons_from_Pakistan']="No data";}
		if (!isset($locations[$locationId]['STATISTICS/Number_of_Persons_Other'])){$locations[$locationId]['STATISTICS/Number_of_Persons_Other']="No data";}
		if (!isset($locations[$locationId]['STATISTICS/Number_of_UASC'])){$locations[$locationId]['STATISTICS/Number_of_UASC']="No data";}
		if (!isset($locations[$locationId]['STATISTICS/Number_of_Women'])){$locations[$locationId]['STATISTICS/Number_of_Women']="No data";}
		if (!isset($locations[$locationId]['WASH/Adequate_Hygene'])){$locations[$locationId]['WASH/Adequate_Hygene']="No data";}
		if (!isset($locations[$locationId]['WASH/Adequate_Sewage_System'])){$locations[$locationId]['WASH/Adequate_Sewage_System']="No data";}
		if (!isset($locations[$locationId]['WASH/Adequate_Waste_Management'])){$locations[$locationId]['WASH/Adequate_Waste_Management']="No data";}
		if (!isset($locations[$locationId]['WASH/Adequate_Water_Supply'])){$locations[$locationId]['WASH/Adequate_Water_Supply']="No data";}
		if (!isset($locations[$locationId]['WASH/Drinking_Water_Available'])){$locations[$locationId]['WASH/Drinking_Water_Available']="No data";}
		if (!isset($locations[$locationId]['WASH/Gender_Separated_Facilities'])){$locations[$locationId]['WASH/Gender_Separated_Facilities']="No data";}
		if (!isset($locations[$locationId]['WASH/Hot_Water_Available'])){$locations[$locationId]['WASH/Hot_Water_Available']="No data";}
		if (!isset($locations[$locationId]['WASH/Laundry_Services_for_Personal_Use'])){$locations[$locationId]['WASH/Laundry_Services_for_Personal_Use']="No data";}
		if (!isset($locations[$locationId]['WASH/Number_of_Showers'])){$locations[$locationId]['WASH/Number_of_Showers']="No data";}
		if (!isset($locations[$locationId]['WASH/Number_of_Toilets'])){$locations[$locationId]['WASH/Number_of_Toilets']="No data";}
		if (!isset($locations[$locationId]['WASH/Number_of_Water_Taps'])){$locations[$locationId]['WASH/Number_of_Water_Taps']="No data";}
		if (!isset($locations[$locationId]['WASH/On_site_Laundry_or_Outsourced'])){$locations[$locationId]['WASH/On_site_Laundry_or_Outsourced']="No data";}


		//the $mapName variable will be used when displaying locations on maps. It gets data from the Location_name element of the matrix based on the locadionId
		$mapName = $locations[$locationId]['GENERAL_INFO/Location_Name'];
		$mapName = "'" . $mapName . "'";

		//$children and $UASC variables will be used for generating charts
		$children = $locations[$locationId]['STATISTICS/Number_of_Children'];
		$UASC = $locations[$locationId]['STATISTICS/Number_of_UASC'];

		//this is to calculate TOTAL CHILDREN - UASC, and get non-UASC children (if the numbers are missing, "No data" is displayed)
		if(($children != "No data") AND ($UASC != "No data")){
		$otherChildren = $children - $UASC;}
		else $otherChildren = "No data";

		?>
		<title>Centre Profiles - Serbia</title>
		<!--
		A scrtipt for generating the Gender chart (check for more info: https://developers.google.com/chart/)
		The numbers of Men, Women and Children are pulled from KoBO (based on the locationID) and passed to the script
		-->
	    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	    <script type="text/javascript">
	      google.charts.load('current', {'packages':['corechart']});
	      google.charts.setOnLoadCallback(drawChart);
	      function drawChart() {
	        var data = google.visualization.arrayToDataTable([

	          ['Age/Gender', 'Persons'],
	          ['Men', <?php echo $locations[$locationId]['STATISTICS/Number_of_Men']?>],
	          ['Women', <?php echo $locations[$locationId]['STATISTICS/Number_of_Women']?>],
	          ['Children', <?php echo $locations[$locationId]['STATISTICS/Number_of_Children']?>]
	        ]);

	        var options = {
	          title: 'Age/Gender Breakdown',
	          colors: ['#0c73bb', '#225893', '#d9d9d9']
	        };

	        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

	        chart.draw(data, options);
	      }
	    </script>
	    <!--
		A scrtipt for generating the UASC/Other Children chart (check for more info: https://developers.google.com/chart/)
		The numbers of Children and UASC pulled from KoBo, while the $otherChildren variable is calculated above
		-->
	    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	    <script type="text/javascript">
	      google.charts.load("current", {packages:["corechart"]});
	      google.charts.setOnLoadCallback(drawChart);
	      function drawChart() {
	        var data = google.visualization.arrayToDataTable([
	          ['UASC/Other Children', 'Children'],
	          ['UASC', <?php echo $locations[$locationId]['STATISTICS/Number_of_UASC']?>],
	          ['Other Children', <?php echo $otherChildren?>],
	        ]);

	        var options = {
	          title: 'UASC/Other Children',
	          colors: ['#0c73bb', '#225893', '#d9d9d9'],
	          pieHole: 0.4,
	        };

	        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
	        chart.draw(data, options);
	      }
	    </script>
	    <!--
		A scrtipt for generating the CoO map (check for more info: https://developers.google.com/chart/)
		CoOs are pulled from KoBo data
		-->
	    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	   	 <script type="text/javascript">
	      google.charts.load('current', {
	        'packages':['geochart'],
	        // Note: you will need to get a mapsApiKey for your project.
	        // See: https://developers.google.com/chart/interactive/docs/basic_load_libs#load-settings
	        'mapsApiKey': 'AIzaSyD-9tSrke72PouQMnMX-a7eZSW0jkFMBWY'
	      });
	      google.charts.setOnLoadCallback(drawRegionsMap);

	      function drawRegionsMap() {
	        var data = google.visualization.arrayToDataTable([
	          ['Country of Origin', 'Persons'],
	          ['Afghanistan', <?php echo $locations[$locationId]['STATISTICS/Number_of_Persons_from_Afghanistan']?>],
	          ['Iraq', <?php echo $locations[$locationId]['STATISTICS/Number_of_Persons_from_Iraq']?>],
	          ['Pakistan', <?php echo $locations[$locationId]['STATISTICS/Number_of_Persons_from_Pakistan']?>]
	        ]);

	        var options = {
	       	colors: ['#d9d9d9', '#0c73bb', '#225893']};

	        var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));

	        chart.draw(data, options);
	      }
	    </script>
	    <!--
		A scrtipt for generating the location map (check for more info: https://developers.google.com/chart/)
		The coordinates (Latitude and Longitude) are pulled from KoBo
		The region code is set to "RS" to display Serbia
		-->
	        <script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>
		    <script type='text/javascript'>
		     google.charts.load('current', {
		       'packages': ['geochart'],
		       // Note: you will need to get a mapsApiKey for your project.
		       // See: https://developers.google.com/chart/interactive/docs/basic_load_libs#load-settings
		       'mapsApiKey': 'AIzaSyD-9tSrke72PouQMnMX-a7eZSW0jkFMBWY'
		     });
		     google.charts.setOnLoadCallback(drawMarkersMap);

		      function drawMarkersMap() {
		      var data = google.visualization.arrayToDataTable([
   				['Lat', 'Long', 'Name'],
			    [<?php echo $locations[$locationId]['GENERAL_INFO/Latitude']?>, <?php echo $locations[$locationId]['GENERAL_INFO/Longitude']?>, <?php echo $mapName?>],
			  
		      ]);

		      var options = {
		        region: 'RS',
		        displayMode: 'markers',
		        defaultColor: '#0c73bb'
		      };

		      var chart = new google.visualization.GeoChart(document.getElementById('chart_div'));
		      chart.draw(data, options);
		    };
		    </script>

	</head>
	<body>
		<!--
		This is the HTML form (search box) that takes your input which is used to get the $locationIN (explained above... PHP code checking if something was POSTed)
		-->
		<div align="center">
			<table style="font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 14;">
				<tr>
					<td colspan="3">
					<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
						<strong>Location:</strong>
							<select name="search">
		<!--
		The location names are hard-coded into the drowdown lists to control displayin new locations added
		-->
								<option value="Adasevci" <?php if (isset($locations[$locationId]['GENERAL_INFO/Location_Name']) && $locations[$locationId]['GENERAL_INFO/Location_Name'] == 'Adasevci') echo ' selected="selected"';?>>Adasevci TC</option>
								<option value="Banja Koviljaca" <?php if (isset($locations[$locationId]['GENERAL_INFO/Location_Name']) && $locations[$locationId]['GENERAL_INFO/Location_Name'] == 'Banja Koviljaca') echo ' selected="selected"';?>>Banja Koviljaca AC</option>
							</select>
							<input type="submit" name="submit" value="Select" style="background-color: #0c73bb; color: white; border: 0"></div>
						</form>
					</td>
				</tr>
				<tr>
		<!---
		The header logo is pulled from the local unhcr_logo.png file, but it can be refferenced from the web
		The rest is a HTML table that gets data from the location array pulled from kobo, based on the locationId (obtained by comparing the location name from the array with the search box)
		-->
				 	<td colspan="5" style="background-color: #0c73bb ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 16; font-weight: bold; color: white; text-align: left; height: 70"><img src="unhcr_logo.png" alt="UNHCR Serbia" style="height:70;"><div style="text-align: right;"></div></td>
				 	<td colspan="4" rowspan="9" width="100" style="background-color: white ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; font-weight: bold; color: gray; text-align: TOP-left; height: 12; vertical-align: top;"><div id="chart_div" style="width: 100%;></div></div></td>
				</tr>
				<tr style="height: 16">
				</tr> 
				<tr>
					<td colspan="5" style="background-color: white ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 30; font-weight: bold; color: black; text-align: left; height: 30"><?php echo strtoupper($locations[$locationId]['GENERAL_INFO/Location_Name'] . " " . $locations[$locationId]['GENERAL_INFO/Type'])?></td>
				</tr>
				<tr>
					<td colspan="2" style="background-color: white ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; font-weight: bold; color: black; text-align: left; height: 12">Focal Point:</td>
					<td colspan="3" rowspan="5" width="100" style="background-color: white ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; font-weight: bold; color: gray; text-align: right; height: 12; vertical-align: top;">
						<?php echo strtoupper ($locations[$locationId]['GENERAL_INFO/Function_Type_of_Building']);?>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="background-color: white ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; color: black; text-align: left; height: 12"><?php echo $locations[$locationId]['GENERAL_INFO/Location_Focal_Point']?></td>
				</tr>
				<tr>
					<td colspan="2" style="background-color: white ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; font-weight: bold; color: black; text-align: left; height: 12">Phone Number:</td>
				</tr>
				<tr>
					<td colspan="2" style="background-color: white ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; color: black; text-align: left; height: 12"><?php echo $locations[$locationId]['GENERAL_INFO/Focal_Point_Phone']?></td>
				</tr>
				<tr>
					<td colspan="2" style="background-color: white ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; font-weight: bold; color: black; text-align: left; height: 12">Authority/Mgmt.:</td>
				</tr>
				<tr>
					<td colspan="2" style="background-color: white ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; color: black; text-align: left; height: 12"><?php echo $locations[$locationId]['GENERAL_INFO/Managed_by']?></td>
				</tr>
				<tr>
					<td colspan="2" style="background-color: white ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; color: black; text-align: left; height: 12"></td>
				</tr>
				<tr>
					<td colspan="2" style="background-color: white ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; font-weight: bold; color: black; text-align: left; height: 12">Property of:</td>
					<td colspan="5" rowspan="2" width="100" style="background-color: white ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; font-weight: bold; color: gray; text-align: TOP-left; height: 12; vertical-align: top;">
					<?php
					echo $locations[$locationId]['GENERAL_INFO/Centre_Phone_Number'];?></br><?php
					echo $locations[$locationId]['GENERAL_INFO/Centre_e_mail_Address'];?></br><?php
					echo $locations[$locationId]['GENERAL_INFO/Centre_Address']
					?>
				<tr>
					<td colspan="2" style="background-color: white ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; color: black; text-align: left; height: 12; vertical-align: top;"><?php echo $locations[$locationId]['GENERAL_INFO/Property_of']?>
						
					</td>
				</tr>
				<tr>
					<td colspan="5" style="background-color: white ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 14; font-weight: bold; color: black; text-align: left; height: 14; color: #225893"><?php echo $locations[$locationId]['GENERAL_INFO/Latitude']?>, <?php echo $locations[$locationId]['GENERAL_INFO/Longitude']?></td>

				</tr>
				<tr>
					<td colspan="2" style="background-color: white ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; color: gray; text-align: left; height: 12"><strong><?php echo $locations[$locationId]['GENERAL_INFO/Distance_from_Belgrade']?>km</strong> AWAY FROM BELGRADE</td>
				</tr>
					<td colspan="2" style="background-color: white ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; color: gray; text-align: left; height: 12"><strong><?php echo $locations[$locationId]['GENERAL_INFO/Distance_from_Public_Services']?>km</strong> AWAY FROM PUBLIC SERVICES</td>
				</tr>
				<tr>
				<!--
				This is where the traffic lights start
				The first column, and later the fifth column are used for displaying traffic lights (column info is stored in variables above e.g. $traffick_red)
				In most fields, it checks the element displayed (for example YES, NO or PARTIALLY), and displays a color based on the results
				Some fileds (like Occuppacy/Capacity, #toilets, fields that contain dates etc), have different methods of calculation. You can take a look at them individualy
				-->
					<?php
					if ($locations[$locationId]['GENERAL_INFO/Occupancy'] == "No data"){
						$locations[$locationId]['GENERAL_INFO/Occupancy'] = "0";}

					if ($locations[$locationId]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'] == "No data"){
						$locations[$locationId]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'] = "1";}

					$ocuPercent = ($locations[$locationId]['GENERAL_INFO/Occupancy'])*100/($locations[$locationId]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p']);
					if ($ocuPercent <= 100){echo $traffic_green;}
					elseif (($ocuPercent > 100) AND ($ocuPercent <= 120)) {echo $traffic_yellow;}
					elseif ($ocuPercent > 120) {echo $traffic_red;}
					else {echo $traffic_red;}
					?></td>
			 		<td colspan="4" style="background-color: white ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 20; font-weight: bold;">OCCUPANCY/CAPACITY:</td>
			 		<td colspan="2" style="background-color: white ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 20; font-weight: bold; text-align: right">
			 		<?php
			 		echo $locations[$locationId]['GENERAL_INFO/Occupancy'];?>/<?php
			 		echo $locations[$locationId]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p']
			 		?>			 		
			 		</td>
				</tr> 
				<tr>
			 		<td colspan="7" style="background-color: black ; height: 5"></td>
				</tr>
				<tr>
					<td colspan="7" style="background-color: white ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 14; font-weight: bold;"></td>	
				</tr>
				<tr>
					<td colspan="7" style="background-color: white ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 14; font-weight: bold;">Top Countries of Origin:</td>	
				</tr>
				<tr>
			 		<td colspan="7" style="background-color: white ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12;">
			 			<table>
			 				<tr>
			 				<div id="regions_div" style="width: 800px; height: 500px;"></div>
			 				</tr>
			 				<tr>
			 				<td><div id="donutchart" style="width: 400px; height: 300px;"></div></td>
			 				<td ><div id="piechart" style="width: 400px; height: 300px;"></div></div></td>
			 				</tr>
			 			</table>
			 		</td>
			 	</tr>
			 	<tr>
			 		<td colspan="3" style="background-color: D1D2D4 ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 16; font-weight: bold;">SHELTER:</td>
			   		<td style="width: 40"></td>
			 		<td colspan="3" style="background-color: D1D2D4 ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 16; font-weight: bold;">SAFETY & SECURITY:</td>
			 	</tr> 
			 	<tr style="height: 16">
			 	</tr> 
		  		<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'];
					    if ($ocuPercent <= 100){echo $traffic_green;}
						elseif (($ocuPercent > 100) AND ($ocuPercent <= 120)) {echo $traffic_yellow;}
						elseif ($ocuPercent > 120) {echo $traffic_red;}
						else {echo $traffic_red;}
					?>
				    <td style="width: 300">#places per 4.5-5.5 m2/p: </td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['SAFETY_SECURITY/Fire_Safety_Insured'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Fire safety ensured: </td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['SHELTER/Number_of_Places_per_3_5_m2_p'];
					    if ($q_answer > 0){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">#places per 3.5 m2/p:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
			 		<?php 
			 			$q_answer = "yes";
			 		?>
				    <?php
				    	$q_answer = $locations[$locationId]['SAFETY_SECURITY/Centre_Adequately_Lit'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Center adequately lit: </td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['SHELTER/Sufficient_Electrical_Capacity'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Sufficient electrical capacity:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['SAFETY_SECURITY/Physical_Risks'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_red;}
					    elseif ($q_answer == "no"){echo $traffic_green;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Physical risks:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['SHELTER/Adequate_Heating_System'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Adequate heating system:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['SAFETY_SECURITY/Centre_Secured_with_a_Fence'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Center secured with a fence:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['SHELTER/Adequate_Ventilation'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Adequate ventilation:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['SAFETY_SECURITY/Centre_Secured_with_Video_Surveillance'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Center secured with video surveillance:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['SHELTER/Internet_Available'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Internet/Wi-Fi available:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['SAFETY_SECURITY/Security_Personnel_Present'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Security personnel present:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['SHELTER/Adequate_Level_of_Privacy'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Adequate level of privacy:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <td style="width:10;font-family: webdings; font-size: 10"></td>
				    <td style="width: 300"></td> 
				    <td style="width: 60"></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['SHELTER/Centre_Accessible_for_all'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Center accessible for all:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <td style="width:10;font-family: webdings; font-size: 10"></td>
				    <td style="width: 300"></td> 
				    <td style="width: 60"></td>
			 	</tr>
			 	<tr>
			 	 	<td style="width:10;font-family: webdings; font-size: 10"></td>
				    <td style="width: 300"></td> 
				    <td style="width: 60"></td>
				    <td style="width: 40"></td>
			 		<td colspan="3" style="background-color: D1D2D4 ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 16; font-weight: bold;">FOOD & NFIs:</td>
			 	</tr>
			 	<tr style="height: 16">
			 	</tr> 
			 	<tr >
			 		<td colspan="3" style="background-color: D1D2D4 ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 16; font-weight: bold;">WASH:</td>
			 		<td style="width: 40"></td>
			 		<?php
				    	$q_answer = $locations[$locationId]['FOOD_NFI/Adequate_Cooking_Dining_Space'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Adequate cooking/dining space:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr> 
			 	<tr>
				    <td style="width:10;font-family: webdings; font-size: 10"></td>
				    <td style="width: 300"></td> 
				    <td style="width: 60; font-weight: bold;"></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['FOOD_NFI/Adequate_Meals'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Adequate meals:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['WASH/Number_of_Toilets'];
					    if (($locations[$locationId]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'] != "No data") AND ($locations[$locationId]['WASH/Number_of_Toilets'] != "No data")){
					   		if(($locations[$locationId]['GENERAL_INFO/Occupancy']/20) <= $locations[$locationId]['WASH/Number_of_Toilets']){echo $traffic_green;}
					    	elseif(($locations[$locationId]['GENERAL_INFO/Occupancy']/20) > $locations[$locationId]['WASH/Number_of_Toilets']){echo $traffic_red;}
						}else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">#toilets:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer_data = $locations[$locationId]['FOOD_NFI/Food_Prepared_On_site_or_Catered'];
				    	if ($q_answer_data == "yes"){$q_answer = "On-site";}
				    	elseif ($q_answer_data == "no"){$q_answer = "Catered";}
				    	elseif ($q_answer_data == "partially"){$q_answer = "No food";}
				    	elseif ($q_answer_data == "No data"){$q_answer = "No data";}
				    	if ($q_answer == "No Food"){echo $traffic_red;}
				    	else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Food prepared on-site or catered:</td> 
				    <td style="width: 60; font-weight: bold; font-size: 11"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['WASH/Number_of_Showers'];
					    if (($locations[$locationId]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'] != "No data") AND ($locations[$locationId]['WASH/Number_of_Showers'] != "No data")){
					   		if(($locations[$locationId]['GENERAL_INFO/Occupancy']/20) <= $locations[$locationId]['WASH/Number_of_Showers']){echo $traffic_green;}
					    	elseif(($locations[$locationId]['GENERAL_INFO/Occupancy']/20) > $locations[$locationId]['WASH/Number_of_Showers']){echo $traffic_red;}
						}else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">#showers:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['FOOD_NFI/Adequate_NFI_Distribution'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Adequate storage/distribution space:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['WASH/Number_of_Water_Taps'];
					    if (($locations[$locationId]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'] != "No data") AND ($locations[$locationId]['WASH/Number_of_Water_Taps'] != "No data")){
					   		if(($locations[$locationId]['GENERAL_INFO/Occupancy']/80) <= $locations[$locationId]['WASH/Number_of_Water_Taps']){echo $traffic_green;}
					    	elseif(($locations[$locationId]['GENERAL_INFO/Occupancy']/80) > $locations[$locationId]['WASH/Number_of_Water_Taps']){echo $traffic_red;}
						}else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">#water taps:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['FOOD_NFI/Adequate_NFI_Distribution'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Adequate NFI distribution:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['WASH/Gender_Separated_Facilities'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Gender separated facilities:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['FOOD_NFI/Provision_of_NFIs_Ta_ed_to_Specific_Needs'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Provision of NFIs targeted to specific needs:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>			    <?php
				    	$q_answer = $locations[$locationId]['WASH/Adequate_Water_Supply'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Adequate water supply:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <td style="width:10;font-family: webdings; font-size: 10"></td>
				    <td style="width: 300"></td> 
				    <td style="width: 60"></td>
				</tr>
		  		<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['WASH/Drinking_Water_Available'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Drinking water available:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
			 		<td colspan="3" style="background-color: D1D2D4 ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 16; font-weight: bold;">HEALTH:</td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['WASH/Hot_Water_Available'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Hot water available:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <td style="width:10;font-family: webdings; font-size: 10"></td>
				    <td style="width: 300"></td> 
				    <td style="width: 60; font-weight: bold;"></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['WASH/Adequate_Sewage_System'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Adequate sewage system:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['HEALTH/Adequate_Medical_Treatment'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Adequate medical treatment:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer_data = $locations[$locationId]['WASH/On_site_Laundry_or_Outsourced'];
				    	if ($q_answer_data == "yes"){$q_answer = "On-site";}
				    	elseif ($q_answer_data == "no"){$q_answer = "Outsourced";}
				    	elseif ($q_answer_data == "partially"){$q_answer = "No laundry";}
				    	elseif ($q_answer_data == "No data"){$q_answer = "No data";}
				    	if ($q_answer == "No laundry"){echo $traffic_red;}
				    	else {echo $traffic_gray;}
				    ?>
				    <td style="width: 300">On-site laundry or outsourced:</td> 
				    <td style="width: 60; font-weight: bold; font-size: 11"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['HEALTH/Health_Spot_on_the_Premises'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Health spot on the premises:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['WASH/Laundry_Services_for_Personal_Use'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Laundry services for personal use:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['HEALTH/Quarantine_due_to_Medical_Reasons'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Quarantine due to medical reasons:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['WASH/Adequate_Hygene'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Adequate hygiene of the facilities:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['HEALTH/Referral_Mechanism_to_Public_Healthcare'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Referral mechanism to public healthcare:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['WASH/Adequate_Waste_Management'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Adequate waste management:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['HEALTH/Health_Costs_Covered_or_Reimbursed'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Health costs covered or reimbursed:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr style="height: 16">
			 	</tr> 
			 	<tr>
			 		<td colspan="3" style="background-color: D1D2D4 ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 16; font-weight: bold;">EDUCATION & LEISURE:</td>
			   		<td style="width: 40"></td>
			 		<td colspan="3" style="background-color: D1D2D4 ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 16; font-weight: bold;">CHILD PROTECTION:</td>
			 	</tr> 
			 	<tr style="height: 16">
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['EDUCATION_LEISURE/Area_for_Educational_upational_Activities'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Area for educational/occupational activities:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['CHILD_PROTECTION/Designated_Child_Friendly_Space'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Designated Child-Friendly Space:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['EDUCATION_LEISURE/Area_for_Recreational_Activities'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Area for recreational activities:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['CHILD_PROTECTION/Outdoor_Playground'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Outdoor playground:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['EDUCATION_LEISURE/Area_for_Practicing_Religion'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Area for practicing religion:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['CHILD_PROTECTION/Designated_Mother_and_Baby_Areas'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Designated mother and baby areas:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer_data = $locations[$locationId]['EDUCATION_LEISURE/Frequency_of_Activities_for_Children'];
				    	if ($q_answer_data == "not_org"){$q_answer = "Not org.";}
				    	elseif ($q_answer_data == "daily"){$q_answer = "Daily";}
				    	elseif ($q_answer_data == "several_x_week"){$q_answer = "x/week";}
				    	elseif ($q_answer_data == "several_x_mont"){$q_answer = "x/month";}
				    	elseif ($q_answer_data == "several_x_year"){$q_answer = "x/year";}
				    	elseif ($q_answer_data == "No data"){$q_answer = "No data";}
				    	
				    	if ($q_answer_data == "not_org"){echo $traffic_red;}
				    	elseif ($q_answer_data == "daily"){echo $traffic_green;}
				    	elseif ($q_answer_data == "several_x_week"){echo $traffic_green;}
				    	elseif ($q_answer_data == "several_x_mont"){echo $traffic_yellow;}
				    	elseif ($q_answer_data == "several_x_year"){echo $traffic_red;}
				    	elseif ($q_answer_data == "No data"){echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Frequency of activities for children:</td> 
				    <td style="width: 60; font-weight: bold; font-size: 11"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['CHILD_PROTECTION/Staff_with_Child_Pro_ction_Skills_Present'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Staff with child protection skills present:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer_data = $locations[$locationId]['EDUCATION_LEISURE/Frequency_of_Activities_for_Adults'];
				    	if ($q_answer_data == "not_org"){$q_answer = "Not org.";}
				    	elseif ($q_answer_data == "daily"){$q_answer = "Daily";}
				    	elseif ($q_answer_data == "several_x_week"){$q_answer = "x/week";}
				    	elseif ($q_answer_data == "several_x_mont"){$q_answer = "x/month";}
				    	elseif ($q_answer_data == "several_x_year"){$q_answer = "x/year";}
				    	elseif ($q_answer_data == "No data"){$q_answer = "No data";}
				    	
				    	if ($q_answer_data == "not_org"){echo $traffic_red;}
				    	elseif ($q_answer_data == "daily"){echo $traffic_green;}
				    	elseif ($q_answer_data == "several_x_week"){echo $traffic_green;}
				    	elseif ($q_answer_data == "several_x_mont"){echo $traffic_yellow;}
				    	elseif ($q_answer_data == "several_x_year"){echo $traffic_red;}
				    	elseif ($q_answer_data == "No data"){echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Frequency of activities for adults:</td> 
				    <td style="width: 60; font-weight: bold; font-size: 11"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['CHILD_PROTECTION/BIAs_Conducted'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">BIAs conducted:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer_data = $locations[$locationId]['EDUCATION_LEISURE/Frequency_of_Language_Classes'];
				    	if ($q_answer_data == "not_org"){$q_answer = "Not org.";}
				    	elseif ($q_answer_data == "daily"){$q_answer = "Daily";}
				    	elseif ($q_answer_data == "several_x_week"){$q_answer = "x/week";}
				    	elseif ($q_answer_data == "several_x_mont"){$q_answer = "x/month";}
				    	elseif ($q_answer_data == "several_x_year"){$q_answer = "x/year";}
				    	elseif ($q_answer_data == "No data"){$q_answer = "No data";}
				    	
				    	if ($q_answer_data == "not_org"){echo $traffic_red;}
				    	elseif ($q_answer_data == "daily"){echo $traffic_green;}
				    	elseif ($q_answer_data == "several_x_week"){echo $traffic_green;}
				    	elseif ($q_answer_data == "several_x_mont"){echo $traffic_yellow;}
				    	elseif ($q_answer_data == "several_x_year"){echo $traffic_red;}
				    	elseif ($q_answer_data == "No data"){echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Frequency of language classes:</td> 
				    <td style="width: 60; font-weight: bold; font-size: 11"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['CHILD_PROTECTION/Separate_Accommodation_for_UASC'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Separate accommodation for UASC:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['EDUCATION_LEISURE/Children_Attending_School'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Children attending school:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['CHILD_PROTECTION/Separate_Accommodati_n_for_UAS_Boys_Girls'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Separate accommodation for UAS boys/girls:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['EDUCATION_LEISURE/School_Start_Allowance_In_kind'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">School start allowance/in-kind assistance:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['CHILD_PROTECTION/Can_Children_at_Risk_d_in_Foster_Families'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Can children at risk be accom. in foster families:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['EDUCATION_LEISURE/Incentives_for_Parti_in_Centre_Operation'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Incentives for participation in center operation:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['CHILD_PROTECTION/Legal_Guardian_Appointed_for_every_UASC'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Legal guardian appointed for every UASC:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <td style="width:10;font-family: webdings; font-size: 10"></td>
				    <td style="width: 300"></td> 
				    <td style="width: 60; font-weight: bold;"></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['CHILD_PROTECTION/Access_to_CSW_Services'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Access to CSW services:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <td style="width:10;font-family: webdings; font-size: 10"></td>
				    <td style="width: 300"></td> 
				    <td style="width: 60; font-weight: bold;"></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['CHILD_PROTECTION/Child_Protection_SOP_lace_and_Functioning'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Child protection SOPs in place/functioning:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
			 		<td colspan="3" style="background-color: D1D2D4 ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 16; font-weight: bold;">COMMUNICATION:</td>
				    <td style="width: 40"></td>
				    <td style="width:10;font-family: webdings; font-size: 10"></td>
				    <td style="width: 300"></td> 
				    <td style="width: 60"></td>
			 	</tr>
			 	<tr>
			 	 	<td style="width:10;font-family: webdings; font-size: 10"></td>
				    <td style="width: 300"></td> 
				    <td style="width: 60"></td>
				    <td style="width: 40"></td>
			 		<td colspan="3" style="background-color: D1D2D4 ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 16; font-weight: bold;">ASYLUM & IDENTIFICATION:</td>
			 	<tr>			    <?php
				    	$q_answer = $locations[$locationId]['COMMUNICATION/Private_Rooms_for_Counselling'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Private rooms for counselling:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <td style="width:10;font-family: webdings; font-size: 10"></td>
				    <td style="width: 300"></td> 
				    <td style="width: 60"></td>
				</tr>
			 	<tr>			    <?php
				    	$q_answer = $locations[$locationId]['COMMUNICATION/Legal_Counselling_Available'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Legal counselling available:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <td style="width:10;font-family: webdings; font-size: 10"></td>
				    <td style="width: 300"></td> 
				    <td style="width: 60"></td>
				</tr>
				<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['COMMUNICATION/Psycho_social_Counselling_Available'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Psychosocial counselling available:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['ASYLUM_IDENTIFICATION/Number_of_Persons_In_Applying_for_Asylum'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">#persons interested in applying for asylum:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['COMMUNICATION/Sufficient_Number_of_Interpreters'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Sufficient number of interpreters available:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['ASYLUM_IDENTIFICATION/Number_of_Persons_In_ng_to_Designated_ACs'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">#persons interested moved to desig. ACs:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['COMMUNICATION/Information_about_Available_Services'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Information about available services:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['ASYLUM_IDENTIFICATION/Accommodated_Persons_Registers_Available'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Accommodated persons registers available:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['COMMUNICATION/House_Rules_Clearly_Displayed'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">House Rules clearly displayed:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['ASYLUM_IDENTIFICATION/Centres_Issuing_any_kind_of_ID'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Center issuing any kind of ID:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['COMMUNICATION/Complaints_Mechanism_in_Place'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Complaints mechanism in place:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <td style="width:10;font-family: webdings; font-size: 10"></td>
				    <td style="width: 300"></td> 
				    <td style="width: 60; font-weight: bold;"></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['COMMUNICATION/Community_Structure_Established'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Community structure established:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <td style="width:10;font-family: webdings; font-size: 10"></td>
				    <td style="width: 300"></td> 
				    <td style="width: 60; font-weight: bold;"></td>
			 	</tr>
			 	<tr>
			 	 	<td style="width:10;font-family: webdings; font-size: 10"></td>
				    <td style="width: 300"></td> 
				    <td style="width: 60"></td>
				    <td style="width: 40"></td>
			 		<td colspan="3" style="background-color: D1D2D4 ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 16; font-weight: bold;">COORDINATION & MGMT.:</td>
			 	<tr >
			 		<td colspan="3" style="background-color: D1D2D4 ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 16; font-weight: bold;">PSNs:</td>
			 		<td style="width: 40"></td>
			 		<td style="width:10;font-family: webdings; font-size: 10"></td>
				    <td style="width: 300"></td> 
				    <td style="width: 60; font-weight: bold;"></td>
			 	</tr>
			 	<tr>
				    <td style="width:10;font-family: webdings; font-size: 10"></td>
				    <td style="width: 300"></td> 
				    <td style="width: 60; font-weight: bold;"></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['COORDINATION_MANAGEMENT/Office_Space_for_Caregivers'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Office space for caregivers:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['PSNS/Special_Services_for_SGBV_Survivors'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Special services for SGBV survivors:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['COORDINATION_MANAGEMENT/Referral_System_that_Ensures_Follow_up'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Referral system that ensures follow-up:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['PSNS/Special_Services_for_Sub_Groups'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Special services for sub-groups:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['COORDINATION_MANAGEMENT/Site_specific_Coordination_Mechanism'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Site-specific coordination mechanism:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['PSNS/SGBV_SOPs_in_Place_and_Functioning'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">SGBV SOPs in place and functioning:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['COORDINATION_MANAGEMENT/Date_of_Participatory_Assessment'];
					    if ($q_answer != "No data"){
						$truDate = date("d-m-Y", strtotime($q_answer));}
						else{$truDate = $q_answer;}

						if ($q_answer != "No data"){
						$startTimeStamp = strtotime($q_answer);
						$endTimeStamp = strtotime(date('y-m-d'));
						$timeDiff = abs($endTimeStamp - $startTimeStamp);
						$numberDays = $timeDiff/86400;
						$numberDays = intval($numberDays);}
						else{echo $traffic_gray;}

						if (isset($numberDays)){
							if ($numberDays <= 180){echo $traffic_green;}
							else{echo $traffic_red;}
						}
				    ?>
				    <td style="width: 300">Date of the last participatory assessment:</td> 
				    <td style="width: 60; font-weight: bold; font-size: 10"><?php echo ucwords($truDate)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['PSNS/PSNs_Referral_System_in_Place'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">PSNs referral system in place:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <td style="width:10;font-family: webdings; font-size: 10"></td>
				    <td style="width: 300"></td> 
				    <td style="width: 60; font-weight: bold;"></td>
			 	</tr>
				<tr>
			 	 	<td style="width:10;font-family: webdings; font-size: 10"></td>
				    <td style="width: 300"></td> 
				    <td style="width: 60"></td>
				    <td style="width: 40"></td>
			 		<td colspan="3" style="background-color: D1D2D4 ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 16; font-weight: bold;">FREEDOM OF MOVEMENT:</td>
			 	<tr >
			 	</tr> 
			 	<tr style="height: 16">
			 	</tr>
			 	<tr >
			 		<td colspan="3" style="background-color: D1D2D4 ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 16; font-weight: bold;">FAMILY UNITY:</td>
			 		<td style="width: 40"></td>
			 		<?php
				    	$q_answer = $locations[$locationId]['FREEDOM_MOVEMENT/Permits_Required_to_e_Outside_the_Centre'];
						echo $traffic_gray;
				    ?>
				    <td style="width: 300">Permits required to move outside of center:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <td style="width:10;font-family: webdings; font-size: 10"></td>
				    <td style="width: 300"></td> 
				    <td style="width: 60; font-weight: bold;"></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['FREEDOM_MOVEMENT/Access_after_prolonged_absence'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Access after prolonged absence:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['FAMILY_UNITY/Families_Women_Child_quately_Accommodated'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Families/women/children adequately accom.:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['FREEDOM_MOVEMENT/Maximum_length_of_ab_ce_allowed_in_hours'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Maximum length of absence allowed (in hours):</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['FAMILY_UNITY/Family_Unity_Maintained'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Family unity maintained:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['FREEDOM_MOVEMENT/Restricted_Freedom_o_nt_Inside_the_Centre'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_red;}
					    elseif ($q_answer == "no"){echo $traffic_green;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Restr. freedom of movement inside the center:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr>
				    <?php
				    	$q_answer = $locations[$locationId]['FAMILY_UNITY/Referral_Mechanism_f_Family_Reunification'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300"> Referral mechanism for family reunification:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
				    <td style="width: 40"></td>
				    <?php
				    	$q_answer = $locations[$locationId]['FREEDOM_MOVEMENT/Access_to_all_Public_Services'];
					    if ($q_answer == "partially"){echo $traffic_yellow;}
					    elseif ($q_answer == "yes"){echo $traffic_green;}
					    elseif ($q_answer == "no"){echo $traffic_red;}
					    else{echo $traffic_gray;}
				    ?>
				    <td style="width: 300">Access to all public services:</td> 
				    <td style="width: 60; font-weight: bold;"><?php echo ucwords($q_answer)?></td>
			 	</tr>
			 	<tr style="height: 16">
			 	</tr>
			 	<!--
				This is the 3W section. It doesnt use traffic lights, it simply displays the data provided from KoBo in a table
			 	-->
			 	<tr>
			 		<td colspan="7" style="background-color: #0c73bb ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 16; font-weight: bold; color: white">3W</td>
			 	</tr>
			 	<tr>
			 		<td colspan="2" style="background-color: #225893 ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 16; font-weight: bold; color: white; text-align: center">Sector</td>
			 		<td colspan="5" style="background-color: #225893 ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 16; font-weight: bold; color: white; text-align: center;">Donor - Organization</td
			 	</tr>
			 	<tr>
			 		<td colspan="2" style="background-color: #fdfdfd ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; font-weight: bold; color: #225893; text-align: right; center; height: 50">PROTECTION</td>
			 		<td colspan="5" style="background-color: #f0f0f1 ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; color: black; text-align: center; height: 50"><?php echo $locations[$locationId]['_3W/Agencies_Protection']?></td
			 	</tr>
			 	<tr>
			 		<td colspan="2" style="background-color: #fdfdfd ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; font-weight: bold; color: #225893; text-align: right; center; height: 50">ADMIN/LEGAL INFO</td>
			 		<td colspan="5" style="background-color: #f0f0f1 ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; color: black; text-align: center; height: 50"><?php echo $locations[$locationId]['_3W/Agencies_Admin_Legal_Info']?></td
			 	</tr>
			 	<tr>
			 		<td colspan="2" style="background-color: #fdfdfd ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; font-weight: bold; color: #225893; text-align: right; center; height: 50">HEALTH</td>
			 		<td colspan="5" style="background-color: #f0f0f1 ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; color: black; text-align: center; height: 50;"><?php echo $locations[$locationId]['_3W/Agencies_Health']?></td
			 	</tr>
			 	<tr>
			 		<td colspan="2" style="background-color: #fdfdfd ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; font-weight: bold; color: #225893; text-align: right; center; height: 50">EDUCATION</td>
			 		<td colspan="5" style="background-color: #f0f0f1 ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; color: black; text-align: center; height: 50"><?php echo $locations[$locationId]['_3W/Agencies_Education']?></td
			 	</tr>
			 	<tr>
			 		<td colspan="2" style="background-color: #fdfdfd ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; font-weight: bold; color: #225893; text-align: right; center; height: 50">FOOD & NUTRITION</td>
			 		<td colspan="5" style="background-color: #f0f0f1 ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; color: black; text-align: center; height: 50"><?php echo $locations[$locationId]['_3W/Agencies_Food_and_Nutrition']?></td
			 	</tr>
			 	<tr>
			 		<td colspan="2" style="background-color: #fdfdfd ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; font-weight: bold; color: #225893; text-align: right; center; height: 50">NON-FOOD ITEMS</td>
			 		<td colspan="5" style="background-color: #f0f0f1 ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; color: black; text-align: center; height: 50"><?php echo $locations[$locationId]['_3W/Agencies_NFI']?></td
			 	</tr>
			 	<tr>
			 		<td colspan="2" style="background-color: #fdfdfd ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; font-weight: bold; color: #225893; text-align: right; center; height: 50">WATER, SANITATION & HYGENE</td>
			 		<td colspan="5" style="background-color: #f0f0f1 ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; color: black; text-align: center; height: 50"><?php echo $locations[$locationId]['_3W/Agencies_Water_Sanitation_and_Hygene']?></td
			 	</tr>
			 	<tr>
			 		<td colspan="2" style="background-color: #fdfdfd ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; font-weight: bold; color: #225893; text-align: right; center; height: 50">LOCAL COMUNITY SUPPORT</td>
			 		<td colspan="5" style="background-color: #f0f0f1 ;font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 12; color: black; text-align: center; height: 50"><?php echo $locations[$locationId]['_3W/Agencies_Local_Community_Support']?></td
			 	</tr>
			 	<tr>
			 	<!--
				This is the disclaimer at the end of the end of the page
			 	-->
			 		<td colspan="7" style="font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 10; font-weight: bold;">
			 			</br>
			 			<p>
			 				This is a living document. UNHCR Serbia cannot vouch for the accuracy of all data provided from various sources. </br>
			 				For any comments or suggestins, feel free to <a href="http://www.unhcr.rs/opste/vesti-misljenja/kontakti.html">contact us</a>.</br></br>
			 				This assessment was conducted with reference to national legislation of Serbia, EU Reception Directives, EASO Guidelines, Sphere and UNHCR standards.</br></br>
			 				You can also download the <a href="https://data2.unhcr.org/en/documents/details/55034">PDF version</a> from the <a href="http://data2.unhcr.org/en/situations">Operational Data Portal</a>.
			 			</p>
			 		</td>
			 	</tr>
			 </table>
		</div>
	</body>
</html>

