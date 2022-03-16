
<html>
	<head>
		<link rel="stylesheet" href="css/styles.css" media="screen" />
		<title>RSS Feed</title>
	</head>

<script type="text/JavaScript">
<!--
function timedRefresh(timeoutPeriod) {
	setTimeout("location.reload(true);",timeoutPeriod);
}
//   -->
</script>
</head>
<body onload="JavaScript:timedRefresh(30000);">

<?php
date_default_timezone_set('America/Denver');







// Feed RSS
$feed_urlFP = 'http://feeds.feedburner.com/SlickdealsnetFP?format=xml';
$contentsFP = file_get_contents($feed_urlFP);
$xFP = new SimpleXMLElement($contentsFP);

$feed_urlHT = 'http://feeds.feedburner.com/SlickdealsnetHT?format=xml';
$contentsHT = file_get_contents($feed_urlHT);
$xHT = new SimpleXMLElement($contentsHT);

$feed_urlHD = 'http://feeds.feedburner.com/SlickdealsnetForums-9?format=xml';
$contentsHD = file_get_contents($feed_urlHD);
$xHD = new SimpleXMLElement($contentsHD);

/*var_dump($xHD);*/


$yFP = 0;
$yHT = 0;
$yHD = 0;
// End




//Connect to SQL
$con=mysqli_connect("localhost","REDACTED",
	"REDACTED","REDACTED");
// End


