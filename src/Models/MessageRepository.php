<?php

namespace Models;

class MessageRepository
{
    private $messages=array();
    private MessageDataMapper $mapper;

    public function __construct()
    {
        $this->mapper=new MessageDataMapper();
        $this->messages=$this->mapper->getAll();
    }

    public function All(){
        return $this->messages;
    }
    public function getById($ID){
        foreach ($this->messages as $message){
            if($message->getId()==$ID){
                return $message;
            }
        }
        return null;
    }
    public function getByAuthor($Author){
        $arr = array();
        foreach ($this->messages as $message){
            if($message->getAuthor()==$Author){
                array_push($arr, $message);
            }
        }
        return  $arr;
    }
    public function add($message){
        $this->mapper->add($message);
        $this->messages=$this->mapper->getAll();
    }
    public function deleteAllByLoginName($loginName){
        foreach ($this->messages as $message){
            if($message->getAuthor()==$loginName){
                $this->mapper->delete($message);
            }
        }
        $this->messages=$this->mapper->getAll();
    }
}