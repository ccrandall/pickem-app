<?php
//if (isset($_POST['pickset-week'])) {

    //$feed_name = $form_data->{'feed_name'};
    // $feed_url = $form_data->{'feed_url'}; 

	$pickset_week = $_POST['pickset-week'];
	$fields = '';
	
	foreach ($_POST as $k=>$v) {
		$fields[$k] = ucwords(str_replace('-',' ', $v));
	}
    // $fields = array($pretty_feed_name,$feed_name,$feed_url);
    $filename = 'pickset'.$pickset_week.'.csv';
    
	$handle = fopen($filename, 'w+');
    $write_csv = fputcsv($handle, $fields);

    fclose($handle);
	
	if (file_exists($filename)) {
		echo json_encode('Picks saved successfully.');
	} else {
		echo json_encode('An error was encountered, please try again');
	}

    //echo json_encode($fields);
	
	// print_r($fields);
	//}

?>
