<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            color: #333;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h1 {
            color: #3490dc;
            margin-bottom: 20px;
        }
        .info-box {
            background-color: #e3f2fd;
            border-left: 4px solid #3490dc;
            padding: 15px;
            margin-bottom: 20px;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        .card {
            flex: 1 0 300px;
            margin: 10px;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            background-color: white;
        }
        .card h3 {
            margin-top: 0;
            color: #3490dc;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        
        <div class="info-box">
            <p>Welcome to your admin dashboard. This is a simple interface for managing your application.</p>
        </div>
        
        <div class="row">
            <div class="card">
                <h3>LLM Interface</h3>
                <p>Configure and test your LLM integration here.</p>
                <p><a href="{{ url('/llm/form') }}">Go to LLM Interface</a></p>
            </div>

            <div class="card">
                <h3>Telescope</h3>
                <p>View application metrics and debugging information.</p>
                <p><a href="{{ url('/telescope') }}">Launch Telescope</a></p>
            </div>
        </div>
        
        <div class="row">
            <div class="card">
                <h3>System Status</h3>
                <p>PHP Version: {{ phpversion() }}</p>
                <p>Laravel Version: {{ app()->version() }}</p>
                <p>Environment: {{ app()->environment() }}</p>
            </div>
            
            <div class="card">
                <h3>Tasks</h3>
                <p>No pending tasks.</p>
            </div>
        </div>
    </div>
</body>
</html>
