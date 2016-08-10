<?php

class MessageController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Auth::checkAuthentication();
    }
    
     public function index() //list of received messages for current user
    {
    $messages = MessageModel::checkMessages(Session::get('user_uuid'));
        $this->View->render('message/index', array('messages' => $messages));
    }
    
     public function sent() //list of sent messages for current user
    {
    $messages = MessageModel::checkSentMessages(Session::get('user_uuid'));
        $this->View->render('message/sent', array('messages' => $messages));
    }
    
     public function send() //send a message to another user
    {
    MessageModel::sendMessage(
        Session::get('user_uuid'),
        UserModel::getUUidByUserName(Request::post('recipient')),
        Request::post('message'),
        Request::post('subject')
                              ) ;
    Redirect::to('message/index');                               
    }
    
 public function new_message()
    {
        $this->View->render('message/new_message');
    }
    
 public function msgcount()
    {
        $this->View->renderWithoutHeaderAndFooter('message/msgcount', array('msgcount' => MessageModel::getUnreadMessages(Session::get('user_uuid'))));
    }    
    
public function reply_message($timeuuid)
    {
        $this->View->render('message/reply_message', array('message' => MessageModel::getMessage(Session::get('user_uuid'), $timeuuid, '')));
    }    
    
     public function single($timeuuid, $recipient = '') //recipient needed to let sender look at the same message body
    {
        $message = MessageModel::getMessage(Session::get('user_uuid'), $timeuuid, $recipient);
    if ($message !== false) {
        $this->View->render('message/single', array('message' => $message));
                            }
    }    
    
}