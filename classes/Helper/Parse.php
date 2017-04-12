<?php
/**
 * Created by PhpStorm.
 * User: colinleung
 * Date: 12/4/2017
 * Time: 4:23 PM
 */
use Parse\ParseClient;
use Parse\ParseUser;


class Helper_Parse{
  private static $initialized = FALSE;
  public static $current_user;

  public static function initialize(){
    if(self::$initialized)return;

    $config = Kohana::$config->load('site')->get('parse');
    ParseClient::initialize( $config['id'], '', $config['master'] );
    ParseClient::setServerURL($config['url'], $config['mount']);
    self::$current_user =  ParseUser::getCurrentUser();

    self::$initialized = TRUE;
  }
}