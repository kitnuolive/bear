<?php

class accountController extends Controller
{

    private $upload;

    public function __construct()
    {
        parent::__construct();
        $this->upload = new uploadController();
    }

    public function index()
    {
        return "test";
    }

}
