<?php

class LandingController extends Controller
{

    public function __construct($data = array())
    {
        parent::__construct($data);
    }

    public function index()
    {
        $this->data['result'] = 'data';
//        exit();
    }

}
