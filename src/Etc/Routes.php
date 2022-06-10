<?php 
namespace Etc;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use MyControllers\MainController;
use PDO;

class Routes {
    const SITE_URL = 'http://localhost';

    public function __construct(){
        require_once dirname(__DIR__, 2).'/vendor/autoload.php';
        $uri = $_SERVER['REQUEST_URI'];
        $mc = new MainController();
        switch ($uri){
        case '/logout':
            {
                $mc->logout();
            }
        case '/login':
            {
                $mc->login($_POST['loginName'], $_POST['password']);
                break;
            }
        case '/registration':
            {
                $mc->registration($_POST['loginName'], $_POST['password']);
                break;
            }
        case '/getUserById':
            {
                $mc->getUserById($_POST['UserID']);
                break;
            }
        case '/send':
            {
                $mc->send($_POST['message'], $_COOKIE['loginName']);
                break;
            }
        case '/delete':
            {
                $mc->delete($_POST['loginName']);
                break;
            }
        default:
            {
                $mc->showIndex();
                break;
            }
        }
    }
}