<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Preview | BrowserShot Test</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .glass-card {
            max-width: 1200px;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        /* Header Section */
        .header {
            background: linear-gradient(120deg, #fdfbfb 0%, #ebedee 100%);
            padding: 30px 40px;
            border-bottom: 3px solid rgba(102, 126, 234, 0.1);
        }

        .header h1 {
            font-size: 2.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .badge-container {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .badge {
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .badge-primary { background: #667eea; color: white; }
        .badge-success { background: #10b981; color: white; }
        .badge-warning { background: #f59e0b; color: white; }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            padding: 40px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 30px;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(102, 126, 234, 0.1);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -15px rgba(102, 126, 234, 0.3);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            display: inline-block;
            animation: rotate 10s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .stat-value {
            font-size: 2.2rem;
            font-weight: bold;
            color: #333;
        }

        .stat-label {
            color: #666;
            font-size: 0.95rem;
            margin-top: 5px;
        }

        /* Data Table */
        .table-section {
            padding: 0 40px 40px 40px;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
        }

        .section-title h2 {
            color: #333;
            font-size: 1.8rem;
        }

        .live-indicator {
            width: 12px;
            height: 12px;
            background: #10b981;
            border-radius: 50%;
            animation: blink 1.5s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 20px -5px rgba(0, 0, 0, 0.1);
        }

        .data-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            padding: 18px 20px;
            text-align: left;
        }

        .data-table td {
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
            color: #555;
        }

        .data-table tr:hover {
            background: #f8f9ff;
        }

        .status-badge {
            padding: 6px 15px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-active { background: #d1fae5; color: #065f46; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-inactive { background: #fee2e2; color: #991b1b; }

        /* Interactive Section */
        .interactive-section {
            background: linear-gradient(120deg, #fdfbfb 0%, #ebedee 100%);
            padding: 40px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .counter-box {
            background: white;
            padding: 30px;
            border-radius: 30px;
            text-align: center;
        }

        .counter {
            font-size: 3.5rem;
            font-weight: bold;
            color: #667eea;
            margin: 15px 0;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin: 5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px #667eea;
        }

        .progress-box {
            background: white;
            padding: 30px;
            border-radius: 30px;
        }

        .progress-bar {
            width: 100%;
            height: 12px;
            background: #f0f0f0;
            border-radius: 50px;
            margin: 20px 0;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 50px;
            transition: width 0.5s ease;
        }

        /* Footer */
        .footer {
            padding: 30px 40px;
            text-align: center;
            color: #666;
            border-top: 1px solid rgba(102, 126, 234, 0.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header h1 { font-size: 2rem; }
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="glass-card">
        <!-- Header -->
        <div class="header">
            <h1>✨ Dashboard Overview</h1>
            <p style="color: #666; font-size: 1.1rem;">BrowserShot Test Page • Dummy Data for Screenshot Testing</p>
            <div class="badge-container">
                <span class="badge badge-primary">🟢 Live Preview</span>
                <span class="badge badge-success" id="activeUsers">242 Active Users</span>
                <span class="badge badge-warning">🚀 Beta v2.0</span>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-icon">👥</span>
                <div class="stat-value" id="totalUsers">12,847</div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <span class="stat-icon">💰</span>
                <div class="stat-value">$47.2K</div>
                <div class="stat-label">Revenue (MTD)</div>
            </div>
            <div class="stat-card">
                <span class="stat-icon">📊</span>
                <div class="stat-value">89.3%</div>
                <div class="stat-label">Engagement Rate</div>
            </div>
            <div class="stat-card">
                <span class="stat-icon">⚡</span>
                <div class="stat-value">2.4s</div>
                <div class="stat-label">Avg Response Time</div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="table-section">
            <div class="section-title">
                <h2>Recent Activities</h2>
                <span class="live-indicator"></span>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Status</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>John Doe</td>
                        <td>Updated profile settings</td>
                        <td><span class="status-badge status-active">Completed</span></td>
                        <td>2 min ago</td>
                    </tr>
                    <tr>
                        <td>Sarah Smith</td>
                        <td>New subscription</td>
                        <td><span class="status-badge status-active">Completed</span></td>
                        <td>15 min ago</td>
                    </tr>
                    <tr>
                        <td>Mike Johnson</td>
                        <td>Payment processing</td>
                        <td><span class="status-badge status-pending">Pending</span></td>
                        <td>32 min ago</td>
                    </tr>
                    <tr>
                        <td>Emily Brown</td>
                        <td>Account deactivation</td>
                        <td><span class="status-badge status-inactive">Inactive</span></td>
                        <td>1 hour ago</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Interactive Section -->
        <div class="interactive-section">
            <div class="counter-box">
                <h3 style="color: #333;">Interactive Counter</h3>
                <div class="counter" id="counter">42</div>
                <button class="btn btn-primary" onclick="incrementCounter()">Click Me!</button>
                <button class="btn btn-primary" onclick="resetCounter()">Reset</button>
            </div>

            <div class="progress-box">
                <h3 style="color: #333;">System Health</h3>
                <div style="margin: 20px 0;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span>CPU Usage</span>
                        <span id="cpuValue">65%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="cpuProgress" style="width: 65%"></div>
                    </div>
                </div>
                <div style="margin: 20px 0;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span>Memory</span>
                        <span id="memoryValue">42%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="memoryProgress" style="width: 42%"></div>
                    </div>
                </div>
                <button class="btn btn-primary" onclick="refreshMetrics()">⟳ Refresh Metrics</button>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>BrowserShot Test • Dummy Data for Screenshot Testing • Updated in real-time</p>
            <p style="font-size: 0.85rem; margin-top: 10px;">Last generated: <span id="timestamp"></span></p>
        </div>
    </div>

    <script>
        // Update timestamp
        function updateTimestamp() {
            const now = new Date();
            document.getElementById('timestamp').textContent = now.toLocaleString();
        }
        updateTimestamp();

        // Counter functionality
        function incrementCounter() {
            const counter = document.getElementById('counter');
            counter.textContent = parseInt(counter.textContent) + 1;
        }

        function resetCounter() {
            document.getElementById('counter').textContent = '42';
        }

        // Refresh metrics
        function refreshMetrics() {
            // Random CPU between 20-90%
            const cpu = Math.floor(Math.random() * 70) + 20;
            document.getElementById('cpuValue').textContent = cpu + '%';
            document.getElementById('cpuProgress').style.width = cpu + '%';
            
            // Random Memory between 30-80%
            const memory = Math.floor(Math.random() * 50) + 30;
            document.getElementById('memoryValue').textContent = memory + '%';
            document.getElementById('memoryProgress').style.width = memory + '%';

            // Update active users
            const users = Math.floor(Math.random() * 300) + 100;
            document.getElementById('activeUsers').textContent = users + ' Active Users';

            // Animate the refresh button
            const btn = event.target;
            btn.style.transform = 'rotate(360deg)';
            setTimeout(() => {
                btn.style.transform = 'rotate(0deg)';
            }, 500);
        }

        // Auto-refresh metrics every 10 seconds
        setInterval(() => {
            // Only refresh if not called manually
            const cpu = Math.floor(Math.random() * 70) + 20;
            document.getElementById('cpuValue').textContent = cpu + '%';
            document.getElementById('cpuProgress').style.width = cpu + '%';
            
            const memory = Math.floor(Math.random() * 50) + 30;
            document.getElementById('memoryValue').textContent = memory + '%';
            document.getElementById('memoryProgress').style.width = memory + '%';

            // Update timestamp
            updateTimestamp();
        }, 10000);

        // Random table row highlighting effect
        setInterval(() => {
            const rows = document.querySelectorAll('.data-table tbody tr');
            rows.forEach(row => {
                row.style.backgroundColor = '#ffffff';
            });
            const randomRow = Math.floor(Math.random() * rows.length);
            if (rows[randomRow]) {
                rows[randomRow].style.backgroundColor = '#fff3e0';
                setTimeout(() => {
                    if (rows[randomRow]) {
                        rows[randomRow].style.backgroundColor = '';
                    }
                }, 1000);
            }
        }, 3000);

        // Console log for debugging
        console.log('BrowserShot test page loaded successfully!');
        console.log('Timestamp:', new Date().toLocaleString());
    </script>
</body>
</html>