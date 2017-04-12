<?php
/**
 * Created by PhpStorm.
 * User: Digi3
 * Date: 3/12/2015
 * Time: 17:42
 */
use Parse\ParseACL;
use Parse\ParsePush;
use Parse\ParseUser;
use Parse\ParseInstallation;
use Parse\ParseException;
use Parse\ParseAnalytics;
use Parse\ParseFile;
use Parse\ParseCloud;
use Parse\ParseQuery;
use Parse\ParseObject;
use Parse\ParseClient;

class Controller_User extends Controller_Parse{
  public function action_index()
  {
    if($this->output_format == 'php'){
      $this->success_redirect('/');
      return;
    }
    if($this->is_unauthorized())return;
    //show user home

    $this->apply_parse_value(
      $this->template_body,
      $this->current_user,
      array('username', 'email', 'allowShow', 'nickname', 'suggestText')
    );
  }

  /* user profile*/
  public function action_profile()
  {
    if($this->is_unauthorized())return;

    $this->apply_parse_value(
      $this->template_body,
      $this->current_user,
      array('username', 'email', 'allowShow', 'nickname')
    );
  }

  public function action_profile_update()
  {
    if($this->is_unauthorized())return;
    if(!$this->validate_post(array('nickname')))return;

    if(isset($_POST['allowShow'])){
      $allowShow = $_POST['allowShow'] == 'true' || $_POST['allowShow'] == 'on';
    }else{
      $allowShow = false;
    }

    $nickname  = $_POST['nickname'];

    try{
      $this->current_user->set('allowShow', $allowShow);
      $this->current_user->set('nickname',  $nickname);
      $this->current_user->save(TRUE);

      $this->apply_parse_value(
        $this->template_body,
        $this->current_user,
        array('username', 'email', 'allowShow', 'nickname', 'suggestText')
      );
      $this->output->status='success' ;
    }catch(ParseException $ex){
      $this->output->status = 'error';
      $this->output->message= $ex->getMessage();
    }

    if($this->output_format == 'php'){
        $this->success_redirect('user/profile');
    }
  }

  /* login*/
  public function action_login(){}

  public function action_login_submit()
  {
    if(!empty($_POST['fbid'])) {
      //login by fb
      if(!$this->validate_post(array('username', 'fbid', 'fbtoken')))return;
      $this->login_facebook();
    }else{
      if(!$this->validate_post(array('username', 'password')))return;
      $this->login_password();
    }
  }

  private function login_facebook(){
    $username    = $_POST['username'];
    $fb_id       = $_POST['fbid'];
    $fb_token    = $_POST['fbtoken'];

    try{
      ParseUser::logInWithFacebook($fb_id, $fb_token);
      $this->current_user = ParseUser::getCurrentUser();

      if(empty($this->current_user->get('email'))){
        $this->output->status = 'error';
        $this->output->message = '未有登記';

        ParseUser::logOut();

        if($this->output_format!='json'){
          $this->success_redirect('contributor/invite');
        }
      }else{
        $this->apply_parse_value(
          $this->template_body,
          $this->current_user,
          array('username', 'email', 'allowShow', 'nickname', 'suggestText')
        );

        if($this->output_format!='json'){
          $this->success_redirect(empty($_POST['destination'])? 'user' : $_POST['destination']);
        }
      }
      return;
    } catch(ParseException $ex) {
      $this->error_redirect($ex->getCode(),
        array(
          'email'  => $username,
          'message'=> urlencode($ex->getMessage())
        )
      );
    }
  }

