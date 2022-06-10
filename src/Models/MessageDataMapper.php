<?php

namespace Models;
use PDO;
class MessageDataMapper
{
    private PDO $dbh;
    public function __construct()
    {
        $this->dbh = new PDO('mysql:host=localhost;dbname=forum', 'root', 'root');
    }
    public function getAll(){
        $sql = "SELECT * FROM forum.messages;";
        $stmt=$this->dbh->prepare($sql);
        $stmt->execute();
        $results=$stmt->fetchAll();
        $arr = array();
        foreach ($results as $result){
            $Message = new Message($result['date'], $result['author'], $result['message'], $result['id']);
            array_push($arr, $Message);
        }
        return $arr;
    }
    public function add($message){
        $sql = 'INSERT INTO forum.`messages` (`id`, `date`, `author`, `message`) VALUES (NULL, :date, :author, :message);';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':date', date("Y-m-d H:i:s"), PDO::PARAM_STR);
        $stmt->bindParam(':author', $message->getAuthor(), PDO::PARAM_STR);
        $stmt->bindParam(':message', $message->getMessage(), PDO::PARAM_STR);
        $stmt->execute();
    }
    public function delete($message){
        $sql = 'DELETE FROM forum.`messages` WHERE `date`=:date AND `author`=:author AND `message`=:message;';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':date', $message->getDate(), PDO::PARAM_STR);
        $stmt->bindParam(':author', $message->getAuthor(), PDO::PARAM_STR);
        $stmt->bindParam(':message', $message->getMessage(), PDO::PARAM_STR);
        $stmt->execute();
    }

}