<?php namespace MyApp\Core;
    
use MyApp\Core\DataBase\Query;
use PDO;
abstract class Model implements Query{
    use DataBase\DataBase;
    // Los Datos deben estar en minuscula en la mayoria los metodos son sensibles a mayus

    # Devuelve todos los datos de una tabla
    public function selectall(string $tabla):?array{
        $sql='SELECT * FROM `'.$tabla.'`';

        return self::sql1($sql);
    }
    # Devuelve datos deacuerdo a varios criterios de campos y condiciones AND/OR
    public function select(string $tabla, array $criterios, array $values, bool $condicion):?array{
        
        $condicion=($condicion)?'AND':'OR';
        $where="";
        for ($i=0; $i < count($criterios)-1; $i++) { 
            $where='`'.$criterios[$i].'`=\''.$values[$i].'\' '.$condicion.' ';
        }
        $where.='`'.$criterios[$i].'`=\''.$values[$i].'\'';

        $sql='SELECT * FROM `'.$tabla.'` WHERE '.$where;

        return self::sql1($sql);
    }
    # Inserta elementos en una tabla. Devuelve true/false
    public function insert(string $tabla, array $campos, array $elementos):?bool{
        $columnas="";
        $values="";
        for ($i=0; $i < count($campos)-1; $i++) { 
            $columnas.='`'.$campos[$i].'`, ';
            $values.='\''.$elementos[$i].'\', ';
        }
        $columnas.='`'.$campos[count($campos)-1].'`';
        $values.='\''.$elementos[$i].'\'';

        $sql='INSERT INTO `'.$tabla.'` ('.$columnas.') VALUES ('.$values.')';

        return self::sql2($sql);
        
    }

    # Actualiza elementos de una tabla. Devuelve true/false deacuerdo a varios criterios de campos y condiciones AND/OR
    public function update(string $tabla, array $campos, array $elementos, array $criterios, array $values, bool $condicion):?bool
    {
        $condicion=($condicion)?'AND':'OR';
        $update="";
        $where="";

        for ($i=0; $i < count($campos)-1; $i++){

            if($elementos[$i]=='CURRENT_TIMESTAMP'){

                $update.='`'.$campos[$i].'`='.$elementos[$i].', ';
            }   
            else{
                $update.='`'.$campos[$i].'`=\''.$elementos[$i].'\', ';
            }
        }

        $update.='`'.$campos[count($campos)-1].'`=\''.$elementos[count($campos)-1].'\'';

        
        for ($j=0;$j < count($criterios)-1; $j++)
            $where.='`'.$criterios[$j].'`=\''.$values[$j].'\' '.$condicion.' ';
            
        $where.='`'.$criterios[count($criterios)-1].'`=\''.$values[count($criterios)-1].'\'';

        $sql='UPDATE `'.$tabla.'` SET '.$update.' WHERE '.$where;

        return self::sql2($sql);
       
    }
    # Elimina una fila de una tabla. Devuelve true/false deacuerdo a varios criterios de campos y condiciones AND/OR. Extremo cuidado
    public function delete(string $tabla, array $criterios, array $values, bool $condicion):?bool
    {
        $condicion=($condicion)?'AND':'OR';
        $where="";
        for ($i=0; $i < count($criterios)-1; $i++) { 
            $where='`'.$criterios[$i].'`=\''.$values[$i].'\' '.$condicion.' ';
        }
        $where.='`'.$criterios[count($criterios)-1].'`=\''.$values[count($criterios)-1].'\'';

        $sql='DELETE FROM \`'.$tabla.'` WHERE '.$where;
        
        return self::sql2($sql);
        
    }

