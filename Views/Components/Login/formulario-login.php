<script src="https://www.google.com/recaptcha/api.js?render=<?=$data['key_google']?>"></script>

<h2><?=$data['key_form']?></h2>
    <table>
    <form action="<?=URL_COMPLETA?>/Login/validar" method='POST'>
        <tbody>
        <tr>
            <td colspan="2"><h1>Login</h1></td>
        </tr>
        <tr>
            <td><label for="user">Usuario</label></td>
            <td><input name="user" type="text"></td>
        </tr>
        <tr>
            <td><label for="pass">Contraseña</label></td>
            <td><input  name="pass" type="text"></td>
        </tr>
        
        <tr>
            <td colspan='2'><input name='coin' type="hidden" value='<?=$data['key_form']?>'></td>
        </tr>
        <tr>
            <td colspan='2'><input name='<?=$data['name_key_google']?>' id="<?=$data['name_key_google']?>" type="hidden"></td>
        </tr>
        <tr>
            <td  colspan="2"><input type="submit" name="myform" value="Enviar"></td>
        </tr>
        </tbody>
    </form>
    </table>
   
<script>
    
    grecaptcha.ready(function() {
        grecaptcha.execute('<?=$data['key_google']?>', {action: 'homepage'}).then(function(token) {
            // Add your logic to submit to your backend server here.
            document.getElementById('<?=$data['name_key_google']?>').value=token;

        });
    });
    
</script>