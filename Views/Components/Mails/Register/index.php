<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Correo de Verificacion</title>
    <style>
        #correo{
            border-radius:4%;
            font-family:'Amatic SC', cursive;
            background:rgb(21, 25, 49);
            box-sizing:border-box;
            padding:1em;
            margin:auto;
            width:500px;
            height:350px;
            text-align:center;
            box-shadow:0 0 3px 1px black;
            position:relative;
            color:white;
            text-shadow:1px 1px 2px rgb(31, 31, 31);
            }
        #part1, #part2{
            width:100%;
        }
        #correo img{
            width:25%;
            height:30%;
            margin: 1em;
        }
         
        #correo h1{
            color:white;
        }
        #correo p{
            color:white;
            letter-spacing:1px;
        }
        #active-button-validate:hover{
            cursor:pointer;
            background:rgba(118, 219, 213,0.7);
            text-shadow:1px 1px 2px rgb(31, 31, 31),
                        -2px 2px 5px rgb(21, 25, 49),
                        2px -2px 5px rgb(21, 25, 49);
            color:whitesmoke;
            
        }
        #active-button-validate{
            background:rgba(118, 219, 213,0.4);
            display:block;
            box-sizing:border-box;
            margin:auto;
            text-decoration:none;
            width:150px;
            height:55px;
            font-size:1.1em;
            font-family:'Amatic SC', cursive;
            font-weight:800;
            border-radius:5%;
            border:none;
            padding:0.8em;
            border:4px solid rgba(118, 219, 213,0.4);
            box-shadow: 0 0 0 1px inset rgba(118, 219, 213,0.4) ,
                        0 0 0 2px inset rgba(118, 219, 213,0.4), 
                        0 0 0 1px rgba(118, 219, 213,0.4), 
                        0 0 7px 5px inset rgba(118, 219, 213,0.4), 
                        0 0 7px 4px rgba(118, 219, 213,0.4);  

            text-shadow:1px 1px 2px rgb(31, 31, 31),
                        1px 2px 10px rgba(118, 219, 213,1),
                        1px -2px 5px rgba(118, 219, 213,1); 
            color:white;
        }

        @media screen and (max-width: 1532px){
            #correo{
                width:450px;
                box-shadow: 0 0 0 1px inset rgba(118, 219, 213,0.4),
                            0 0 0 2px inset rgba(118, 219, 213,0.4), 
                            0 0 0 1px rgba(118, 219, 213,0.4), 
                            0 0 7px 5px inset rgba(118, 219, 213,0.4), 
                            0 0 7px 4px rgba(118, 219, 213,0.4); 
            }
            #correo img{
                width:20%;
                height:25%;
            }
            #active-button-validate{
                width:130px;
                height:45px;
                font-size:1em;
            }
        }
        @media screen and (max-width: 1024px){
            #correo{
                width:350px;
                height:300px;
            }
            #correo h1{
                font-size:1.6em;
            }
            #correo p{
                font-size:0.85em;
            }
            #active-button-validate{
                width:100px;
                height:40px;
                font-size:0.8em;
            }
        }
        @media screen and (max-width: 768px){
            #correo{
                width:280px;
                height:250px;
                box-shadow:0 0 10px 0px blue;
            }
            #correo img{
                width:25%;
                height:30%;
                margin:0.7em;
            }
            #correo h1{
                font-size:1.2em;
            }
            #correo p{
                font-size:0.75em;
            }
            #active-button-validate{
                width:90px;
                height:30px;
                font-size:0.6em;
            }
        }
        @media screen and (max-width: 360px){
            #correo{
                width:200px;
                height:200px;
                box-shadow:0 0 10px 0px red;
            }
            #correo img{
                width:30%;
                height:30%;
                margin:0.5em;
            }
            #correo h1{
                font-size:0.9em;
            }
            #correo p{
                font-size:0.5em;
            }
            #active-button-validate{
                width:65px;
                height:25px;
                font-size:0.45em;
            }
        }
    </style>
</head>
<body>
<div id='correo'>
    
        <h1>Correo de Verificacion mynamesapp</h1>
        <p>Bienvenido a la comunidad. Haz click en el boton para activar tu cuenta.</p>
        <p>El link de activacion tiene una duracion de 1hora de validez.</p>
    
   
    <div id='part1'> <img src="miurlimagen" alt="logo-app"> </div>
    <div id='part2'> <a id='active-button-validate' href='milink'>Activar mi Cuenta</a> </div>
   
    
</div>
</body>
</html>