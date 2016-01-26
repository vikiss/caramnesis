<?php

class IndexController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();
        //    Auth::checkAuthentication();
    }

    /**
     * Handles what happens when user moves to URL/index/index - or - as this is the default controller, also
     * when user moves to /index or enter your application at base level
     */
    public function index()
    {
	 
              
        if (LoginModel::isUserLoggedIn()) {              
              
              if ( $cars = CarModel::getCars()  )
                         
	               {$this->View->render('index/index', array('car' => $cars)); } else
              
                 {$this->View->render('car/new_car'); } 
          
          } else {
    $this->View->render('index/welcome');
    }
				

    }
    
    
    
    
     public function save_it()
    {
        NoteModel::saveEmail(Request::post('email'));
        Redirect::to('index/index');
    }
    

    
        
}