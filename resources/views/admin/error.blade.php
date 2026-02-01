<!DOCTYPE html>
<html>
<head>
    <title>Admin Error</title>
    <style>
        body { font-family: monospace; background:#111; color:#f55; padding:20px }
        pre { background:#000; padding:15px }
    </style>
</head>
<body>
    <h1>Admin Dashboard Error</h1>
    <pre>{{ $error }}</pre>
    <p>{{ $file }} : {{ $line }}</p>
</body>
</html>
