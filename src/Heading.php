<?php
namespace Bookdown\Content;

class Heading
{
    protected $number;

    protected $title;

    protected $id;

    protected $href;

    protected $level;

    public function __construct($number, $title, $href, $id = null)
    {
        $this->number = $number;
        $this->title = $title;
        $this->href = $href;
        $this->id = $id;
        $this->level = substr_count($number, '.');
    }

    public function __get($key)
    {
        throw new Exception("Use a method");
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getHref()
    {
        $href = $this->href;
        if ($this->id) {
            $href .= '#' . $this->id;
        }
        return $href;
    }

    public function getLevel()
    {
        return $this->level;
    }
}
