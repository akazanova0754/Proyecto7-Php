<?php namespace MyApp\Core\DataBase;
interface Query{
    #Basicos
    public function selectall(string $tabla):?array;
    public function select(string $tabla, array $criterios, array $values, bool $condicion):?array;
    public function insert(string $tabla, array $campos, array $elementos):?bool;
    public function update(string $tabla, array $campos, array $elementos, array $criterios, array $values, bool $condicion):?bool;
    public function delete(string $tabla, array $criterios, array $values, bool $condicion):?bool;
    public function search(string $tabla, array $criterios, array $values, bool $condicion):?array;


    public function sql1(string $sql):?array;#select y selectall
    public function sql2(string $sql):?bool;#insert, update, delete
    public function sql3(string $sql,array $values):?array; # search
    
    #Intermedio: INNER JOIN PARA DOS TABLAS

    public function selectjoin(string $tabla_principal, string $tabla_secundaria, array $campos, string $join, array $on, array $criterios, bool $condicion):?array;

    // public function updatejoin(array $tablas,array $campos,array $values,array $join,array $criterios,bool $condicion):?bool;
    // public function deletejoin():?bool;



}

?>