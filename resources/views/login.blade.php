<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Login · Chit-Chat Café</title>
    @vite(['resources/css/app.css', 'resources/css/login.css'])
</head>
<body class="login-page app-page">
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