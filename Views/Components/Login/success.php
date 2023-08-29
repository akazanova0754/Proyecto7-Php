<h2>Hola <?=$data?> Bienvenido al sistema</h2>
<h2>Redireccionando <div id="contador"></div></h2>
<script>
    cont(10);
    function cont($num){
        document.getElementById('contador').innerHTML=$num;
        if($num!=0){
            setTimeout(function(){cont($num-1)},1000);
        }
    }
</script>
