<?php
/**
 * Created by PhpStorm.
 * User: colinleung
 * Date: 11/4/2017
 * Time: 8:45 AM
 */
use Parse\ParseObject;
use Parse\ParseException;

class Controller_Parsetest extends Controller_Parse{
  public function action_index(){
    $object = ParseObject::create("TestObject");

    try{
      $object->set("elephant", "php");
      $object->set("today", new DateTime());
      $object->setArray("mylist", [1, 2, 3]);
      $object->setAssociativeArray(
        "languageTypes", array("php" => "awesome", "ruby" => "wtf")
      );
      $object->save(true);
    }catch (ParseException $ex) {
      echo 'test fail:'.$ex->getCode();
    }

    echo 'test success';
  }
}