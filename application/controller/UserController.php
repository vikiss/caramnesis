<?php  //public pages!
class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        //Auth::checkAuthentication();
    }

    public function index($user_name)
    {
        if ($useruuid = UserModel::getUUidByUserName($user_name))
        {
            if ($saved_page = ProfileModel::getProfilePage($useruuid)) {
        $this->View->render('profile/PublicPage', array(
             'page_owner' => $useruuid,
             'saved_page' => $saved_page,
          ));
      } else {
          header('HTTP/1.0 404 Not Found', true, 404);
          $this->View->render('error/404');
      }
        }
    }



    public function someaction($user_name, $param1 = 'param2', $param2 = 'param2', $param3 = 'param3')
    {
        $this->View->render('profile/PublicPage', array(
              'user_name' => $user_name,
              'param1' => $param1,
              'param2' => $param2,
              'param3' => $param3,
          ));

    }
}
