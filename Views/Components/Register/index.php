<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=(!empty($title))?$title:"Register";?></title>
    <?=$this->requirements?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>

    <?php 
 
        if(!empty($sub_archivo)) {
            require_once($sub_archivo);
        }
    ?>    

</body>
<script>
        cont(5);
        function cont($num){
            document.getElementById('contador').innerHTML=$num;
            if($num!=0){
                setTimeout(function(){cont($num-1)},1000);
            }
        }
</script>
</html>