// Check connection
if (mysqli_connect_errno()) {
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
// End

// Truncate



// End


// Find PID for Cleanup and Sorting
$finderCleanup = mysqli_query($con, "SELECT * FROM entries 
	ORDER BY PID DESC LIMIT 1");
$finderCleanup = mysqli_fetch_array($finderCleanup);

$finderCleanupHT = mysqli_query($con, "SELECT * FROM entriesht 
	ORDER BY PID DESC LIMIT 1");
$finderCleanupHT = mysqli_fetch_array($finderCleanupHT);

$finderCleanupHD = mysqli_query($con, "SELECT * FROM entrieshd 
	ORDER BY PID DESC LIMIT 1");
$finderCleanupHD = mysqli_fetch_array($finderCleanupHD);
// End



// Find last update and current time
$sqlTime = $finderCleanup['serverTime'];
$remoteTime = $finderCleanup['remoteTime'];
$remoteTime = DateTime::createFromFormat('Y-m-d H:i:s', $remoteTime);

$sqlTimeHT = $finderCleanupHT['serverTime'];
$remoteTimeHT = $finderCleanupHT['remoteTime'];
$remoteTimeHT = DateTime::createFromFormat('Y-m-d H:i:s', $remoteTimeHT);

$sqlTimeHD = $finderCleanupHD['serverTime'];
$remoteTimeHD = $finderCleanupHD['remoteTime'];
$remoteTimeHD = DateTime::createFromFormat('Y-m-d H:i:s', $remoteTimeHD);
// End



// Functions: Check last update time, and insert data into MySQL
function checkTime($sqlTime, $xFP, $con) {
$yFP = 0;


$sqlTime = DateTime::createFromFormat('Y-m-d H:i:s', $sqlTime);
$sqlTimeH = date_format($sqlTime, "h");
$sqlTimeM = date_format($sqlTime, "i");
$phpTimeH = date("h");
$phpTimeM = date('i');


$comparisonTimeH = $phpTimeH - $sqlTimeH;
$comparisonTimeM = $phpTimeM - $sqlTimeM;



	if ($comparisonTimeH != 0 || $comparisonTimeM >= 0) {

		foreach($xFP->channel->item as $entry) {

			$entry->title = mysqli_real_escape_string($con, $entry->title);
			$entry->link = mysqli_real_escape_string($con, $entry->link);
			$something = DateTime::createFromFormat('D, d M Y H:i:s e', $entry->pubDate);
			$something2 = date_format($something, "Y-m-d H:i:s");
			
				if ($yFP < 20) {

				mysqli_query($con,"INSERT INTO entries (Link, Title, remoteTime)
				VALUES ('$entry->link', '$entry->title', '$something2')");

				
				$yFP++;

			}

			if ($yFP == 20) { 
				// echo "Tables updated successfully.";
				$yFP++;
			}
		}
	} 
}

checkTime($sqlTime, $xFP, $con);






function checkTimeHT($sqlTimeHT, $xHT, $con) {
$yHT = 0;


$sqlTimeHT = DateTime::createFromFormat('Y-m-d H:i:s', $sqlTimeHT);
$sqlTimeHHT = date_format($sqlTimeHT, "h");
$sqlTimeMHT = date_format($sqlTimeHT, "i");
$phpTimeHHT = date("h");
$phpTimeMHT = date('i');


$comparisonTimeHHT = $phpTimeHHT - $sqlTimeHHT;
$comparisonTimeMHT = $phpTimeMHT - $sqlTimeMHT;



	if ($comparisonTimeHHT != 0 || $comparisonTimeMHT >= 0) {

		foreach($xHT->channel->item as $entryHT) {

			$entryHT->title = mysqli_real_escape_string($con, $entryHT->title);
			$entryHT->link = mysqli_real_escape_string($con, $entryHT->link);
			$somethingHT = DateTime::createFromFormat('D, d M Y H:i:s e', $entryHT->pubDate);
			$something2HT = date_format($somethingHT, "Y-m-d H:i:s");
			
				if ($yHT < 20) {

				mysqli_query($con,"INSERT INTO entriesht (Link, Title, remoteTime)
				VALUES ('$entryHT->link', '$entryHT->title', '$something2HT')");

				
				$yHT++;

			}

			if ($yHT == 20) { 
				// echo "Tables updated successfully.";
				$yHT++;
			}
		}
	} 
}

checkTimeHT($sqlTimeHT, $xHT, $con);






function checkTimeHD($sqlTimeHD, $xHD, $con) {
    $yHD = 0;


    $sqlTimeHD = DateTime::createFromFormat('Y-m-d H:i:s', $sqlTimeHD);
    $sqlTimeHHD = date_format($sqlTimeHD, "h");
    $sqlTimeMHD = date_format($sqlTimeHD, "i");
    $phpTimeHHD = date("h");
    $phpTimeMHD = date('i');


    $comparisonTimeHHD = $phpTimeHHD - $sqlTimeHHD;
    $comparisonTimeMHD = $phpTimeMHD - $sqlTimeMHD;



    if ($comparisonTimeHHD != 0 || $comparisonTimeMHD >= 0) {

        foreach($xHD->channel->item as $entryHD) {

            $entryHD->title = mysqli_real_escape_string($con, $entryHD->title);
            $entryHD->link = mysqli_real_escape_string($con, $entryHD->link);
            $somethingHD = DateTime::createFromFormat('D, d M Y H:i:s e', $entryHD->pubDate);
            $something2HD = date_format($somethingHD, "Y-m-d H:i:s");

            if ($yHD < 20) {

                mysqli_query($con,"INSERT INTO entrieshd (Link, Title, remoteTime)
				VALUES ('$entryHD->link', '$entryHD->title', '$something2HD')");


                $yHD++;

            }

            if ($yHD == 20) {
                // echo "Tables updated successfully.";
                $yHD++;
            }
        }
    }
}

checkTimeHD($sqlTimeHD, $xHD, $con);
// End
?> 




		<div class="container">
			<div class="left">
			<h1> Front Page </h1>

	<?php

	// Print RSS feed Front Page
	function getResults($con) {
		
		$yFP = 0;
		
	$results = mysqli_query($con, "SELECT * FROM entries 
	 ORDER BY remoteTime DESC LIMIT 8");


	while  ($rowFp = mysqli_fetch_array($results)) {
		echo "<a target=\"_blank\" href=\"" . $rowFp['Link'] . "\">" .  
		$rowFp['Title'] . "</a><br />" . "<p class=\"date\">" .  
		"<p /><hr class=\"divider\">";
			
		}
	}


	getResults($con);
	// End

	?>

			<br /><br /><h1 class="ht"> Hot Topics </h1>


	<?php

	// Print RSS feed Hot Topics
	function getResultsHT($con) {
		
		$yHT = 0;
		
	$results = mysqli_query($con, "SELECT * FROM entriesht 
	 ORDER BY remoteTime DESC LIMIT 11");


	while  ($rowHT = mysqli_fetch_array($results)) {
		echo "<a target=\"_blank\" href=\"" . $rowHT['Link'] . "\">" .  
		$rowHT['Title'] . "</a><br />" . "<p class=\"date\">" . 
		"<p /><hr class=\"divider\">";

		}
	}

	getResultsHT($con);
	// End

	?>


			</div>
			<div class="right"><h1>Hot Deals</h1>

	<?php

	// Print RSS feed Hot Deals
	function getResultsHD($con) {
		
		$yHD = 0;
		
	$results = mysqli_query($con, "SELECT * FROM entrieshd 
	 ORDER BY remoteTime DESC LIMIT 21");


	while  ($rowHD = mysqli_fetch_array($results)) {
		echo "<a target=\"_blank\" href=\"" . $rowHD['Link'] . "\">" .  
		$rowHD['Title'] . "</a><br />" . "<p class=\"date\">" .
		"<p /><hr class=\"divider\">";

		}
	}



	getResultsHD($con);1

	// End



	?>


			</div>
		</div>
	</body>


<?php 
	// Disconnect from SQL
	mysqli_close($con); 
	// End
?>

</html>