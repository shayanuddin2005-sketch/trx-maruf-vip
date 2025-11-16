<?php
// PHP structure retained for environment compatibility.
include 'config.php';

$error_message = ""; // Error message variable

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submitted_password = $_POST['activation_code'];

    // Assuming $ADMIN_PASSWORD is defined in config.php
    if (isset($submitted_password) && $submitted_password === $ADMIN_PASSWORD) {
        // Correct password
        $_SESSION['loggedin'] = true; // Mark user as logged in
        header("Location: game-selection.php"); // Redirect to next page
        exit;
    } else {
        // Incorrect password
        $error_message = "ACCESS DENIED! Invalid Key. Contact Admin for activation.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRX MARUF VIP - System Authentication</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap');

        /* --- 4-COLOR PREMIUM PALETTE --- */
        :root {
            --color-neon-blue: #00BFFF;
            --color-cyber-pink: #FF00FF;
            --color-premium-yellow: #FFFF00; 
            --color-neon-green: #00FF7F; 
            --color-error-red: #FF4500;
            --color-text-light: #E0E0E0;
            --color-background-dark: #0a0a0f;
        }

        /* --- KEY FIX: Ensure full viewport coverage and no external overflow --- */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* --- 1. LAGG FIX: CONTAINER GLOW SLOWER (8s -> 10s) AND LIGHTER --- */
        @keyframes neon-glow-pulse {
            0%, 100% {
                border-color: var(--color-neon-blue);
                /* Decreased intensity: 15px -> 10px */
                box-shadow: 0 0 10px rgba(0, 191, 255, 0.5), inset 0 0 5px rgba(0, 191, 255, 0.3);
            }
            25% {
                border-color: var(--color-cyber-pink);
                box-shadow: 0 0 15px rgba(255, 0, 255, 0.7), inset 0 0 7px rgba(255, 0, 255, 0.4);
            }
            50% {
                border-color: var(--color-premium-yellow);
                box-shadow: 0 0 10px rgba(255, 255, 0, 0.5), inset 0 0 5px rgba(255, 255, 0, 0.3);
            }
            75% {
                border-color: var(--color-neon-green);
                box-shadow: 0 0 15px rgba(0, 255, 127, 0.7), inset 0 0 7px rgba(0, 255, 127, 0.4);
            }
        }
        
        /* --- 2. LAGG FIX: BUTTON GRADIENT SLOWER (4s -> 6s) --- */
        @keyframes neon-button-shift {
            0% { background: linear-gradient(135deg, var(--color-neon-blue), var(--color-cyber-pink)); }
            33% { background: linear-gradient(135deg, var(--color-cyber-pink), var(--color-premium-yellow)); }
            66% { background: linear-gradient(135deg, var(--color-premium-yellow), var(--color-neon-green)); }
            100% { background: linear-gradient(135deg, var(--color-neon-green), var(--color-neon-blue)); }
        }

        /* --- 3. LAGG FIX: TEXT GLITCH SLOWER (0.6s -> 1.5s) and LESS SHIFT --- */
        @keyframes text-glitch {
            0%, 100% { transform: translate(0); }
            20% { transform: translate(-0.5px, 0.5px); text-shadow: -0.5px -0.5px 0 var(--color-cyber-pink); }
            40% { transform: translate(-0.5px, -0.5px); text-shadow: 0.5px 0.5px 0 var(--color-neon-blue); }
            60% { transform: translate(0.5px, 0.5px); text-shadow: -0.5px 0.5px 0 var(--color-premium-yellow); }
            80% { transform: translate(0.5px, -0.5px); text-shadow: 0.5px -0.5px 0 var(--color-neon-green); }
        }

        /* Status LED Animation (Kept fast, as it's small) */
        @keyframes status-blink {
            0%, 100% { background-color: var(--color-neon-green); box-shadow: 0 0 5px var(--color-neon-green), 0 0 15px var(--color-neon-green); }
            50% { background-color: rgba(0, 255, 127, 0.3); box-shadow: none; }
        }

        /* --- BASE STYLES --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Orbitron', sans-serif;
            color: var(--color-text-light);
        }

        .login-page {
            background: var(--color-background-dark);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 15px;
            position: relative;
            z-index: 1; 
            padding-bottom: 50px; 
        }
        
        .login-page::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: linear-gradient(0deg, transparent 97%, rgba(0, 191, 255, 0.08) 100%),
                              linear-gradient(90deg, transparent 97%, rgba(0, 191, 255, 0.08) 100%);
            background-size: 25px 25px; 
            z-index: 0;
        }

        .login-container {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            border: 2px solid; 
            padding: 40px 30px; 
            border-radius: 20px;
            width: 100%;
            max-width: 450px;
            text-align: center;
            position: relative;
            z-index: 2;
            animation: neon-glow-pulse 10s linear infinite; /* LAGG FIX: Slower */
        }
        
        .login-container a, .login-container button {
            transition: all 0.3s ease, box-shadow 0.4s ease;
            text-decoration: none;
            display: block;
            width: 100%;
        }

        /* --- LOGO --- */
        .logo h1 {
            font-size: 2.5rem;
            margin-bottom: 5px;
            letter-spacing: 3px;
            color: var(--color-premium-yellow);
            /* LAGG FIX: Lighter Text Shadow */
            text-shadow: 0 0 5px var(--color-premium-yellow), 0 0 10px var(--color-cyber-pink); 
            animation: text-glitch 1.5s infinite alternate; /* LAGG FIX: Much Slower */
        }
        
        /* --- DEVICE CODE BOX --- */
        .device-code-box {
            /* LAGG FIX: Device box glow also slower/lighter */
            animation: neon-glow-pulse 12s linear infinite reverse; 
        }
        .device-code {
            animation: text-glitch 1s infinite alternate; /* LAGG FIX: Slower */
        }

        /* --- KEY BUTTON --- */
        .key-btn {
            animation: neon-glow-pulse 8s linear infinite; /* LAGG FIX: Slower */
        }
        .key-btn:hover {
            /* LAGG FIX: Lighter hover shadow */
            box-shadow: 0 0 15px rgba(0, 255, 127, 0.6); 
            transform: translateY(-2px);
        }

        /* --- LOGIN BUTTON --- */
        .login-btn {
            animation: neon-button-shift 6s linear infinite; /* LAGG FIX: Slower */
        }
        
        /* Rest of the CSS for layout/spacing/colors remains same as previous fixed version */
        
        .system-status {
            position: absolute;
            top: 15px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--color-neon-green);
            text-shadow: 0 0 5px var(--color-neon-green);
            font-family: 'Roboto Mono', monospace; 
            background: rgba(0, 0, 0, 0.5);
            padding: 4px 10px;
            border-radius: 5px;
            border: 1px solid var(--color-neon-green);
        }

        .status-led {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 8px;
            animation: status-blink 1s infinite;
        }

        .logo {
            text-align: center;
            margin-top: 25px;
            margin-bottom: 35px;
        }

        .logo p {
            color: var(--color-neon-blue);
            font-size: 1rem;
            font-family: 'Roboto Mono', monospace;
            font-weight: 400;
            text-shadow: 0 0 5px var(--color-neon-blue);
        }

        .device-code-box {
            background: rgba(0, 0, 0, 0.5);
            border: 2px solid;
            border-radius: 12px;
            padding: 20px;
            cursor: pointer;
            position: relative;
            margin-bottom: 25px;
            box-shadow: 0 0 10px rgba(0, 191, 255, 0.3);
        }

        .device-code-box h3 {
            color: var(--color-neon-blue);
            margin-bottom: 10px;
            font-size: 1rem;
            font-weight: 700;
            text-shadow: 0 0 5px var(--color-neon-blue);
        }

        .device-code {
            font-size: 1.6rem;
            font-weight: 900;
            letter-spacing: 4px;
            font-family: 'Roboto Mono', monospace;
            color: var(--color-cyber-pink);
            text-shadow: 0 0 10px var(--color-cyber-pink);
        }
        
        .ip-tracker {
            color: var(--color-neon-green);
            font-size: 0.8rem;
            margin-top: 10px;
            font-family: 'Roboto Mono', monospace;
            text-shadow: 0 0 3px var(--color-neon-green);
        }

        .device-note {
            color: #999;
            font-size: 0.75rem;
            margin-top: 5px;
            font-family: 'Arial', sans-serif;
        }

        .copy-notification {
            position: absolute;
            top: 50%; 
            left: 50%;
            transform: translate(-50%, -50%);
            background: var(--color-premium-yellow); 
            color: var(--color-background-dark);
            font-weight: 700;
            padding: 8px 15px; 
            border-radius: 6px; 
            font-size: 0.9rem; 
            opacity: 0;
            transition: all 0.4s ease; 
            z-index: 100; 
            pointer-events: none; 
        }

        .copy-notification.show {
            opacity: 1;
            transform: translate(-50%, -100%);
        }

        .form-group input {
            padding: 15px;
            border: 2px solid; 
            border-radius: 10px;
            background: rgba(0, 0, 0, 0.7);
            font-size: 1.1rem;
            letter-spacing: 1px;
            font-family: 'Roboto Mono', monospace;
            animation: neon-glow-pulse 9s linear infinite; /* LAGG FIX: Slower */
            width: 100%;
        }

        .form-group input:focus {
            animation: none;
            border-color: var(--color-premium-yellow);
            box-shadow: 0 0 20px var(--color-premium-yellow);
        }

        .login-btn {
            border: none;
            color: white;
            padding: 16px;
            border-radius: 10px;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            text-shadow: 0 0 8px rgba(255, 255, 255, 0.8);
            margin-bottom: 30px; 
        }

        .login-btn:hover {
             transform: translateY(-2px);
        }
        
        .error-message {
            background: rgba(255, 69, 0, 0.3);
            color: var(--color-error-red);
            border: 2px solid var(--color-error-red);
            box-shadow: 0 0 10px rgba(255, 69, 0, 0.8);
            padding: 15px;
            border-radius: 10px;
            font-size: 0.95rem;
            margin-bottom: 20px;
        }
        
        .get-password {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .telegram-section {
            background: rgba(0, 0, 0, 0.6);
            border: 1px solid var(--color-neon-blue);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 191, 255, 0.5);
            margin-top: 30px;
            padding-bottom: 30px;
        }

        .telegram-section h4 {
            color: var(--color-text-light);
            font-size: 1rem;
            font-family: 'Roboto Mono', monospace;
            text-shadow: 0 0 5px var(--color-text-light);
            margin-bottom: 15px;
        }

        .telegram-btn {
            display: inline-block;
            background: var(--color-neon-blue);
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: bold;
            box-shadow: 0 0 15px rgba(0, 191, 255, 0.6);
        }

        .telegram-btn:hover {
            background: var(--color-premium-yellow);
            color: var(--color-background-dark);
            box-shadow: 0 0 25px var(--color-premium-yellow);
        }

        .credit-footer {
            position: relative; 
            width: 100%;
            text-align: center;
            color: #AAA;
            font-size: 0.8rem;
            padding-top: 15px;
            z-index: 3;
            font-family: 'Arial', sans-serif;
        }

        .credit-footer a {
            color: var(--color-premium-yellow);
            text-shadow: 0 0 8px var(--color-premium-yellow);
        }
    </style>
