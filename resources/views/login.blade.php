<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Login · Chit-Chat Café</title>
    <style>
        /* ---------- RESET ---------- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* light mode */
        :root {
            --bg-page: #f0f7ee;
            --card-bg: #ffffff;
            --text-primary: #1e2a1c;
            --text-secondary: #3a5a34;
            --text-muted: #6b7c68;
            --border-light: #ddebe0;
            --input-bg: #ffffff;
            --input-border: #cbdcd0;
            --input-focus: #6fbf4c;
            --accent-green: #4c9f2f;
            --accent-green-dark: #3b7e24;
            --accent-hover: #e9f5e3;
            --link-color: #4c9f2f;
            --shadow-sm: 0 12px 30px rgba(0, 0, 0, 0.05), 0 4px 8px rgba(0, 0, 0, 0.02);
            --transition: all 0.25s ease;
        }

        /* dark mode */
        body.dark {
            --bg-page: #121212;
            --card-bg: #1e2a1c;
            --text-primary: #eef5ea;
            --text-secondary: #cfe3c7;
            --text-muted: #a1b89a;
            --border-light: #2c3e28;
            --input-bg: #2a3a26;
            --input-border: #415e3a;
            --input-focus: #7ed957;
            --accent-green: #7ed957;
            --accent-green-dark: #5fb03e;
            --accent-hover: #2c3e28;
            --link-color: #8cd96c;
            --shadow-sm: 0 12px 30px rgba(0, 0, 0, 0.4);
        }

        body {
            background-color: var(--bg-page);
            font-family: 'Segoe UI', 'Poppins', system-ui, -apple-system, 'Inter', 'Noto Sans', sans-serif;
            transition: background-color 0.3s ease, color 0.2s ease;
            color: var(--text-primary);
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 20px;
            position: relative;
        }

        /* dark mode button */
        .theme-toggle {
            position: fixed;
            top: 24px;
            right: 24px;
            background: var(--card-bg);
            border: 1px solid var(--border-light);
            border-radius: 60px;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.6rem;
            backdrop-filter: blur(4px);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            z-index: 999;
            background-color: var(--card-bg);
            color: var(--text-primary);
        }

        .theme-toggle:hover {
            transform: scale(1.05);
            background-color: var(--accent-hover);
            border-color: var(--accent-green);
        }


        .login-card {
            max-width: 500px;
            width: 100%;
            background-color: var(--card-bg);
            border-radius: 40px;
            box-shadow: var(--shadow-sm);
            padding: 40px 36px 48px;
            transition: background-color 0.3s ease, box-shadow 0.3s;
            border: 1px solid var(--border-light);
        }


        .brand {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand h1 {
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, var(--accent-green) 0%, #7ac74f 100%);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            margin-bottom: 8px;
        }

        .slogan {
            font-size: 1rem;
            color: var(--text-secondary);
            border-top: 1px solid var(--border-light);
            display: inline-block;
            padding-top: 10px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 28px;
        }

        .welcome-text p {
            font-size: 1rem;
            color: var(--text-muted);
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .input-group label {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-secondary);
            letter-spacing: 0.3px;
        }

        .input-group input {
            background-color: var(--input-bg);
            border: 1.5px solid var(--input-border);
            border-radius: 24px;
            padding: 14px 18px;
            font-size: 1rem;
            color: var(--text-primary);
            transition: var(--transition);
            outline: none;
            font-weight: 500;
        }

        .input-group input:focus {
            border-color: var(--input-focus);
            box-shadow: 0 0 0 3px rgba(108, 191, 76, 0.2);
        }

        .form-aux {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin: 4px 0 8px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            color: var(--text-muted);
            cursor: pointer;
        }

        .checkbox-group input {
            width: 18px;
            height: 18px;
            accent-color: var(--accent-green);
            margin: 0;
            cursor: pointer;
        }

        .forgot-link {
            font-size: 0.85rem;
            color: var(--link-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .forgot-link:hover {
            text-decoration: underline;
            color: var(--accent-green-dark);
        }

        .login-btn {
            background-color: var(--accent-green);
            border: none;
            border-radius: 40px;
            padding: 14px 20px;
            font-size: 1.05rem;
            font-weight: 700;
            color: white;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 6px;
            letter-spacing: 0.8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .login-btn:hover {
            background-color: var(--accent-green-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(76, 159, 47, 0.25);
        }

        .register-area {
            text-align: center;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid var(--border-light);
        }

        .register-text {
            font-size: 0.95rem;
            color: var(--text-secondary);
        }

        .register-link {
            color: var(--link-color);
            font-weight: 700;
            text-decoration: none;
            margin-left: 6px;
            transition: var(--transition);
            border-bottom: 1px dashed transparent;
        }

        .register-link:hover {
            border-bottom-color: var(--link-color);
            color: var(--accent-green-dark);
        }


        @media (max-width: 550px) {
            .login-card {
                padding: 32px 24px 40px;
            }
            .brand h1 {
                font-size: 2rem;
            }
            .theme-toggle {
                top: 16px;
                right: 16px;
                width: 42px;
                height: 42px;
                font-size: 1.4rem;
            }
            .login-btn {
                padding: 12px;
            }
        }

        input:-webkit-autofill,
        input:-webkit-autofill:focus {
            transition: background-color 600000s 0s, color 600000s 0s;
        }

        .login-card {
            animation: fadeSlideUp 0.5s ease-out;
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <!-- dark mode toggle button (🌞/🌙) -->
    <button class="theme-toggle" id="themeToggleBtn" aria-label="切換深淺主題">
        🌞
    </button>

    <div class="login-card">
        <div class="brand">
            <h1>Chit-Chat Cafe</h1>
            <hr>
            <div class="slogan"><h3>Your home for games and gatherings</h3></div>
        </div>
        

        <div class="welcome-text">
            <p>Welcome back · Sign in to your account</p>
        </div>

        <form class="login-form" id="loginForm" method="POST" action="{{ route('login.verify') }}">
            @csrf
            
            @if($errors->any())
            <div style="background:#fee9e6; color:#e74c3c; padding:14px 18px; border-radius:20px; text-align:center; font-weight:600;">
                {{ $errors->first() }}
            </div>
            @endif

            <!-- Email -->
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required autocomplete="email">
            </div>

            <!-- password -->
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>

            <!-- remember me + forgot password -->
            <div class="form-aux">
                <label class="checkbox-group">
                    <input type="checkbox" name="remember" id="remember">
                    <span>Remember me</span>
                </label>
                <!-- forgot password 按作業要求可無需具備功能 -->
                <a href="#" class="forgot-link" id="forgotPwdLink">Forgot password?</a>
            </div>

            <!-- login button -->
            <button type="submit" class="login-btn">Login</button>

            <div class="register-area">
                <div class="register-text">
                    First time in here?
                    <!-- 這裡的 route('register') 需要在 web.php 定義對應的註冊頁面路由，或者直接寫成 /register -->
                    <a href="{{ route('register') }}" class="register-link" id="registerLink">Register now!</a>
                </div>
            </div>

        </form>
    </div>

    <script>
        // ---------- dark mode / light mode toggle + local storage ----------
        const themeToggleBtn = document.getElementById('themeToggleBtn');
        const htmlBody = document.body;

        const getStoredTheme = () => localStorage.getItem('theme');
        const setStoredTheme = (theme) => localStorage.setItem('theme', theme);

        const applyTheme = (theme) => {
            if (theme === 'dark') {
                htmlBody.classList.add('dark');
                themeToggleBtn.innerHTML = '🌙';  // dark mode
            } else {
                htmlBody.classList.remove('dark');
                themeToggleBtn.innerHTML = '🌞';  // light mode
            }
        };

        const initTheme = () => {
            const storedTheme = getStoredTheme();
      
            if (storedTheme === 'dark') {
                applyTheme('dark');
            } else if (storedTheme === 'light') {
                applyTheme('light');
            } else {

                applyTheme('light');
                setStoredTheme('light');
            }
        };


        const toggleTheme = () => {
            const isDark = htmlBody.classList.contains('dark');
            if (isDark) {
                applyTheme('light');
                setStoredTheme('light');
            } else {
                applyTheme('dark');
                setStoredTheme('dark');
            }
        };

        themeToggleBtn.addEventListener('click', toggleTheme);
        initTheme();
    </script>
</body>
</html>