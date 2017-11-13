<?php

class about extends Model
{

    private $upload;

    public function __construct()
    {
        parent::__construct();
        $this->upload = new UploadController();
    }

    public function aboutList()
    {
        return "test";
    }

}
