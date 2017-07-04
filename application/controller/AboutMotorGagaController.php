<?php

class AboutMotorGagaController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * This method controls what happens when you move to /dashboard/index in your app.
     */
    public function index()
    {
        $this->View->render('index/about');
    }
}
