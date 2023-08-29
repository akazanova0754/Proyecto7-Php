<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=(!empty($title))?$title:"Login";?></title>
    <?=$this->requirements?>
</head>
<body>
    <?php 
    
    if(!empty($sub_archivo)) {
        require($sub_archivo);
    }
    ?>    

</body>
</html>