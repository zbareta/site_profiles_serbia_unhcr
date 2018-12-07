		<!--
		This application pulls SITE PROFILE data from kobo.unhcr.org, stores it in an array and displays it using charts, maps, traffic lights, plain text etc.
		For any assistance feel free to contact:

		Zeljko Bareta - IM Associate, Belgrade, Serbia

		work e-mail:	BARETA@unhcr.org
		private e-mail:	zbareta@gmail.com
		mobile:			+38163 366 158
		skype: 			zeljko.bareta
		-->
<html lang="en">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
	<link href='http://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css"
	integrity="sha512-M2wvCLH6DSRazYeZRIm1JnYyh22purTM+FDB5CsyxtQJYeKq83arPe5wgbNmcFXGqiSH2XR8dT/fJISVA1r/zQ=="
	crossorigin=""/>
	<script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js"
  	integrity="sha512-lInM/apFSqyy1o6s89K4iQUKg6ppXEgsVxT35HbzUupEVRh2Eu9Wdl4tHj7dZO0s1uvplcYGmt3498TtHq+log=="
  	crossorigin=""></script>
		<!-- 
		Writes the number "hits" the page recieved into a sepparete counterlog.txt file
		Upload an empty counterlog.txt file to your host, next to this file
		-->
		<?php
		$fp = fopen("overviewlog.txt", "r"); 
		$count = fread($fp, 1024); 
		fclose($fp); 
		$count = $count + 1; 
		//echo "<p>Page views:" . $count . "</p>"; 
		$fp = fopen("overviewlog.txt", "w"); 
		fwrite($fp, $count); 
		fclose($fp); 
		?> 

		<!-- 
		Getting data from the kobo.unhcr.org API. Enter your USERNAME, PASSWORD and API LINK
		-->
		<?php
		$username = "xxxxx";
		$password = "xxxxx";
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

		//This initially sets all variables used for generating charts to 0
		$men_total = 0;
		$women_total = 0;
		$children_total = 0;
		$uasc_total = 0;
		$afg_total = 0;
		$irq_total = 0;
		$pak_total = 0;
		$syr_total = 0;
		$oth_total = 0;
		$occ_total = 0;
		$capacity_total = 0;
		$otherChildren_total = 0;

		//This generates popups on the map, as well as links to specific site profiles
		$Adasevci_link = '<a href="site_profiles.php?search=Adasevci&submit=Select">Site Profile</a>';
		$Adasevci_popup =  "<strong>" . $locations[0]['GENERAL_INFO/Location_Name'] . " " . strtoupper($locations[0]['GENERAL_INFO/Type']) . "</strong>" . "</br>" . "<strong>" . "Occupancy: " . "</strong>" . $locations[0]['GENERAL_INFO/Occupancy'] . "/" . $locations[0]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'] . "</br>" . "<strong>" . "E-mail: ". "</strong>" . $locations[0]['GENERAL_INFO/Centre_e_mail_Address'] . "</br>" . $Adasevci_link;
		$Adasevci_popup = "'" . $Adasevci_popup . "'";

		$Banja_link = '<a href="site_profiles.php?search=Banja Koviljaca&submit=Select">Site Profile</a>';
		$Banja_popup =  "<strong>" . $locations[1]['GENERAL_INFO/Location_Name'] . " " . strtoupper($locations[1]['GENERAL_INFO/Type']) . "</strong>" . "</br>" . "<strong>" . "Occupancy: " . "</strong>" . $locations[1]['GENERAL_INFO/Occupancy'] . "/" . $locations[1]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'] . "</br>" . "<strong>" . "E-mail: ". "</strong>" . $locations[1]['GENERAL_INFO/Centre_e_mail_Address'] . "</br>" . $Banja_link;
		$Banja_popup = "'" . $Banja_popup . "'";

		$Bogovadja_link = '<a href="site_profiles.php?search=Bogovadja&submit=Select">Site Profile</a>';
		$Bogovadja_popup =  "<strong>" . $locations[3]['GENERAL_INFO/Location_Name'] . " " . strtoupper($locations[3]['GENERAL_INFO/Type']) . "</strong>" . "</br>" . "<strong>" . "Occupancy: " . "</strong>" . $locations[3]['GENERAL_INFO/Occupancy'] . "/" . $locations[3]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'] . "</br>" . "<strong>" . "E-mail: ". "</strong>" . $locations[3]['GENERAL_INFO/Centre_e_mail_Address'] . "</br>" . $Bogovadja_link;
		$Bogovadja_popup = "'" . $Bogovadja_popup . "'";

		$Bosilegrad_link = '<a href="site_profiles.php?search=Bosilegrad&submit=Select">Site Profile</a>';
		$Bosilegrad_popup =  "<strong>" . $locations[4]['GENERAL_INFO/Location_Name'] . " " . strtoupper($locations[4]['GENERAL_INFO/Type']) . "</strong>" . "</br>" . "<strong>" . "Occupancy: " . "</strong>" . $locations[4]['GENERAL_INFO/Occupancy'] . "/" . $locations[4]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'] . "</br>" . "<strong>" . "E-mail: ". "</strong>" . $locations[4]['GENERAL_INFO/Centre_e_mail_Address'] . "</br>" . $Bosilegrad_link;
		$Bosilegrad_popup = "'" . $Bosilegrad_popup . "'";

		$Bujanovac_link = '<a href="site_profiles.php?search=Bujanovac&submit=Select">Site Profile</a>';
		$Bujanovac_popup =  "<strong>" . $locations[5]['GENERAL_INFO/Location_Name'] . " " . strtoupper($locations[5]['GENERAL_INFO/Type']) . "</strong>" . "</br>" . "<strong>" . "Occupancy: " . "</strong>" . $locations[5]['GENERAL_INFO/Occupancy'] . "/" . $locations[5]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'] . "</br>" . "<strong>" . "E-mail: ". "</strong>" . $locations[5]['GENERAL_INFO/Centre_e_mail_Address'] . "</br>" . $Bujanovac_link;
		$Bujanovac_popup = "'" . $Bujanovac_popup . "'";

		$Dimitrovgrad_link = '<a href="site_profiles.php?search=Dimitrovgrad&submit=Select">Site Profile</a>';
		$Dimitrovgrad_popup =  "<strong>" . $locations[16]['GENERAL_INFO/Location_Name'] . " " . strtoupper($locations[16]['GENERAL_INFO/Type']) . "</strong>" . "</br>" . "<strong>" . "Occupancy: " . "</strong>" . $locations[16]['GENERAL_INFO/Occupancy'] . "/" . $locations[16]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'] . "</br>" . "<strong>" . "E-mail: ". "</strong>" . $locations[16]['GENERAL_INFO/Centre_e_mail_Address'] . "</br>" . $Dimitrovgrad_link;
		$Dimitrovgrad_popup = "'" . $Dimitrovgrad_popup . "'";

		$Divljana_link = '<a href="site_profiles.php?search=Divljana&submit=Select">Site Profile</a>';
		$Divljana_popup =  "<strong>" . $locations[18]['GENERAL_INFO/Location_Name'] . " " . strtoupper($locations[18]['GENERAL_INFO/Type']) . "</strong>" . "</br>" . "<strong>" . "Occupancy: " . "</strong>" . $locations[18]['GENERAL_INFO/Occupancy'] . "/" . $locations[18]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'] . "</br>" . "<strong>" . "E-mail: ". "</strong>" . $locations[18]['GENERAL_INFO/Centre_e_mail_Address'] . "</br>" . $Divljana_link;
		$Divljana_popup = "'" . $Divljana_popup . "'";

		$Kikinda_link = '<a href="site_profiles.php?search=Kikinda&submit=Select">Site Profile</a>';
		$Kikinda_popup =  "<strong>" . $locations[12]['GENERAL_INFO/Location_Name'] . " " . strtoupper($locations[12]['GENERAL_INFO/Type']) . "</strong>" . "</br>" . "<strong>" . "Occupancy: " . "</strong>" . $locations[12]['GENERAL_INFO/Occupancy'] . "/" . $locations[12]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'] . "</br>" . "<strong>" . "E-mail: ". "</strong>" . $locations[12]['GENERAL_INFO/Centre_e_mail_Address'] . "</br>" . $Kikinda_link;
		$Kikinda_popup = "'" . $Kikinda_popup . "'";

		$Krnjaca_link = '<a href="site_profiles.php?search=Krnjaca&submit=Select">Site Profile</a>';
		$Krnjaca_popup =  "<strong>" . $locations[14]['GENERAL_INFO/Location_Name'] . " " . strtoupper($locations[14]['GENERAL_INFO/Type']) . "</strong>" . "</br>" . "<strong>" . "Occupancy: " . "</strong>" . $locations[14]['GENERAL_INFO/Occupancy'] . "/" . $locations[14]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'] . "</br>" . "<strong>" . "E-mail: ". "</strong>" . $locations[14]['GENERAL_INFO/Centre_e_mail_Address'] . "</br>" . $Krnjaca_link;
		$Krnjaca_popup = "'" . $Krnjaca_popup . "'";

		$Obrenovac_link = '<a href="site_profiles.php?search=Obrenovac&submit=Select">Site Profile</a>';
		$Obrenovac_popup =  "<strong>" . $locations[15]['GENERAL_INFO/Location_Name'] . " " . strtoupper($locations[15]['GENERAL_INFO/Type']) . "</strong>" . "</br>" . "<strong>" . "Occupancy: " . "</strong>" . $locations[15]['GENERAL_INFO/Occupancy'] . "/" . $locations[15]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'] . "</br>" . "<strong>" . "E-mail: ". "</strong>" . $locations[15]['GENERAL_INFO/Centre_e_mail_Address'] . "</br>" . $Obrenovac_link;
		$Obrenovac_popup = "'" . $Obrenovac_popup . "'";

		$Pirot_link = '<a href="site_profiles.php?search=Pirot&submit=Select">Site Profile</a>';
		$Pirot_popup =  "<strong>" . $locations[17]['GENERAL_INFO/Location_Name'] . " " . strtoupper($locations[17]['GENERAL_INFO/Type']) . "</strong>" . "</br>" . "<strong>" . "Occupancy: " . "</strong>" . $locations[17]['GENERAL_INFO/Occupancy'] . "/" . $locations[17]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'] . "</br>" . "<strong>" . "E-mail: ". "</strong>" . $locations[17]['GENERAL_INFO/Centre_e_mail_Address'] . "</br>" . $Pirot_link;
		$Pirot_popup = "'" . $Pirot_popup . "'";

		$Presevo_link = '<a href="site_profiles.php?search=Presevo&submit=Select">Site Profile</a>';
		$Presevo_popup =  "<strong>" . $locations[2]['GENERAL_INFO/Location_Name'] . " " . strtoupper($locations[2]['GENERAL_INFO/Type']) . "</strong>" . "</br>" . "<strong>" . "Occupancy: " . "</strong>" . $locations[2]['GENERAL_INFO/Occupancy'] . "/" . $locations[2]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'] . "</br>" . "<strong>" . "E-mail: ". "</strong>" . $locations[2]['GENERAL_INFO/Centre_e_mail_Address'] . "</br>" . $Presevo_link;
		$Presevo_popup = "'" . $Presevo_popup . "'";

		$Principovac_link = '<a href="site_profiles.php?search=Principovac&submit=Select">Site Profile</a>';
		$Principovac_popup =  "<strong>" . $locations[13]['GENERAL_INFO/Location_Name'] . " " . strtoupper($locations[13]['GENERAL_INFO/Type']) . "</strong>" . "</br>" . "<strong>" . "Occupancy: " . "</strong>" . $locations[13]['GENERAL_INFO/Occupancy'] . "/" . $locations[13]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'] . "</br>" . "<strong>" . "E-mail: ". "</strong>" . $locations[13]['GENERAL_INFO/Centre_e_mail_Address'] . "</br>" . $Principovac_link;
		$Principovac_popup = "'" . $Principovac_popup . "'";

		$SjenicaV_link = '<a href="site_profiles.php?search=Sjenica&submit=Select">Site Profile</a>';
		$SjenicaV_popup =  "<strong>" . $locations[8]['GENERAL_INFO/Location_Name'] . " " . strtoupper($locations[8]['GENERAL_INFO/Type']) . "</strong>" . "</br>" . "<strong>" . "Occupancy: " . "</strong>" . $locations[8]['GENERAL_INFO/Occupancy'] . "/" . $locations[8]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'] . "</br>" . "<strong>" . "E-mail: ". "</strong>" . $locations[8]['GENERAL_INFO/Centre_e_mail_Address'] . "</br>" . $SjenicaV_link;
		$SjenicaV_popup = "'" . $SjenicaV_popup . "'";

		$Sombor_link = '<a href="site_profiles.php?search=Sombor&submit=Select">Site Profile</a>';
		$Sombor_popup =  "<strong>" . $locations[9]['GENERAL_INFO/Location_Name'] . " " . strtoupper($locations[9]['GENERAL_INFO/Type']) . "</strong>" . "</br>" . "<strong>" . "Occupancy: " . "</strong>" . $locations[9]['GENERAL_INFO/Occupancy'] . "/" . $locations[9]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'] . "</br>" . "<strong>" . "E-mail: ". "</strong>" . $locations[9]['GENERAL_INFO/Centre_e_mail_Address'] . "</br>" . $Sombor_link;
		$Sombor_popup = "'" . $Sombor_popup . "'";

		$Subotica_link = '<a href="site_profiles.php?search=Subotica&submit=Select">Site Profile</a>';
		$Subotica_popup =  "<strong>" . $locations[6]['GENERAL_INFO/Location_Name'] . " " . strtoupper($locations[6]['GENERAL_INFO/Type']) . "</strong>" . "</br>" . "<strong>" . "Occupancy: " . "</strong>" . $locations[6]['GENERAL_INFO/Occupancy'] . "/" . $locations[6]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'] . "</br>" . "<strong>" . "E-mail: ". "</strong>" . $locations[6]['GENERAL_INFO/Centre_e_mail_Address'] . "</br>" . $Subotica_link;
		$Subotica_popup = "'" . $Subotica_popup . "'";

		$Tutin_link = '<a href="site_profiles.php?search=Tutin&submit=Select">Site Profile</a>';
		$Tutin_popup =  "<strong>" . $locations[10]['GENERAL_INFO/Location_Name'] . " " . strtoupper($locations[10]['GENERAL_INFO/Type']) . "</strong>" . "</br>" . "<strong>" . "Occupancy: " . "</strong>" . $locations[10]['GENERAL_INFO/Occupancy'] . "/" . $locations[10]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'] . "</br>" . "<strong>" . "E-mail: ". "</strong>" . $locations[10]['GENERAL_INFO/Centre_e_mail_Address'] . "</br>" . $Tutin_link;
		$Tutin_popup = "'" . $Tutin_popup . "'";

		$Vranje_link = '<a href="site_profiles.php?search=Vranje&submit=Select">Site Profile</a>';
		$Vranje_popup =  "<strong>" . $locations[11]['GENERAL_INFO/Location_Name'] . " " . strtoupper($locations[11]['GENERAL_INFO/Type']) . "</strong>" . "</br>" . "<strong>" . "Occupancy: " . "</strong>" . $locations[11]['GENERAL_INFO/Occupancy'] . "/" . $locations[11]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'] . "</br>" . "<strong>" . "E-mail: ". "</strong>" . $locations[11]['GENERAL_INFO/Centre_e_mail_Address'] . "</br>" . $Vranje_link;
		$Vranje_popup = "'" . $Vranje_popup . "'";

		$Sid_link = '<a href="site_profiles.php?search=Sid&submit=Select">Site Profile</a>';
		$Sid_popup =  "<strong>" . $locations[19]['GENERAL_INFO/Location_Name'] . " " . strtoupper($locations[19]['GENERAL_INFO/Type']) . "</strong>" . "</br>" . "<strong>" . "Occupancy: " . "</strong>" . $locations[19]['GENERAL_INFO/Occupancy'] . "/" . $locations[19]['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'] . "</br>" . "<strong>" . "E-mail: ". "</strong>" . $locations[19]['GENERAL_INFO/Centre_e_mail_Address'] . "</br>" . $Sid_link;
		$Sid_popup = "'" . $Sid_popup . "'";

		//this loop goes trough all locations and sums up the numbers for the charts
		foreach($locations as $location){
		$men_total += $location['STATISTICS/Number_of_Men'];
		$women_total += $location['STATISTICS/Number_of_Women'];
		$children_total += $location['STATISTICS/Number_of_Children'];
		$uasc_total += $location['STATISTICS/Number_of_UASC'];
		$afg_total += $location['STATISTICS/Number_of_Persons_from_Afghanistan'];
		$irq_total += $location['STATISTICS/Number_of_Persons_from_Iraq'];
		$pak_total += $location['STATISTICS/Number_of_Persons_from_Pakistan'];
		$syr_total += $location['STATISTICS/Number_of_Persons_from_Syria'];
		$oth_total += $location['STATISTICS/Number_of_Persons_Other'];
		$occ_total += $location['GENERAL_INFO/Occupancy'];
		$capacity_total += $location['SHELTER/Number_of_Places_per_4_5_5_5_m2_p'];
		}
		$otherChildren_total = $children_total - $uasc_total;
		?>	


		<title>CENTRE PROFILING: The Republic of Serbia</title>
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
	          ['Men', <?php echo $men_total; ?>],
      		  ['Women', <?php echo $women_total; ?>],
	          ['Children', <?php echo $children_total; ?>]
	        ]);

	        var options = {
	          title: 'Age/Gender Breakdown',
	          colors: ['#0072BC', '#338EC9', '#99C7E4']
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
	          ['UASC', <?php echo $uasc_total; ?>],
	          ['Other Children', <?php echo $otherChildren_total; ?>],
	        ]);

	        var options = {
	          title: 'UASC/Other Children',
	          colors: ['#0072BC', '#338EC9'],
	          pieHole: 0.4,
	        };

	        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
	        chart.draw(data, options);
	      }
	    </script>
	    </script>
		    <!--
			A scrtipt for generating the CoO bar chart (check for more info: https://developers.google.com/chart/)
			CoOs are pulled from KoBo data
			-->
		    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
			  <script type="text/javascript">
			    google.charts.load("current", {packages:['corechart']});
			    google.charts.setOnLoadCallback(drawChart);
			    function drawChart() {
			      var data = google.visualization.arrayToDataTable([
			        ["Country of Origin", "PoCs", { role: "style" } ],
			        ["Afghanistan", <?php echo $afg_total ?>, "#0072BC"],
			        ["Iraq", <?php echo $irq_total ?>, "#0072BC"],
			        ["Pakistan", <?php echo $pak_total ?>, "#0072BC"],
			        ["Syria", <?php echo $syr_total ?>, "#0072BC"],
			        ["Other", <?php echo $oth_total ?>, "#0072BC"]
			      ]);

			      var view = new google.visualization.DataView(data);
			      view.setColumns([0, 1,
			                       { calc: "stringify",
			                         sourceColumn: 1,
			                         type: "string",
			                         role: "annotation" },
			                       2]);

			      var options = {
			      	title: 'Top Countries of Origin',
			        width: 800,
			        height: 300,
			        bar: {groupWidth: "70%"},
			        legend: { position: "none" },
			      };
			      var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
			      chart.draw(view, options);
			  }
  			</script>
	</head>
	<body>
		<div align="center">
			<table style="font-family: 'Lato', sans-serif; font-size: 14;">
				<tr>
					<td colspan="3" style="background-color: #0072BC ;font-family: 'Lato', sans-serif; font-size: 26; color: white; text-align: left; height: 70"><strong>CENTRE PROFILING</strong></br><span style="font-size: 20">THE REPUBLIC OF SERBIA</span><div style="text-align: right;"></div></td>
				</tr>
				<tr>
					<td rowspan="2">
						<div id="map" style="width: 500px; height: 600px"></div>
		 				<script>
		 					//This draws up the map using coordinates from KoBo and popust generated before
		 						var map = L.map('map').setView([44.153358, 20.843502], 7);
								var Esri_WorldStreetMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
							
								}).addTo(map);


						

							L.marker([<?php echo $locations[0]['GENERAL_INFO/Latitude']?>, <?php echo $locations[0]['GENERAL_INFO/Longitude']?>]).addTo(map)
			    			.bindPopup(<?php echo $Adasevci_popup ?>) 
			    			L.marker([<?php echo $locations[1]['GENERAL_INFO/Latitude']?>, <?php echo $locations[1]['GENERAL_INFO/Longitude']?>]).addTo(map)
			    			.bindPopup(<?php echo $Banja_popup ?>)
			    			L.marker([<?php echo $locations[3]['GENERAL_INFO/Latitude']?>, <?php echo $locations[3]['GENERAL_INFO/Longitude']?>]).addTo(map)
			    			.bindPopup(<?php echo $Bogovadja_popup ?>)
			    			L.marker([<?php echo $locations[4]['GENERAL_INFO/Latitude']?>, <?php echo $locations[4]['GENERAL_INFO/Longitude']?>]).addTo(map)
			    			.bindPopup(<?php echo $Bosilegrad_popup ?>)
			    			L.marker([<?php echo $locations[5]['GENERAL_INFO/Latitude']?>, <?php echo $locations[5]['GENERAL_INFO/Longitude']?>]).addTo(map)
			    			.bindPopup(<?php echo $Bujanovac_popup ?>)
			    			L.marker([<?php echo $locations[16]['GENERAL_INFO/Latitude']?>, <?php echo $locations[16]['GENERAL_INFO/Longitude']?>]).addTo(map)
			    			.bindPopup(<?php echo $Dimitrovgrad_popup ?>)
			    			L.marker([<?php echo $locations[18]['GENERAL_INFO/Latitude']?>, <?php echo $locations[18]['GENERAL_INFO/Longitude']?>]).addTo(map)
			    			.bindPopup(<?php echo $Divljana_popup ?>)
			    			L.marker([<?php echo $locations[12]['GENERAL_INFO/Latitude']?>, <?php echo $locations[12]['GENERAL_INFO/Longitude']?>]).addTo(map)
			    			.bindPopup(<?php echo $Kikinda_popup ?>)
			    			L.marker([<?php echo $locations[14]['GENERAL_INFO/Latitude']?>, <?php echo $locations[14]['GENERAL_INFO/Longitude']?>]).addTo(map)
			    			.bindPopup(<?php echo $Krnjaca_popup ?>)
			    			L.marker([<?php echo $locations[15]['GENERAL_INFO/Latitude']?>, <?php echo $locations[15]['GENERAL_INFO/Longitude']?>]).addTo(map)
			    			.bindPopup(<?php echo $Obrenovac_popup ?>)
			    			L.marker([<?php echo $locations[17]['GENERAL_INFO/Latitude']?>, <?php echo $locations[17]['GENERAL_INFO/Longitude']?>]).addTo(map)
			    			.bindPopup(<?php echo $Pirot_popup ?>)
			    			L.marker([<?php echo $locations[2]['GENERAL_INFO/Latitude']?>, <?php echo $locations[2]['GENERAL_INFO/Longitude']?>]).addTo(map)
			    			.bindPopup(<?php echo $Presevo_popup ?>)
			    			L.marker([<?php echo $locations[13]['GENERAL_INFO/Latitude']?>, <?php echo $locations[13]['GENERAL_INFO/Longitude']?>]).addTo(map)
			    			.bindPopup(<?php echo $Principovac_popup ?>)
			    			L.marker([<?php echo $locations[8]['GENERAL_INFO/Latitude']?>, <?php echo $locations[8]['GENERAL_INFO/Longitude']?>]).addTo(map)
			    			.bindPopup(<?php echo $SjenicaV_popup ?>)
			    			L.marker([<?php echo $locations[9]['GENERAL_INFO/Latitude']?>, <?php echo $locations[9]['GENERAL_INFO/Longitude']?>]).addTo(map)
			    			.bindPopup(<?php echo $Sombor_popup ?>)
			    			L.marker([<?php echo $locations[6]['GENERAL_INFO/Latitude']?>, <?php echo $locations[6]['GENERAL_INFO/Longitude']?>]).addTo(map)
			    			.bindPopup(<?php echo $Subotica_popup ?>)
			    			L.marker([<?php echo $locations[10]['GENERAL_INFO/Latitude']?>, <?php echo $locations[10]['GENERAL_INFO/Longitude']?>]).addTo(map)
			    			.bindPopup(<?php echo $Tutin_popup ?>)
			    			L.marker([<?php echo $locations[11]['GENERAL_INFO/Latitude']?>, <?php echo $locations[11]['GENERAL_INFO/Longitude']?>]).addTo(map)
			    			.bindPopup(<?php echo $Vranje_popup ?>)
			    			L.marker([<?php echo $locations[19]['GENERAL_INFO/Latitude']?>, <?php echo $locations[19]['GENERAL_INFO/Longitude']?>]).addTo(map)
			    			.bindPopup(<?php echo $Sid_popup ?>)
  							</script>
  					</td>
  					<!--This generates the charts-->
  					<td colspan="2">
						<div id="columnchart_values" style="width: 800px; height: 300px;"></div>
					</td>
				</tr>
				<tr>
					<td>
						<div id="piechart" style="width: 400px; height: 300px;"></div>
					</td>
					<td>
						<div id="donutchart" style="width: 400px; height: 300px;"></div>
					</td>
				</tr>
				<tr>
			 	<!--
				This is the disclaimer at the end of the end of the page
			 	-->
			 		<td colspan="4" style="font-family: 'Lato', sans-serif; font-size: 10; font-weight: bold;">
			 			</br>
			 			<p> *Data as of <strong><?php echo $locations[0]['GENERAL_INFO/Update_Month_Year']?></strong></br></br>
			 				UNHCR Serbia cannot vouch for the accuracy of all data provided from various sources. For any comments or suggestins, feel free to <a href="http://www.unhcr.rs/opste/vesti-misljenja/kontakti.html">contact us</a>.</br></br>
			 				This assessment was conducted with reference to national legislation of Serbia, EU Reception Directives, EASO Guidelines, Sphere and UNHCR standards.</br></br>
			 				You can also download the <a href="https://data2.unhcr.org/en/documents/details/55034">PDF version</a> from the <a href="http://data2.unhcr.org/en/situations">Operational Data Portal</a>.
			 			</br></br>
			 				Return to <a href="http://www.unhcr.rs">UNHCR.rs</a>.
			 			</p>
			 		</td>
			 	</tr>
			</table>

	</body>
</html>
