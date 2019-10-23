<?php
require_once('db.php');
require_once('DB_Connection.php');

abstract class Model {
    protected static $primaryKey = 'id';
    protected static $table;

    private static function pluralize($singular, $quantity=2, $plural=null) {
        if($quantity==1 || !strlen($singular)) return $singular;
        if($plural!==null) return $plural;

        $last_letter = strtolower($singular[strlen($singular)-1]);
        switch($last_letter) {
            case 'y':
                return substr($singular,0,-1).'ies';
            case 's':
                return $singular.'es';
            default:
                return $singular.'s';
        }
    }

    public static function find($id) {
        if (static::$table)
            $table = static::$table;
        else
            $table = self::pluralize(strtolower(static::class));
        $sql = 'select * from ' . $table;
        $sql .= ' where ' . static::$primaryKey . ' = :id limit 1';

        $pdo = DB_Connection::getInstance();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$data)
            return false;

        $object = new static;
        foreach ($data as $key => $value) {
            $object->$key = $value;
        }
        return $object;
    }

    public static function findAll() {
        if (static::$table)
            $table = static::$table;
        else
            $table = self::pluralize(strtolower(static::class));

        $pdo = DB_Connection::getInstance();

        $sql = 'select * from ' . $table;

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $objects = [];
        $object = null;
        foreach ($data as $item)
        {
            $object = new static;
            foreach ($item as $key => $value)
            {
                $object->$key = $value;
            }
            $objects[] = $object;
        }
        return $objects;
    }

    public function delete() {

        $identifier = static::$primaryKey;

        if (static::$table)
            $table = static::$table;
        else
            $table = self::pluralize(strtolower(static::class));

        $pdo = DB_Connection::getInstance();
        $sql = 'delete from ' . $table . ' where ' . static::$primaryKey . ' = ' . $this->$identifier;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }

    public function save() {

        $identifier = static::$primaryKey;

        if (static::$table)
            $table = static::$table;
        else
            $table = self::pluralize(strtolower(static::class));

        $pdo = DB_Connection::getInstance();

        $sql = 'show columns from ' . $table;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $columns = [];
        $values = [];
        foreach ($result as $column) {
            if ($column['Key'] != "PRI") {
                $column_name = $column['Field'];
                $columns[] = $column_name;
                $values[] = $this->$column_name;
            }
        }

        $data = array_combine($columns, $values);
        echo ('<pre>');
        var_dump($data);
        echo ('</pre>');

        $string_data = "";
//        foreach ($columns as $column)
//        {
//            $string_data.= $column . ' = ' . '"' . $this->$column . '", ';
//        }
//        $string_data = substr($string_data, 0, -2);


        if ($this->$identifier) {
            $sql = 'update ' . $table . ' set ' . $string_data . ' where ' . static::$primaryKey . ' = "' . $this->$identifier . '"';
        }
        else {
            $sql = 'insert into ' . $table . ' set ' . $string_data;
        }

        var_dump($string_data);
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
}

class User extends Model {

}

class Category extends Model {
    public static $primaryKey = 'cat_id';
}

class Post extends Model {

}

$user = new User;
$category = new Category;
$post = new Post;

$user->name = "4vasya pupkin";
$user->email = "4vasyapupkin@mail.ru";
$user->gender = 1;
$user->save();

//$category = Category::find(1);
//$category->name = 'c++';
//$category->save();

//$users = User::find(4);
//$users->name = 'izabell';
//$users->save();