    # Devuelve datos deacuerdo a varios criterios de campos y condiciones AND/OR. Se utiliza comodines. Un maximo de 20 criterios
    public function search(string $tabla, array $criterios, array $values, bool $condicion):?array
    {
        if(count($criterios)>21 || count($criterios)!=count($values)){
            return null;
        }
        
        $condicion=($condicion)?'AND':'OR';
        $where="";
        $letra="a";
        for ($i=0; $i < count($criterios)-1; $i++) { 
            $where='`'.$criterios[$i].'` LIKE :data'.$letra.' '.$condicion.' ';
            $letra++;
        }
        $where.='`'.$criterios[count($criterios)-1].'` LIKE :data'.$letra;
        $sql="SELECT * FROM `".$tabla."` WHERE ".$where;
        return self::sql3($sql,$values);
    }

    public function sql1(string $sql):?array{
        $data=[];
        try{
            $resultado=self::connect()->prepare($sql);
            if($resultado->execute()){
                $data=$resultado->fetchAll(PDO::FETCH_ASSOC);
            }
            $resultado->closeCursor();
            $resultado=null;
            return $data;
        }catch(PDOException $e){
            return $data;
        }
    }
    public function sql2(string $sql):?bool{
        try{
            $resultado=self::connect()->prepare($sql);
            $confirm=$resultado->execute();
            $resultado->closeCursor();
            $resultado=null;
            return $confirm;
        }catch(PDOException $e){
            return false;
        }
    }
    public function sql3(string $sql, array $values):?array{
        $data=[];
        try{
            $resultado=self::connect()->prepare($sql);
            $letra="a";

            for ($i=0; $i <count($values) ; $i++) {
                $conmodin=":data".$letra;
                $aux="%".$values[$i]."%";
                $resultado->bindValue($conmodin, $aux);
                $letra++;
            }
            
            if($resultado->execute()){
                $data=$resultado->fetchAll(PDO::FETCH_ASSOC);
            }

            $resultado->closeCursor();
            $resultado=null;
            return $data; 
            
        }catch(PDOException $e){
            return $data; 
        }
    }

    /* 
        Tabla principal -> La tabla primaria (String)
        Tabla secundaria -> Las tabla secundaria (String)
        Campos-> Los campos ha obtener(Array)
        Join -> INNER JOIN, LEFT JOIN, RIGTH JOIN. (String)
        ON -> Las clausulas para el join(Array)
        Criterios -> Las comparaciones para el WHERE(Opcional Array de Arrays)
        Condicion -> La condicion para OR/AND ( Array de Arrays)    
    
    
    */
    public function selectjoin(string $tabla_principal, string $tabla_secundaria, array $campos, string $join, array $on, array $criterios, bool $condicion):array 
    {
       #Para los campos
       $camps="";
       for ($i=0; $i < count($campos)-1 ; $i++) { 
           $camps.="".$campos[$i].", ";           
       }
       $camps.= "".$campos[count($campos)-1].""; 
      
       #PARA LAS CLAUSULAS JOIN Y WHERE
       $join_clausuras=" ON ";
       $join_clausuras.=$tabla_principal.".".$on[0]." = ".$tabla_secundaria.".".$on[1]." ";
       $condicion=($condicion)?'AND':'OR';
       $where="";
       
       for ($i=0; $i < count($criterios)-1; $i++) { 
           $where.="WHERE ".$criterios[$i][0].'=\''.$criterios[$i][1].'\' '. $condicion.' ';
       }
       if(count($criterios)>0){
            if(count($criterios)==1)
                $where.="WHERE ";         

            $where.=$criterios[count($criterios)-1][0].'=\''.$criterios[count($criterios)-1][1].'\'';
       }
       
       $sql="SELECT $camps FROM `$tabla_principal` ".$join." `".$tabla_secundaria."`".$join_clausuras.$where;

       var_dump($sql);
       
       try{
           // $sql='SELECT * FROM `usuarios` INNER JOIN `perfiles`  ON usuarios.Id = perfiles.IdUsuario';
           $resultado=self::connect()->prepare($sql);
           $data=[];
           if($resultado->execute()){
               $data=$resultado->fetchAll(PDO::FETCH_ASSOC);
           }

           $resultado->closeCursor();
           return $data;
       }catch(PDOException $e){
           return [];
       }

    }




}
?>