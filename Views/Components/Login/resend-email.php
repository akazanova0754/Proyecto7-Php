
<style>
    table{
        text-align:center;
        margin:auto;
        margin-top:200px;
    }
    table td{
        padding:0.5em;
    }
    
</style>

<script src="https://www.google.com/recaptcha/api.js?render=<?=$data['key_google']?>"></script>

<h2>Hola <?=isset($data)?$data['user']:"Axel"?>. Aun no ha verificado su cuenta. Active su cuenta ahora.</h2>
<form action="<?=URL_COMPLETA?>/Login/resend_email" method='POST'>
<table>
    <tr>
        <td colspan='2'>RESEND EMAIL</td>
    </tr>
    <tr>
        <td>
            <label for="resend-email">Ingrese su correo:</label>
        </td>
        <td>
            <input type="text" name="resend-email" id="resend-email">
        </td>
    </tr>
    <tr>
        <td>
            <label for="resend-user">Ingrese su nombre de Usuario:</label>
        </td>
        <td>
            <input type="text" name="resend-user" id="resend-user">
        </td>
    </tr>
    <tr>
        <td colspan='2'>
            <input name='<?=$data['name_key_google']?>' id="<?=$data['name_key_google']?>" type="hidden">
        </td>
    </tr>
    <tr>
        <td colspan='2'>
            <input type="hidden" value="<?=isset($data)?$data['key']:"4444"?>" name="cript">
        </td>
    </tr>
    <tr>
        <td colspan='2'>
            <input type="submit" name='<?=isset($data)?$data['name-form']:"myform"?>' value="Enviar">
        </td>
    </tr>
</table>
</form>

<script>
    grecaptcha.ready(function() {
        grecaptcha.execute('<?=$data['key_google']?>', {action: 'homepage'}).then(function(token) {
            // Add your logic to submit to your backend server here.
            document.getElementById('<?=$data['name_key_google']?>').value=token;

        });
    });
    
</script>
