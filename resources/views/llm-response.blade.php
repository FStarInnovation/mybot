<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyBot LLM Response</title>
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
            max-width: 800px;
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
        .card {
            margin-bottom: 20px;
            border: 1px solid #eee;
            border-radius: 5px;
            padding: 15px;
        }
        .query-card {
            background-color: #e3f2fd;
            border-left: 4px solid #3490dc;
        }
        .response-card {
            background-color: #f1f8e9;
            border-left: 4px solid #4caf50;
        }
        .card-header {
            font-weight: bold;
            margin-bottom: 10px;
            color: #555;
        }
        .card-content {
            white-space: pre-line;
        }
        .actions {
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3490dc;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 10px;
        }
        .btn:hover {
            background-color: #2779bd;
        }
        .links {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>MyBot LLM Response</h1>
        
        <div class="card query-card">
            <div class="card-header">Your Query:</div>
            <div class="card-content">{{ $query }}</div>
        </div>
        
        <div class="card response-card">
            <div class="card-header">Response:</div>
            <div class="card-content">{{ $response }}</div>
        </div>
        
        <div class="actions">
            <a href="{{ url('/llm/form') }}" class="btn">Ask Another Question</a>
        </div>
        
        <div class="links">
            <p><a href="{{ url('/') }}">Back to Home</a> | <a href="{{ url('/admin/dashboard') }}">Admin Dashboard</a></p>
        </div>
    </div>
</body>
</html>
