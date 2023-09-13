<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HTML Element Counter</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>HTML Element Counter</h1>
    <form id="urlForm">
        <label for="url">URL:</label>
        <input type="url" id="url" name="url" required placeholder="http://example.com">
        <label for="element">Element:</label>
        <input type="text" id="element" name="element" required placeholder="img, div, a...">
        <button type="submit">Check</button>
    </form>
    <div id="responseArea"></div>

    <script src="script.js"></script>
</body>
</html>
