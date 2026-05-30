<?php
// index.php
// Main file for displaying the webpage
require_once 'Backend.php'; // Loads editable data
require_once 'Theme.php';    // Loads theme data

// Define variables for accessing Backend and Theme data
$data = $backend_data;
$theme = $current_theme;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $data['page_title']; ?></title>
	<link rel="icon" type="image/png" href="./logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        :root {
            /* Fetch colors from Theme.php */
            --primary-color: <?php echo $theme['--primary-color']; ?>;
            --secondary-color: <?php echo $theme['--secondary-color']; ?>;
            --text-color-light: <?php echo $theme['--text-color-light']; ?>;
            --text-color-medium: <?php echo $theme['--text-color-medium']; ?>;
            --dark-base-color: <?php echo $theme['--dark-base-color']; ?>;
            --gradient-start: <?php echo $theme['--gradient-start']; ?>;
            --gradient-end: <?php echo $theme['--gradient-end']; ?>;
            --accent-green: <?php echo $theme['--accent-green']; ?>;
            --accent-red: <?php echo $theme['--accent-red']; ?>;
            --button-store-start: <?php echo $theme['--button-store-start']; ?>;
            --button-store-end: <?php echo $theme['--button-store-end']; ?>;
            --button-discord-start: <?php echo $theme['--button-discord-start']; ?>;
            --button-discord-end: <?php echo $theme['--button-discord-end']; ?>;
            --button-play-start: <?php echo $theme['--button-play-start']; ?>;
            --button-play-end: <?php echo $theme['--button-play-end']; ?>;
        }

        html {
            font-size: 16px;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            color: var(--text-color-light);
            background-color: var(--dark-base-color); /* Use color from theme */
            overflow: hidden;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Animated Preloader */
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 1s ease-out, visibility 1s ease-out;
            pointer-events: all;
        }

        .preloader-hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .spinner {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 8px solid rgba(255, 255, 255, 0.2);
            border-top-color: var(--secondary-color);
            animation: spin 1.5s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Interactive Background */
        .background-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .background-image {
            position: absolute;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            filter: brightness(0.7) saturate(1.1);
            transition: transform 0.1s ease-out;
        }

        .overlay-gradient {
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, <?php echo $theme['--dark-base-color']; ?>cc 0%, <?php echo $theme['--gradient-end']; ?>cc 100%); /* Use color from theme */
            mix-blend-mode: multiply;
        }

        .interactive-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            background: linear-gradient(45deg, <?php echo $theme['--primary-color']; ?>80 0%, <?php echo $theme['--secondary-color']; ?>80 100%); /* Use color from theme */
            border-radius: 50%;
            opacity: 0;
            animation: floatAndFade 20s infinite ease-in-out;
            filter: blur(20px);
        }

        .shape:nth-child(1) { width: 150px; height: 150px; left: 10%; top: 20%; animation-delay: 0s; }
        .shape:nth-child(2) { width: 200px; height: 200px; left: 70%; top: 50%; animation-delay: 5s; }
        .shape:nth-child(3) { width: 100px; height: 100px; left: 40%; top: 80%; animation-delay: 10s; }
        .shape:nth-child(4) { width: 180px; height: 180px; left: 25%; top: 60%; animation-delay: 15s; }
        .shape:nth-child(5) { width: 120px; height: 120px; left: 80%; top: 10%; animation-delay: 8s; }

        @keyframes floatAndFade {
            0% { transform: translateY(0) scale(0.8); opacity: 0; }
            25% { opacity: 0.8; }
            50% { transform: translateY(-50px) scale(1.2); opacity: 0; }
            75% { opacity: 0.5; }
            100% { transform: translateY(0) scale(0.8); opacity: 0; }
        }

        /* Main Content */
        .main-content {
            position: relative;
            z-index: 10;
            background: rgba(0, 0, 0, 0.5);
            padding: 2.5rem 1.5rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
            text-align: center;
            max-width: 900px;
            width: 90%;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 1s ease-out, transform 1s ease-out;
        }

        .content-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .logo {
            width: 80px;
            height: auto;
            margin-bottom: 1rem;
            filter: drop-shadow(0 0 10px var(--primary-color));
        }

        h1 {
            font-family: 'Press Start 2P', cursive;
            font-size: 3rem;
            margin-bottom: 0.8rem;
            background: linear-gradient(90deg, var(--secondary-color), var(--text-color-medium));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 15px <?php echo explode(', 0 0', $theme['--text-shadow-color-main'])[0]; ?>; /* Use the first part of shadow color */
            line-height: 1.2;
        }

        .subtitle {
            font-size: 1.35rem;
            margin-bottom: 1.2rem;
            color: var(--text-color-medium);
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);
        }

        p {
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 1.8rem;
            color: var(--text-color-light);
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.6);
        }

        /* Custom Buttons */
        .btn-custom {
            padding: 0.9rem 2.2rem;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            cursor: pointer;
            transition: transform 0.15s ease-out, box-shadow 0.15s ease-out, background 0.3s ease, color 0.3s ease;
            position: relative;
            will-change: transform, box-shadow;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            z-index: 1;
            margin: 0.6rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 170px;
            text-decoration: none;
        }

        .btn-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(255,255,255,0.4) 0%, rgba(255,255,255,0.1) 50%);
            transform: translateX(-100%);
            transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            z-index: -1;
        }

        .btn-custom:hover::before {
            transform: translateX(100%);
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.42);
        }

        /* Shop Now Button */
        .btn-store {
            background: linear-gradient(135deg, var(--button-store-start) 0%, var(--button-store-end) 100%);
            color: white;
            animation: pulse 2s infinite ease-in-out;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .btn-store:hover {
            background: linear-gradient(135deg, var(--button-store-end) 0%, var(--button-store-start) 100%);
            box-shadow: 0 8px 20px var(--button-store-start)cc; /* Use button start color with opacity */
        }

        @keyframes pulse {
            0% { box-shadow: 0 5px 15px var(--button-store-start)66; }
            50% { box-shadow: 0 10px 20px var(--button-store-start)AA; }
            100% { box-shadow: 0 5px 15px var(--button-store-start)66; }
        }

        /* Discord Button */
        .btn-discord {
            background: linear-gradient(135deg, var(--button-discord-start) 0%, var(--button-discord-end) 100%);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .btn-discord:hover {
            background: linear-gradient(135deg, var(--button-discord-end) 0%, var(--button-discord-start) 100%);
            box-shadow: 0 8px 20px var(--button-discord-start)cc;
        }

        /* Play Now Button */
        .btn-copy-ip {
            background: linear-gradient(135deg, var(--button-play-start) 0%, var(--button-play-end) 100%);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.2);
            flex-direction: row;
            align-items: center;
        }

        .btn-copy-ip:hover {
            background: linear-gradient(135deg, var(--button-play-end) 0%, var(--button-play-start) 100%);
            box-shadow: 0 8px 20px var(--button-play-start)cc;
        }

        /* Specific style for the IP text within the button */
        .btn-copy-ip .ip-text {
            font-size: 0.9em;
            opacity: 0.9;
            margin-top: 0;
            margin-left: 5px;
            display: inline;
        }

        /* Icon spacing for buttons */
        .btn-custom .fas,
        .btn-custom .fab {
            margin-right: 10px;
        }
        
        .btn-copy-ip .fas {
            margin-right: 10px;
            margin-bottom: 0;
        }

        /* Server Status Section */
        .server-status-card {
            background: rgba(0, 0, 0, 0.55);
            border-radius: 15px;
            padding: 1.5rem;
            margin-top: 3rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            align-items: center;
            gap: 1rem;
        }

        .status-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 0.5rem;
            flex: 1;
            min-width: 120px;
        }

        .status-item h4 {
            margin-bottom: 0.3rem;
            font-size: 1rem;
            color: var(--text-color-medium);
        }

        .status-item .value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--secondary-color);
            text-shadow: 0 0 8px var(--secondary-color);
        }

        .status-item .value.online {
            color: var(--accent-green);
            text-shadow: 0 0 8px var(--accent-green);
        }

        .status-item .value.offline {
            color: var(--accent-red);
            text-shadow: 0 0 8px var(--accent-red);
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            h1 {
                font-size: 2.2rem;
            }
            .subtitle {
                font-size: 1.1rem;
            }
            p {
                font-size: 0.95rem;
            }
            .btn-custom {
                padding: 0.7rem 1.8rem;
                font-size: 0.95rem;
                width: 85%;
                margin: 0.4rem auto;
                display: block;
            }
            .server-status-card {
                flex-direction: column;
                align-items: stretch;
            }
        }

        @media (max-width: 576px) {
            h1 {
                font-size: 1.8rem;
            }
            .main-content {
                padding: 1.5rem 0.8rem;
            }
            .status-item .value {
                font-size: 1.3rem;
            }
            .subtitle {
                font-size: 1rem;
            }
            p {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

    <div id="preloader">
        <div class="spinner"></div>
    </div>

    <div class="background-container">
        <div class="background-image" id="backgroundImage"></div>
        <div class="overlay-gradient"></div>
        <div class="interactive-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
    </div>

    <div class="container main-content" id="mainContent">
        <img src="./logo.png" alt="Hypixel Logo" class="logo">
        <h1><?php echo $data['main_title']; ?></h1>
        <p class="subtitle"><?php echo $data['subtitle_text']; ?></p>
        <p><?php echo $data['description_text']; ?></p>

        <div class="d-flex flex-wrap justify-content-center mb-4">
            <a href="<?php echo $data['store_button_url']; ?>" class="btn-custom btn-store">
                <i class="fas fa-shopping-bag me-2"></i> <?php echo $data['store_button_text']; ?>
            </a>
            <a href="<?php echo $data['discord_button_url']; ?>" target="_blank" class="btn-custom btn-discord">
                <i class="fab fa-discord me-2"></i> <?php echo $data['discord_button_text']; ?>
            </a>
            <button class="btn-custom btn-copy-ip" id="copyIpButton">
                <i class="fas fa-play me-2"></i> <?php echo $data['play_button_text']; ?> <span class="ip-text" id="ipAddressText"><?php echo $data['server_ip']; ?></span>
            </button>
        </div>

        <div class="server-status-card">
            <div class="status-item">
                <h4>Online</h4>
                <span class="value" id="playersOnline">-</span>
            </div>
            <div class="status-item">
                <h4>Version</h4>
                <span class="value" id="minecraftVersion"><?php echo $data['minecraft_version']; ?></span> 
            </div>
            <div class="status-item">
                <h4>Status</h4>
                <span class="value" id="serverStatus">-</span>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcxhSLfFdfyQppXhFwibj4YmXsPxFxPpPzPZ" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const preloader = document.getElementById('preloader');
            const mainContent = document.getElementById('mainContent');
            const backgroundImage = document.getElementById('backgroundImage');
            const copyIpButton = document.getElementById('copyIpButton');
            const ipAddressText = document.getElementById('ipAddressText');

            // Array of background images
            const backgroundImages = [
                './bg1.jpg',
                './bg2.jpg',
                './bg3.jpg',
                './bg4.jpg',
                './bg5.jpg',
                './bg6.jpg',
                './bg7.jpg'
            ];

            // Function to set a random background image
            function setRandomBackgroundImage() {
                const randomIndex = Math.floor(Math.random() * backgroundImages.length);
                const selectedImage = backgroundImages[randomIndex];
                backgroundImage.style.backgroundImage = `url('${selectedImage}')`;
            }

            // Set initial random background image on load
            setRandomBackgroundImage();

            // --- Preloader Animation ---
            setTimeout(() => {
                preloader.classList.add('preloader-hidden');
                mainContent.classList.add('content-visible');
                document.body.style.overflow = 'auto';
            }, 2000);

            // --- Interactive Background (Mouse Parallax) ---
            document.body.addEventListener('mousemove', (e) => {
                const x = (window.innerWidth - e.pageX * 2) / 100;
                const y = (window.innerHeight - e.pageY * 2) / 100;
                backgroundImage.style.transform = `translate(${x}px, ${y}px) scale(1.03)`;
            });

            // --- Fetch Minecraft Server Status ---
            async function fetchServerStatus() {
                // Fetch API URL from PHP (which comes from Backend.php)
                const apiUrl = "<?php echo $data['api_url']; ?>";
                try {
                    const response = await fetch(apiUrl);
                    const data = await response.json();

                    const playersOnlineElement = document.getElementById('playersOnline');
                    const serverStatusElement = document.getElementById('serverStatus');

                    if (data.online) {
                        playersOnlineElement.textContent = data.players.online.toLocaleString();
                        playersOnlineElement.classList.add('online');
                        playersOnlineElement.classList.remove('offline');
                        
                        serverStatusElement.textContent = 'Online';
                        serverStatusElement.classList.add('online');
                        serverStatusElement.classList.remove('offline');
                    } else {
                        playersOnlineElement.textContent = 'Offline';
                        playersOnlineElement.classList.add('offline');
                        playersOnlineElement.classList.remove('online');

                        serverStatusElement.textContent = 'Offline';
                        serverStatusElement.classList.add('offline');
                        serverStatusElement.classList.remove('online');
                    }
                } catch (error) {
                    console.error('Error fetching server status:', error);
                    document.getElementById('playersOnline').textContent = 'Error';
                    document.getElementById('serverStatus').textContent = 'Error';
                    document.getElementById('serverStatus').classList.add('offline');
                }
            }

            fetchServerStatus();
            setInterval(fetchServerStatus, 60000);

            // --- Copy IP to Clipboard ---
            copyIpButton.addEventListener('click', () => {
                // Fetch IP Address from PHP (which comes from Backend.php)
                const ipToCopy = "<?php echo $data['server_ip']; ?>";
                navigator.clipboard.writeText(ipToCopy).then(() => {
                    const originalIcon = copyIpButton.querySelector('.fas').className;
                    const originalPlayText = "<?php echo $data['play_button_text']; ?>"; // Original "Play Now" text
                    const originalIpText = ipAddressText.textContent; // Original IP text

                    copyIpButton.innerHTML = `<i class="fas fa-check-circle me-2"></i> Copied!`;
                    
                    setTimeout(() => {
                        // Revert back to original content, including IP address
                        copyIpButton.innerHTML = `<i class="${originalIcon}"></i> ${originalPlayText} <span class="ip-text">${originalIpText}</span>`;
                    }, 1500);
                }).catch(err => {
                    console.error('Failed to copy IP:', err);
                    alert('Failed to copy IP. Please copy it manually: ' + ipToCopy);
                });
            });
        });
    </script>
</body>
</html>
