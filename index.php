<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" >
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Video player</title>
    <style>
        body {
            margin: 0;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
			background: black;
        }
        #videoPlayer {
            width: 100%;
            height: 100%;
            display: none;
        }
		button { 
		background: transparent; 
		border: none; 
		color: white; 
		font-size: 20px; 
		padding: 10px 20px; 
		cursor: pointer; }
    </style>
</head>
<body>
    <button id="playButton">Play Video</button>
	<video id="videoPlayer" autoplay loop controls></video>
<script>
        async function get() {
            try {
                const response = await fetch('server.php');
                const data = await response.json();

				file = data['current'];

			
            } catch (error) {
                console.error('Error:', error);
            }
			
			playVideo(file);
        }
		
        async function checkRefresh() {
            try {
                const response = await fetch('server.php');
                const data = await response.json();

				if (data['current'] != file) { 
					file = data['current'];
					window.location.reload();
				}
            
            } catch (error) {
                console.error('Error:', error);
            }
        }
		
		function playVideo(file) {
			document.getElementById('playButton').addEventListener('click', () => {
				const videoPlayer = document.getElementById('videoPlayer');
				const button = document.getElementById('playButton');

				videoPlayer.src = 'video/' + file;
				videoPlayer.style.display = 'block';
				videoPlayer.requestFullscreen({ navigationUI: 'auto' }).catch(err => {
						console.log('Error attempting to enable full-screen mode:', err.message);

				});
            
				button.style.display = 'none'; // Hide the button after click
			});
		}
		get();
        setInterval(checkRefresh, 5000); // Check every 5 seconds
    </script>
</body>
</html>