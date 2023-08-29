<h2><?=$data['key_form']?></h2>
<div id='form'>
    <form  method='post' action="<?=URL_COMPLETA?>/Register/validacion"> <br>
    <label for="user">Usuario</label>
    <input name="user" type="text"> <br>
    <label for="mail">Mail</label>
    <input  name="mail" type="text"> <br>
    <label for="pass">Password</label>
    <input  name="pass" type="text"> <br>
    <label for="pass2">Repeat Password</label>
    <input  name="pass2" type="text"> <br>
    <label for="name">Names</label>
    <input  name="name" type="text"> <br>
    <label for="lastname">LastName</label>
    <input  name="lastname" type="text"> <br>
    <label for="birthday">Birthday</label>
    <input  name="birthday" type="date"> <br>
    <label for="nationality">Nationality</label>
    <input name="nationality" type="text"> <br>
    
    <div id="captcha" class="g-recaptcha" data-sitekey="<?=$data['key_google']?>"></div> <br>
    <input name='key' type="hidden" value='<?=$data['key_form']?>'> <br>
    
    <input type="submit" name="myform" value="Registrarse"> <br>
    </form>
</div>