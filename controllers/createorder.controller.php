<?php

class createorder extends Model
{

    private $upload;

    public function __construct()
    {
        parent::__construct();
        $this->upload = new UploadController();
    }

    public function index()
    {
        return "test";
    }

}
