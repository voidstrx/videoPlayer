<?php
$json = file_get_contents('http://localhost/server.php');
$data = json_decode($json, true);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Display Files and Play Video</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        #files {
            background-color: #f0f0f0;
            padding: 10px;
        }
        #videoPlayer {
            display: none;
            width: 80%;
            height: auto;
            margin: 20px 0;
        }
		#setCurrent {
		display: none;
		}
    </style>
</head>
<body>
    <h1>Видео в S:\98_tv</h1>
    <div id="files">
        <?php

        if (isset($data['files']) && !empty($data['files'][0])) {
            
			
			foreach ($data['files'][0] as $file) {
				
                echo '<button onclick="playVideo(\'' . $file . '\')">Play ' . $file . '</button><br>';
            }
			

        } else {
            echo 'No files found.';
        }
        ?>
    </div>
	
	</div>
		<div id="refresh">
        <button id="refresh" onclick="refresh()">Обновить список видео</button>
    </div>
	
    <div id="status">
        <?php
        echo 'Текущее видео на TV: ' . $data['current'];
        ?>
	</div>

	
    <video id="videoPlayer" controls></video>
	
	<div id="setCurrent">
        <button id="setAsCurrentButton" onclick="setAsCurrent()">Отправить на TV</button>
    </div>
	
    <script>
        function playVideo(file) {
            const videoPlayer = document.getElementById('videoPlayer');
            videoPlayer.src = 'video/' + file;
            videoPlayer.style.display = 'block';
            videoPlayer.play();
			currentFile = file; // Show the "Set as Current" button when the video is playing 
			document.getElementById('setCurrent').style.display = 'block';
        }
		
		async function setAsCurrent() {
            if (currentFile) {
				document.getElementById('status').innerText = `Текущее видео на TV: ${currentFile}`;
				
				try {
                const response = await fetch('server.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json' // Specify the content type
                    },
                      body: JSON.stringify({
					  current: currentFile
					  })
                });

					if (!response.ok) {
						throw new Error('Network response was not ok');
					}

				} catch (error) {
					console.error('Error:', error);
					document.getElementById('response').innerText = 'Error: ' + error.message;
				}
					
					
            } else {
                alert('No video selected');
            }
        }
		
		
		async function refresh() {
				try {
                const response = await fetch('server.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json' // Specify the content type
                    },
                      body: JSON.stringify({
					  refresh: true
					  })
                });

				const data = await response.json();


                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
				window.location.reload();

            } catch (error) {
                console.error('Error:', error);
                document.getElementById('response').innerText = 'Error: ' + error.message;
            }
				
        }
    </script>
</body>
</html>
