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

              if ( $cars = CarModel::getCars(Session::get('user_uuid'))  )

	               {

                       $otherscars=array(); $auth_car_list=array();
                        if ($auth_cars = CarModel::getAuthCarsForUser(Session::get('user_uuid'), 80, 80))
                        {
                           foreach ($auth_cars as $auth_car)
                               {
                                   $auth_car = (array)$auth_car;
                                   $otherscars[]=CarModel::getCar($auth_car['car']);
                                   $auth_car_list[]=$auth_car['car'];
                               }
                        }


					$this->View->render('index/index', array(
                        'cars' => $cars,
                        /* in case we decide to display other users cars on the same page:
                        'auth_car_list' =>$auth_car_list,
                        'otherscars' => $otherscars*/
                    ));

				   } else {

        Redirect::to('car/new_car');

					}

          } else {
    Redirect::to('login/index');
    }


    }

	public function jenesaisquoi () //cron job
    {
    $this->View->renderWithoutHeaderAndFooter('index/cron');
	}


     public function save_it()
    {
        NoteModel::saveEmail(Request::post('email'));
        Redirect::to('index/index');
    }




}
