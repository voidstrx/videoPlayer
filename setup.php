<?php
$json = file_get_contents('http://localhost/server.php');
$data = json_decode($json, true);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="w3.css">
    <title>Display Files and Play Video</title>
    <style>
        #videoPlayer {
            display: none;
            width: 100%;
            height: auto;
            margin: 20px 0;
        }
		#setCurrent {
		display: none;
		}
    </style>

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
</head>
<body class="w3-light-grey ">
    <div class="w3-bar w3-top w3-black w3-large">
        <button class="w3-bar-item w3-hover-green w3-left w3-black" id="setCurrent" onclick="setAsCurrent()">Отправить на TV</button>
        <button class="w3-bar-item w3-hover-red w3-right w3-black"  id="refresh" onclick="refresh()">Обновить список видео</button>
    </div>

    <div class="w3-main" style="margin-top:43px;">



    
        <div id="status" class="w3-container w3-center w3-padding-16 w3-dark-gray"><h1>
            <?php
                echo 'Текущее видео на TV: ' . $data['current'];
            ?></h1>
	    </div>

    <table id="files"  class="w3-table w3-striped w3-white">
            <tr>
                <th></th>
            </tr>

            
        <?php
        if (isset($data['files']) && !empty($data['files'][0])) {

			foreach ($data['files'][0] as $file) {
				
                echo '<tr><td><button class="w3-bar-item" onclick="playVideo(\'' . $file . '\')">Play ' . $file . '</button></td></tr>';
            }
			
        } else {
            echo 'No files found.';
        }
        ?>
        
    </table>

    <video id="videoPlayer" controls class="w3-center"></video>

</div>
</div>

<div class="w3-padding w3-padding-48"></div>
    <div class="w3-container w3-dark-grey w3-padding-32">
    Видеофайлы находятся в S:&#47;98_tv
    </div>

    <footer class="w3-container w3-black">
        <p>Powered by kcio-chuna</p>
    </footer>
</body>
</html>
