<?php

class MessageController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Auth::checkAuthentication();
    }

     public function index($page = 0) //list of received messages for current user
    {
    $offset = 0;
    $records = Config::get('MESSAGE_PAGING');
    if (intval($page) > 1) {$offset = ($page-1) * $records;}
    $messages = MessageModel::checkMessages(Session::get('user_uuid'), array('records' => $records, 'offset' => $offset));
    MessageModel::resetUnreadMessages(Session::get('user_uuid'));
        $this->View->render('message/index', array('messages' => $messages));
    }

     public function sent($page = 0) //list of sent messages for current user
    {
    $offset = 0;
    $records = Config::get('MESSAGE_PAGING');
    if (intval($page) > 1) {$offset = ($page-1) * $records;}
    $messages = MessageModel::checkSentMessages(Session::get('user_uuid'), array('records' => $records, 'offset' => $offset));
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

 public function remindercount()
    {
        $this->View->renderWithoutHeaderAndFooter('message/remindercount', array('remindercount' => ReminderModel::getReminderCount(Session::get('user_uuid'))));
    }

 public function reminders($since_when = 0)
    {
        $uuid = Session::get('user_uuid');
        $reminders = ReminderModel::getReminders($uuid);
        ReminderModel::resetActiveReminders($uuid);
        $this->View->render('message/reminders', array(
                                                       'reminders' => $reminders,
                                                       'cars' => CarModel::getCars($uuid),
                                                       'since_when' => $since_when
                                                       )
                            );
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

      public function reminder($car_id, $time, $microtime, $msgstatus)  //look at a single reminder to postpone it, unmark as active
    {
        $reminder = ReminderModel::getReminder($car_id, $time, $microtime);
    if ($reminder !== false) {
        if ($msgstatus === 'Q') {
            ReminderModel::deleteReminderByCarTime($car_id, $time);
            ReminderModel::DecrementActiveReminders(Session::get('user_uuid'));
        }}
        $this->View->render('message/reminder', array('reminder' => $reminder));
    }


    /*public function del_cass_reminder($car_id, $time, $microtime)  //delete reminder
    {
        $reminder = ReminderModel::getReminder($car_id, $time, $microtime);
        $result = '';
    if ($reminder !== false) {
            $result = ReminderModel::deleteReminderCass($car_id, $time, $microtime);
        }
        $this->View->renderWithoutHeaderAndFooter('car/generic_response', array('response' => $result, 'type' => 'truefalseicon'));
    }*/


    public function delete_reminder()  //same delete cass reminder except with posted data and no ajax
    {
        $reminder = ReminderModel::getReminder(
            Request::post('dlg_car_id'),
            Request::post('dlg_time'),
            Request::post('dlg_microtime')
                                               );

    if ($reminder !== false) {
            if (ReminderModel::deleteReminder(
            $reminder->id,
            Request::post('dlg_car_id'),
            Request::post('dlg_time')
            ))
            {
                Session::add('feedback_positive', _('REMINDER_DELETED'));

            } else {
                Session::add('feedback_negative', _('REMINDER_DELETE_FAILED'));
            }
        }
        Redirect::to('message/reminders');
    }








}
