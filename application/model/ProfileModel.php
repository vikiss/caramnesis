<?php

class ProfileModel
{

    public static function saveProfilePage($data)
{
    if (is_array($data)) {
        //array_walk_recursive($data, 'Filter::XSSFilter'); screws up quotes and stuff
      if ($data['user_id'] == Session::get('user_uuid'))
          {
              $data['content'] = '';
             $content = array();
             if  ($data['show_page'] == 'Y') {
                 $content[] = 'show_page';
             }
             if  ($data['show_cars4sale'] == 'Y') {
                  $content[] = 'show_cars4sale';
              }
             if  ($data['show_partcars4sale'] == 'Y') {
                 $content[] = 'show_partcars4sale';
             }
              $data['content'] = json_encode($content);
              unset($data['show_page']); unset($data['show_cars4sale']); unset($data['show_partcars4sale']);

              if ($data['images']) {                  $imagelist  = explode(',', $data['images']);              };
              $data['images'] = serialize($imagelist);



     $database = DatabaseFactory::getFactory()->getConnection();
     $field_list_arr = array();
     $field_colon_list_arr = array ();
     $argument_list = array();
     $on_duplicate_list_arr = array();
     foreach ($data as $key => $value) {
     $field_list_arr[] = $key;
     $field_colon_list_arr[] = ':'.$key;
     $argument_list[':'.$key] = $data[$key];
     $on_duplicate_list_arr[] = $key.' = :'.$key;
     }
     $field_list = implode(', ', $field_list_arr);
     $field_colon_list = implode(', ', $field_colon_list_arr);
     $on_duplicate_list = implode(', ', $on_duplicate_list_arr);
      $sql = "INSERT INTO user_pages ($field_list) VALUES
   ($field_colon_list) ON DUPLICATE KEY UPDATE $on_duplicate_list";
      $query = $database->prepare($sql);
      if	($query->execute($argument_list))
      { return true; }
    }}
    return false;
}

    public static function getProfilePage($uuid)
{
    $database = DatabaseFactory::getFactory()->getConnection();
    $query    = $database->prepare("SELECT * FROM user_pages WHERE user_id = :user_id");
    $query->execute(array(
        ':user_id' => $uuid,
    ));
    if ($data = $query->fetchAll()) {
        return $data;
    } else
        return false;
}

}
