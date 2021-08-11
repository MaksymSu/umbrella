<?php

/**
 * Created by PhpStorm.
 * User: LB
 * Date: 26.06.2019
 * Time: 08:57
 */

//Базовый клас. Содержит статические методы для работы с записью таблицы
class DbRecord{
    public $query;
        public static function find(){}
        public static function select(){}
        public static function delete(){}
        public static function update(){}
        public static function create(){}
        public static function save(){}
        //.......

    private function connect(){
          //тут вызов static::tableName() потомка и соединение
        }
        //...
}

//Класс книга.  Содержит свойства таблицы books
class Book extends DbRecord {
    //устанавливает таблицу для базового класса
    public static function tableName(){
        return 'books';
    }

    public $name;
    public $id;
    public $dob;

    //получить всех авторов книги
    public function getAuthors(){}
}


//Класс автор.  Содержит свойства таблицы автор
class Author extends DbRecord {
    public static function tableName(){
        return 'authors';
    }
    public $name;
    public $id;
    public $dob;

    //получить все книги автора
    public function getBooks(){}
}

//Класс библиотека.  Содержит связи книг и авторов и методы по условию задания.
class Library extends DbRecord {
    public static function tableName(){
        return 'author_book';
    }

    public $author_id;
    public $book_id;

    public static function addBooks($books){/*...*/ }
    public static function deleteBooks($books){/*...*/ }
    public static function listBooksByAuthor($author, $sort=false){/*...*/ }
    public static function getBooksByAuthor($author,$sort=false){/*...*/ }
    public static function getBooksByDOB($dob,$sort=false){/*...*/ }
}



class Accounting extends DbRecord {
    public static function tableName(){
        return 'accounting';
    }
    public $name;
    public $state_id;

    public static function sell($book){
        //добавить в таблицу запись с book_id и состоянием "sold" (id 1 например)

    }

    public static function rent($book){}
    public static function
}

