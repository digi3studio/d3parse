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

class Controller_Contributor extends Controller_Parse{
  public function action_index(){
    $this->error_redirect(10002, ['message'=>'invalid page']);
  }

  /* register */
  public function action_register(){
    $code = $_REQUEST['code'];

    $query = new ParseQuery('Contributor');

    try {
      $contributor = $query->get($code, true);
      $email = $contributor->get('email');
      $name = $contributor->get('name');

      $user = (new ParseQuery("_User"))
        ->equalTo('email', $email)
        ->first(TRUE);

      if($user){
        //register and verified
        $action = $user->get('emailVerified') ?
          'verify_success' :
          'register_success';

        $this->success_redirect('contributor/'.$action.'?code='.$code);
        return;
      }

      $this->template_body->email = $email;
      $this->template_body->code = $code;
      $this->template_body->name = $name;

    } catch (ParseException $ex) {
      $this->error_redirect($ex->getCode());
    }
  }

  private function register_facebook(){
    if(!$this->validate_post(array('code', 'fbid', 'fbtoken')))return;
    $code        = $_POST['code'];
    $fb_id       = $_POST['fbid'];
    $fb_token    = $_POST['fbtoken'];
    $fb_name     = $_POST['fbname'];
    $query = new ParseQuery("Contributor");
    try{
      $contributor = $query->get($code, true);
      $email = $contributor->get('email');
      $package = $contributor->get('package');
      $user = ParseUser::logInWithFacebook($fb_id, $fb_token);

      $user->set('username',  $email);
      $user->set('nickname', $fb_name);
      $user->set('email', $email);
      $user->set('allowShow', FALSE);
      $user->set('package', $package);
      $user->save(TRUE);

      $user->logOut();
      $this->redirect('contributor/register_success?code='.$code);
    } catch (ParseException $ex) {
      if($ex->getCode() == 202){
        $this->redirect('contributor/register?code='.$code);
        return;
      }

      $this->error_redirect($ex->getCode(),['message'=>$ex->getMessage()]);
    }
  }

  private function register_password(){
    $code        = $_POST['code'];
    $nickname    = $_POST['nickname'];
    $password    = $_POST['password'];

    $query = new ParseQuery("Contributor");
    try {
      $contributor = $query->get($code, true);
      $email       = $contributor->get('email');
      $package = $contributor->get('package');

      $user = new ParseUser();
      $user->set('username',  $email);
      $user->set('nickname',  $nickname);
      $user->set('password',  $password);
      $user->set('email',     $email);
      $user->set('allowShow', FALSE);
      $user->set('package', $package);
      $user->signUp();
      $user->logOut();

      $this->redirect('contributor/register_success?code='.$code);
    } catch (ParseException $ex) {
      if($ex->getCode() == 202){
        $this->redirect('contributor/register?code='.$code);
        return;
      }

      $this->error_redirect($ex->getCode(),['message'=>$ex->getMessage()]);
    }
  }

  public function action_register_save(){
    if(!empty($_POST['fbid'])){
      //register by fb
      $this->register_facebook();
    }else{
      if(!$this->validate_post(array('code', 'nickname', 'password'))){
        $this->error_redirect(10999, array('message'=>'register fail'));
        return;
      }
      $this->register_password();
    }
  }

  public function action_register_success(){
    $query = new ParseQuery('Contributor');
    $qUser = new ParseQuery('_User');

    try {
      $contributor = $query->get($_REQUEST['code'], true);
      $email       = $contributor->get('email');
      $qUser->equalTo('email',$email);
      $user = $qUser->first(TRUE);

      $this->apply_parse_value($this->template_body, $user, array('nickname', 'email'));

    } catch (ParseException $ex) {
//      $this->error_redirect($ex->getCode());
    }
  }

  public function action_verify_success(){
    if(empty($_GET['username'])){
      if(empty($_GET['code'])){
        $this->error_redirect(20001, array('message'=>'verify invalid'));
        return;
      }
      $contributor = (new ParseQuery('Contributor'))->get($_GET['code'], TRUE);
      $username = $contributor->get('email');
    }else{
      $username = $_GET['username'];
    }

    $record = (new ParseQuery('_User'))->equalTo('username', $username)->first(TRUE);

    if(!empty($record->get('authData')['facebook'])){
      $this->template_body->form_state = 'facebook-only';
    }else{
      $this->template_body->form_state = 'password-only';
    }
  }

  public function action_invalid_link(){}

  /* invite */
  public function action_invite(){}

  public function action_invite_submit(){
    if(!$this->validate_post('email'))return;

    $email = $_POST['email'];
    $query = new ParseQuery('Contributor');
    $query->equalTo('email',$email);

    try {
      $contributor = $query->first(TRUE);
      if(empty($contributor)){
        $this->error_redirect('950',array('email'=>$email));
        return;
      };

      if($contributor->get('invited') == TRUE){
        $contributor->set('invited', FALSE);
        $contributor->save(TRUE);
      }

      $contributor->set('invited', TRUE);
      $contributor->save(TRUE);

      $this->redirect('contributor/invite_success?email='.$email);
    } catch (ParseException $ex) {
      $this->error_redirect($ex->getCode());
    }
  }

  public function action_invite_success(){
    $email = isset($_GET['email']) ? $_GET['email'] :'';

    $this->template_body->email = $email;
  }
}