  private function login_password(){
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
      ParseUser::logIn($username, $password);
      $this->success_redirect('user');
    } catch(ParseException $ex) {
      $this->error_redirect($ex->getCode(),
        array(
          'email'  => $username,
          'message'=> urlencode($ex->getMessage())
        )
      );
    }
  }

  /* logout */
  public function action_logout()
  {
    ParseUser::logOut();
    $this->output->status = 'logout';
    if($this->auto_render === TRUE) {
     // do stuff with the user
      $this->template->user_login_status = 'anonymous';
    }
  }

  /* resend activation email */
  public function action_resend(){}

  public function action_resend_submit(){
    if(!$this->validate_post(array('username')))return;

    $username = $_POST['username'];

    $query = new ParseQuery("_User");
    try {
      $query->equalTo('username', $username);
      $user = $query->first(true);
      if(empty($user)){
        $this->error_redirect('950' , array('email'=>$username));
        return;
      }

      $user->set('email', $user->get('email'));
      $user->save(true);
      //resent success
      $this->success_redirect('user/resend_success');
    } catch (ParseException $ex) {
      $this->error_redirect($ex->getCode());
    }
  }

  public function action_resend_success(){}

  /* reset password */
  public function action_reset_password(){
    $this->template_body->email = isset($_GET['email']) ? $_GET['email']:'';
  }

  public function action_reset_password_submit(){
    if(!$this->validate_post('email'))return;
    $email = $_POST['email'];
    try{
      ParseUser::logOut();
      ParseUser::requestPasswordReset($email);
      $this->success_redirect('user/reset_password_send_success?email='.$email);
    }catch(ParseException $ex) {
      $pos = strrpos($ex->getMessage(), "No user found with");

      $this->error_redirect(($pos === FALSE) ? $ex->getCode() : '950',
        array(
          'email'  => $email,
          'message'=> urlencode($ex->getMessage())
        )
      );
    }
  }

  public function action_reset_password_send_success(){
    $this->template_body->email = $_GET['email'];
  }

  public function action_set_password(){
    $config = Kohana::$config->load('site')->get('parse');
    if(!isset($_GET['username']) || !isset($_GET['token'])){
      $this->error_redirect('900');
      return;
    }

    if(isset($_GET['error'])){
      $this->error_redirect('902', $_GET);
      return;
    }

    $this->apply_values(
      $this->template_body,
      array(
        'username'    => $_GET['username'],
        'destination' => $config['url'].'/'.$config['mount'].'/apps/'.$config['id'].'/request_password_reset',
        'token'       => $_GET['token'],
      )
    );
  }

  public function action_set_password_submit(){

  }

  public function action_set_password_fail(){

  }

  public function action_set_password_success(){

  }

  public function action_download()
  {
    if(empty($this->current_user)) {
      $this->output->status = 'login-required';
      $this->output->code = 10001;
      $this->output->html = '<h1 class="ui headline">單撈</h1><h4 class="ui headline">請先登入</h4>';
      return;
    }

    $this->apply_parse_value(
      $this->template_body,
      $this->current_user,
      array('package')
    );

    if($this->output_format == 'json'){
      $tpl = $this->get_view($this->controller_name.'/'.$this->action_name);
      $this->apply_parse_value(
        $tpl,
        $this->current_user,
        array('package')
      );
      $this->output->html = $tpl->render();
    }
  }

  public function action_download_submit()
  {
    if(empty($this->current_user)) {
      $this->error_redirect(10001, array('message'=> "請先登入"));
      return;
    }

    $pid = $this->getPackageId();
    if($pid < 5){
      $this->error_redirect(10002, array('message'=> "組別未能下載試水版"));
      return;
    }

    $download = new ParseObject("Download");
    $download->set('username', $this->current_user->get('username'));
    $download->save(true);

    $file = Helper_Filesearch::get_media($this->city, $this->language,'private/kickAssType-Regular-alpha.otf');
    if (file_exists($file)) {
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename="'.basename($file).'"');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: ' . filesize($file));
      readfile($file);
      exit;
    }
  }

  public function action_list(){
    $contributors = array();
    $query = new ParseQuery("_User");
    try {
      $users = $query->find(true);

      foreach($users as $user){
        if($user->get('allowShow') != 1)continue;
        if(empty($user->get('nickname')))continue;

        $authData = $user->get('authData');

        $contributors[] = array(
          'nickname'=>$user->get('nickname'),
          'pic' => isset($authData['facebook']) ? 'http://graph.facebook.com/v2.8/'.$authData['facebook']['id'].'/picture' : URL::site(Helper_Filesearch::get_media($this->city, $this->language, 'images/contributor.jpg'), TRUE)
        );
      }
      $this->output->contributors = $contributors;
    } catch (ParseException $ex) {
      // The object was not retrieved successfully.
      // error is a ParseException with an error code and message.

      $this->output->status = 'error';
      $this->output->message = Helper_Filesearch::get_view($this->city, $this->language, 'error/connection_error');
    }
  }

  public function action_package(){
    if($this->is_unauthorized()){
      $this->output->html = '請先登入';
      return;
    }

    $this->output->html = Helper_Filesearch::get_view($this->city, $this->language, 'package/'. $this->getPackageId())->render();

  }

  private function getPackageId(){
    switch($this->current_user->get('package')){
      case '魚蛋起革命':
        $pid = 1;
        break;
      case '催淚支援':
        $pid = 2;
        break;
      case '香港開荒牛':
        $pid = 3;
        break;
      case '本土抬頭':
        $pid = 4;
        break;
      case '我係香港人':
        $pid = 5;
        break;
      case '一起的撐':
        $pid = 6;
        break;
      case '光復本土':
        $pid = 7;
        break;
      case '光輝歲月':
        $pid = 8;
        break;
      case '港人字決':
        $pid = 9;
        break;
      case '有請小鳯姐':
        $pid = 10;
        break;
      case '撐到底':
        $pid = 11;
        break;
      case '決志救港':
        $pid = 12;
        break;
      default:
        $pid = 0;
    }
    return $pid;
  }
}