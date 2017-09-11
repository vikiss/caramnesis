<?php  //private profile for editing own pages

class ProfileController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Auth::checkAuthentication();
    }

     public function index()
    {
        $this->View->render('profile/PrivatePage', array(
              'saved_page' => ProfileModel::getProfilePage(Session::get('user_uuid')),
              'page_owner' => Session::get('user_uuid'),
          ));
    }


     public function save()
     {

    if (!ProfileModel::saveProfilePage(array(
        'user_id' => Request::post('user_id'),
        'show_page' => Request::post('show_page'),
        'show_cars4sale' => Request::post('show_cars4sale'),
        'show_partcars4sale' => Request::post('show_partcars4sale'),
        'images' => Request::post('user_images'),
        'location' => Request::post('location'),
        'description' => Request::post('description'),
        'title' =>  Request::post('title'),
        'contact' =>  Request::post('contact'),
    ))) {
         Session::add('feedback_negative', _('FEEDBACK_UNKNOWN_ERROR'));
    }
    Redirect::to('profile/index');
    }

    public function edit()
    {
       $this->View->render('profile/EditPage', array(
             'saved_page' => ProfileModel::getProfilePage(Session::get('user_uuid')),
             'page_owner' => Session::get('user_uuid'),
         ));
    }


}
