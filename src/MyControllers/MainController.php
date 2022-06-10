<?php

namespace MyControllers;

use Models\User;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PDO;

class MainController{
    const SITE_URL = 'http://localhost';

    function logout(){
        setcookie('loginName', '', time() - 36000, '/');
        header('Location: '.self::SITE_URL);
    }

    function login($login, $password){
        $User = new User();
        $User->setLogin($login);
        $User->setPass($password);
        $currentUser=$User->findByLoginPass();
        if ($currentUser!=NULL){
            setcookie('loginName', $currentUser->getLogin());
        }
        header('Location: '.self::SITE_URL);
        var_dump(count($User->findByLoginPass()));
    }

    private function readSQL(){
        $messages = array();

        try {
            $dbh = new PDO('mysql:host=localhost;dbname=forum', 'root', 'root');
            $sql = 'SELECT * from messages';
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll();
            foreach($results as $result){
                $message=[
                'text'=>$result['message'],
                'date'=>$result['date'],
                'from'=>$result['author']
                ];
                array_push($messages, $message);
            }
            $dbh = null;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        return $messages;
    }
    
    function showIndex(){

        $loader = new FilesystemLoader(dirname(__DIR__, 2).'/src/Views/');
        $twig = new Environment($loader);
        $template = $twig->load('main.html.twig');
        
        $logger = new Logger('main');
        $logger->pushHandler(new StreamHandler(dirname(__DIR__, 2) . '/logs/app.log', Logger::INFO));

        $User = new User();

        echo $template->render(['logged' => isset($_COOKIE['loginName']), 
                                'loginName'=>$_COOKIE['loginName'],
                                'users'=>$User->AllUsers(),
                                'messages'=>$this->readSQL()
                                ]);
    }

    function getUserById($UserID){
        $User = new User();
        $loader = new FilesystemLoader(dirname(__DIR__, 2).'/src/Views/');
        $twig = new Environment($loader);
        $template = $twig->load('main.html.twig');

        $logger = new Logger('main');
        $logger->pushHandler(new StreamHandler(dirname(__DIR__, 2) . '/logs/app.log', Logger::INFO));

        $User = new User();

        echo $template->render(['logged' => isset($_COOKIE['loginName']),
            'loginName'=>$_COOKIE['loginName'],
            'users'=>$User->AllUsers(),
            'messages'=>$this->readSQL(),
            'SearchedLoginById'=>$User->findByID($UserID),
            'isLoginSearched'=>true
        ]);
    }

    function send($message_text, $author){
        try {
            $dbh = new PDO('mysql:host=localhost;dbname=forum', 'root', 'root');
            $sql = 'INSERT INTO `messages` (`id`, `date`, `author`, `message`) VALUES (NULL, :date, :author, :message);
';
            $stmt = $dbh->prepare($sql);

            $stmt->bindParam(':date', date("Y-m-d H:i:s"), PDO::PARAM_STR);
            $stmt->bindParam(':author', $author, PDO::PARAM_STR);
            $stmt->bindParam(':message', $message_text, PDO::PARAM_STR);
            $stmt->execute();

            $dbh = null;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        header('Location: '.self::SITE_URL);
    }

    function registration($login, $password){
        $newUser = new User();
        $newUser->setLogin($login);
        $newUser->setPass($password);
        $newUser->save();
        header('Location: '.self::SITE_URL);
    }

    function delete($login){
        $User = new User();
        $User->setLogin($login);
        $User->delete();
        $this->logout();
    }
}