</head>
<body>
    <div class="login-page">
        <div class="login-container">
            <div class="system-status">
                <div class="status-led"></div>
                SYSTEM STATUS: ONLINE
            </div>

            <div class="logo">
                <h1>TRX MARUF VIP</h1>
                <p>[ AUTHENTICATION REQUIRED ]</p>
            </div>
            
            <?php if (!empty($error_message)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <div class="device-code-box" id="deviceCodeBox">
                <h3>DEVICE IDENTIFICATION KEY</h3>
                <div class="device-code" id="deviceCode">TRXF7E1E3DE38E</div>
                <div class="ip-tracker">
                    &gt; NETWORK: IP-192.168.1.1 &gt; REGION: ASI-BD
                </div>
                <p class="device-note">Click ID to copy and transmit to Admin for key generation.</p>
                <div class="copy-notification" id="copyNotification">ID COPIED TO CLIPBOARD!</div>
            </div>
            
            <form class="login-form" method="POST" action="index.php">
                <div class="form-group">
                    <label for="activation_code">ENTER ACTIVATION KEY:</label>
                    <input type="text" id="activation_code" name="activation_code" required placeholder="Paste your VIP key here...">
                </div>
                <button type="submit" class="login-btn">
                    <span style="margin-right: 5px;">&#x1F512;</span> AUTHORIZE & DECRYPT
                </button>
            </form>

            <div class="get-password">
                <a href="https://t.me/OWNER_MARUF_TOP" target="_blank" class="key-btn">
                    <span style="margin-right: 5px;">&#x1F4F1;</span> REQUEST ACTIVATION KEY
                </a>
            </div>
            
            <div class="telegram-section">
                <h4>OFFICIAL COMMAND CHANNEL</h4>
                <a href="https://t.me/MENTOR_MARUF_VAI" target="_blank" class="telegram-btn">
                    <span style="margin-right: 5px;">&#x2708;</span> CONNECT TELEGRAM
                </a>
            </div>
            
            <div class="credit-footer">
                SYSTEM DESIGN & INTEGRATION: <a href="https://t.me/OWNER_MARUF_TOP" target="_blank">@OWNER_MARUF_TOP</a>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deviceCodeBox = document.getElementById('deviceCodeBox');
            if (deviceCodeBox) {
                deviceCodeBox.addEventListener('click', function() {
                    const deviceCode = document.getElementById('deviceCode').textContent;
                    const copyNotification = document.getElementById('copyNotification');
                    
                    // Use modern clipboard API
                    navigator.clipboard.writeText(deviceCode).then(() => {
                        copyNotification.classList.add('show');
                        setTimeout(() => { copyNotification.classList.remove('show'); }, 2000);
                    }).catch(err => {
                        console.error('Could not copy text: ', err);
                        // Fallback for older browsers
                        alert("Failed to copy. Please manually copy the ID: " + deviceCode);
                    });
                });
            }
        });
    </script>
</body>
</html>