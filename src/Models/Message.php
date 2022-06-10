<?php

namespace Models;

class Message
{
    private $id;
    private $date;
    private $author;
    private $message;

    public function __construct($date, $author, $message, $id)
    {
        $this->message=$message;
        $this->author=$author;
        $this->date=$date;
        $this->id=$id;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message): void
    {
        $this->message = $message;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author): void
    {
        $this->author = $author;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date): void
    {
        $this->date = $date;
    }

    public function getId()
    {
        return $this->id;
    }
}