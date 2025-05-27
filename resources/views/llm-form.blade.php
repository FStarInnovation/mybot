<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyBot LLM Interface</title>
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
        .info-box {
            background-color: #e3f2fd;
            border-left: 4px solid #3490dc;
            padding: 15px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            min-height: 100px;
        }
        button {
            background-color: #3490dc;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
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
        <h1>MyBot LLM Interface</h1>
        
        <div class="info-box">
            <p>Use this interface to interact with the LLM model. Enter your query below and submit to get a response.</p>
        </div>
        
        <form action="{{ url('/llm/query') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="query">Your Question or Prompt:</label>
                <textarea name="query" id="query" required placeholder="Enter your question or prompt here..."></textarea>
            </div>
            
            <button type="submit">Submit Query</button>
        </form>
        
        <div class="links">
            <p><a href="{{ url('/') }}">Back to Home</a> | <a href="{{ url('/admin/dashboard') }}">Admin Dashboard</a></p>
        </div>
    </div>
</body>
</html>
