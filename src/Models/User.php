<?php
namespace Models;
use PDO;
class User
{
    public function __construct()
    {
        $this->dbh = new PDO('mysql:host=localhost;dbname=forum', 'root', 'root');
    }
    private string $login;
    private string $pass;
    private PDO $dbh;

    private function findByLogin(){
        $sql="SELECT * FROM `forum`.`user` WHERE userName=:userName";
        $stmt=$this->dbh->prepare($sql);
        $stmt->bindParam(':userName', $this->login);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findByID($ID){
        $sql="SELECT userName from forum.user WHERE id=:id";
        $stmt=$this->dbh->prepare($sql);
        $stmt->bindValue(':id', $ID);
        $stmt->execute();
        $result=$stmt->fetchAll();
        if (count($result)>0){
            $newUser = new User();
            $newUser->setLogin($result[0]['userName']);
            return $newUser;
        } else{
            return null;
        }

    }

    public function AllUsers(){
        $sql="SELECT userName from forum.user;";
        $stmt=$this->dbh->prepare($sql);
        $stmt->execute();
        $users = array();
        $results = $stmt->fetchAll();
        foreach ($results as $result){
            $newUser = new User();
            $newUser->setLogin($result['userName']);
            array_push($users, $newUser);
        }
        return $users;
    }

    public function findByLoginPass(){
        $sql="SELECT * FROM `forum`.`user` WHERE userName=:userName and password=:password";
        $stmt=$this->dbh->prepare($sql);
        $stmt->bindParam(':userName', $this->login);
        $stmt->bindParam(':password', $this->pass);
        $stmt->execute();
        $arr=$stmt->fetchAll();
        if(count($arr)>0){
            $User = new User();
            $User->setPass($this->pass);
            $User->setLogin($this->login);
            return $User;
        } else {
            return null;
        }
    }

    public function delete(){
        $sql="DELETE FROM `forum`.`user` WHERE userName=:userName;";
        $stmt=$this->dbh->prepare($sql);
        $stmt->bindParam(':userName', $this->login);
        $stmt->execute();
    }

    public function save(){
        if(count($this->findByLogin())>0){
            return 0;
        }
        else {
            $sql="INSERT INTO `forum`.`user` (`userName`, `password`) VALUES (:userName, :password);";
            $stmt=$this->dbh->prepare($sql);
            $stmt->bindParam(':userName', $this->login);
            $stmt->bindParam(':password', $this->pass);
            $stmt->execute();
        }
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setLogin($login): void
    {
        $this->login = $login;
    }

    public function setPass(string $pass): void
    {
        $this->pass = $pass;
    }
}