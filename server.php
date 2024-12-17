<?php
$data = json_decode(file_get_contents('settings.json'), true);

function write($file){
	file_put_contents('settings.json', json_encode($file)); 
}

function refresh(){
	$directory = 'video';
	$filesArray = array_values((array_diff(scandir($directory, SCANDIR_SORT_ASCENDING),array('.', '..', 'videoPlayer.html'))));
	
	$data = [ 
	'files' => [$filesArray],
	'current' => $filesArray[0]
	];
	
	write($data);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$rawData = file_get_contents('php://input'); // Decode the JSON data 
	$request = json_decode($rawData, true);
	
	
	if (isset($request['current'])){
		$data['current'] = $request['current'];
		write($data);
	}
	
	if (isset($request['refresh'])){
		refresh();
	}
}

header('Content-Type: application/json');
echo json_encode($data, JSON_PRETTY_PRINT);
?>
