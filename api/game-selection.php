<?php
include 'config.php'; // config ফাইল লোড করা

// স্টার্ট সেশন যদি আগে থেকে স্টার্ট না হয়ে থাকে
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// যদি ইউজার লগইন করা না থাকে, তাকে লগইন পেজে ফেরত পাঠানো হবে
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit;
}

// লগ আউট বাটন চাপলে সেশন বন্ধ করা
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Granted - System Interface</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap');

        /* --- লগইন পেজের কালার প্যালেট --- */
        :root {
            --color-neon-blue: #00BFFF;
            --color-cyber-pink: #FF00FF;
            --color-premium-yellow: #FFFF00; 
            --color-neon-green: #00FF7F; 
            --color-error-red: #FF4500;
            --color-text-light: #E0E0E0;
            --color-background-dark: #0a0a0f;
        }

        /* --- 1. কোর NEON GLOW অ্যানিমেশন (লগইন পেজের মতো) --- */
        @keyframes neon-glow-pulse {
            0% {
                border-color: var(--color-neon-blue);
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
            100% {
                border-color: var(--color-neon-blue);
                box-shadow: 0 0 10px rgba(0, 191, 255, 0.5), inset 0 0 5px rgba(0, 191, 255, 0.3);
            }
        }
        
        /* --- 2. বাটন গ্র্যাডিয়েন্ট অ্যানিমেশন (লগইন পেজের মতো) --- */
        @keyframes neon-button-shift {
            0% { background: linear-gradient(135deg, var(--color-neon-blue), var(--color-cyber-pink)); }
            33% { background: linear-gradient(135deg, var(--color-cyber-pink), var(--color-premium-yellow)); }
            66% { background: linear-gradient(135deg, var(--color-premium-yellow), var(--color-neon-green)); }
            100% { background: linear-gradient(135deg, var(--color-neon-green), var(--color-neon-blue)); }
        }

        /* --- 3. স্থিতি LED অ্যানিমেশন (লগইন পেজের মতো) --- */
        @keyframes status-blink {
            0%, 100% { background-color: var(--color-neon-green); box-shadow: 0 0 5px var(--color-neon-green), 0 0 15px var(--color-neon-green); }
            50% { background-color: rgba(0, 255, 127, 0.3); box-shadow: none; }
        }

        /* --- বেসিক স্টাইলস --- */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Orbitron', sans-serif;
            color: var(--color-text-light);
        }

        .game-selection-page {
            background: var(--color-background-dark);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 15px;
            position: relative;
            padding-bottom: 70px;
        }
        /* ব্যাকগ্রাউন্ড গ্রিড এফেক্ট যোগ করা */
        .game-selection-page::before {
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


        .game-selection-container {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            border: 2px solid; 
            padding: 30px 25px;
            border-radius: 12px;
            width: 100%;
            max-width: 450px;
            text-align: center;
            position: relative;
            z-index: 2;
            /* লগইন পেজের অ্যানিমেশন প্রয়োগ */
            animation: neon-glow-pulse 10s linear infinite;
        }

        /* --- নতুন: সিস্টেম স্ট্যাটাস লগ উইজেট --- */
        .status-log-widget {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid var(--color-neon-green);
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 25px;
            text-align: left;
            font-family: 'Roboto Mono', monospace;
            box-shadow: 0 0 5px var(--color-neon-green);
        }

        .status-log-widget p {
            font-size: 0.75rem;
            line-height: 1.5;
            color: var(--color-neon-green);
            overflow: hidden;
            white-space: nowrap;
            margin: 0;
        }

        .status-log-widget .log-entry {
            color: var(--color-text-light);
        }
        
        .status-log-widget .log-entry-highlight {
            color: var(--color-premium-yellow);
        }


        /* --- হেডার এবং তথ্য --- */
        .access-badge {
            display: inline-block;
            background: var(--color-neon-green);
            color: #000;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
            margin-bottom: 15px;
            text-shadow: 0 0 5px rgba(255, 255, 255, 0.7);
        }

        .game-selection-title {
            font-size: 1.8rem;
            margin-bottom: 8px;
            letter-spacing: 1px;
            color: var(--color-cyber-pink); /* পিঙ্ক কালার টোন */
            text-shadow: 0 0 10px var(--color-cyber-pink);
        }

        .game-selection-subtitle {
            color: var(--color-neon-blue);
            margin-bottom: 25px;
            font-size: 0.9rem;
            font-family: 'Roboto Mono', monospace;
            text-shadow: 0 0 5px var(--color-neon-blue);
        }

        .user-info {
            background: rgba(0, 0, 0, 0.3);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            border: 1px solid var(--color-neon-blue); /* ব্লু বর্ডার */
            text-align: left;
            box-shadow: 0 0 10px rgba(0, 191, 255, 0.3);
            font-family: 'Roboto Mono', monospace;
        }

        .user-info p {
            color: var(--color-text-light);
            margin: 8px 0;
            font-size: 0.9rem;
        }

        .user-code {
            font-weight: bold;
            user-select: all;
            color: var(--color-premium-yellow); /* ইয়েলো কোড হাইলাইট */
            text-shadow: 0 0 5px var(--color-premium-yellow);
        }

        .expiry-date {
            font-weight: bold;
            color: var(--color-neon-green); /* গ্রিন ডেট */
        }

        .session-warning {
            border: 1px solid var(--color-cyber-pink); /* পিঙ্ক বর্ডার */
            color: var(--color-cyber-pink);
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 25px;
            font-size: 0.8rem;
            font-family: 'Arial', sans-serif;
            font-weight: bold;
            text-align: center;
            box-shadow: 0 0 10px rgba(255, 0, 255, 0.4);
        }

        /* --- বাটন সেকশন --- */
        .game-buttons {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 20px;
        }

        .game-btn {
            color: #FFFFFF;
            border: none;
            padding: 15px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            text-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
            /* লগইন পেজের অ্যানিমেশন প্রয়োগ */
            animation: neon-button-shift 6s linear infinite;
        }

        .game-btn:hover {
            transform: translateY(-3px) scale(1.03);
            animation-play-state: paused;
            box-shadow: 0 0 25px var(--color-cyber-pink), 0 0 10px var(--color-neon-blue);
        }

        /* --- লগ আউট বাটন --- */
        .logout-btn {
            display: block;
            background: transparent;
            border: 2px solid var(--color-error-red);
            color: var(--color-error-red);
            padding: 10px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: bold;
            margin-top: 25px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .logout-btn:hover {
            background: var(--color-error-red);
            color: white;
            box-shadow: 0 0 15px var(--color-error-red);
        }

        /* --- ফুটার --- */
        .credit-footer {
            position: relative; 
            width: 100%;
            text-align: center;
            color: #AAA;
            font-size: 0.8rem;
            padding: 20px 0 0;
            z-index: 3;
            font-family: 'Arial', sans-serif;
        }

        .credit-footer a {
            text-decoration: none;
            font-weight: bold;
            color: var(--color-premium-yellow);
            text-shadow: 0 0 8px var(--color-premium-yellow);
            animation: neon-glow-pulse 8s linear infinite reverse;
        }
        .credit-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="game-selection-page">
        <div class="game-selection-container">
            
            <div class="status-log-widget">
                <p>&gt; SYSTEM CHECK: <span class="log-entry">INITIATING...</span></p>
                <p>&gt; AUTH MODULE: <span class="log-entry-highlight">ONLINE</span></p>
                <p>&gt; IP TRACE: <span class="log-entry">203.1.200.77</span></p>
                <p>&gt; ACCESS: <span class="log-entry-highlight">LEVEL 3/A</span></p>
            </div>
            
            <span class="access-badge">✔ ACCESS GRANTED</span>
            <h1 class="game-selection-title">SELECT PLATFORM</h1>
            <p class="game-selection-subtitle">INITIALIZE COMMAND SEQUENCE.</p>

            <div class="user-info">
                <p>DEVICE ID: <span class="user-code">TRXEE1E3DE38E</span></p>
                <p>SESSION TOKEN: <span class="user-code">5D4B...8C9F</span></p>
                <p>ACCESS END: <span class="expiry-date">2025-11-12 10:20:15</span></p>
            </div>

            <div class="session-warning">
                WARNING! This is a single-use access token. Re-authentication will be required.
            </div>

            <div class="game-buttons">
                <a href="game.php" class="game-btn">
                    <span style="margin-right: 5px;">🔥</span> DKWIN PLATFORM 
                </a>
                </div>
            
            <a href="?logout=true" class="logout-btn">
                END SESSION [ LOGOUT ]
            </a>

            <div class="credit-footer">
                System Interface by <a href="https://t.me/OWNER_MARUF_TOP" target="_blank">@OWNER_MARUF_TOP</a>
            </div>
        </div>
    </div>
</body>
</html>