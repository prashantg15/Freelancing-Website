<?php 


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <input type="file" id="image" name="image" onchange="loadfile(event)">
    <img id="preimage" width="220px" height="120px">
    <script type="text/javascript">
        function loadfile(event){
            var output = document.getElementById('preimage');
            output.src = URL.createObjectURL(event.target.files[0]);
        };
    </script>
</body>
</html>