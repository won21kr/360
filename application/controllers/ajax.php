<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Ajax extends Site_controller {

  public function __construct () {
    parent::__construct ();

    if (!$this->input->is_ajax_request ())
      return show_404 ();
  }

  public function pv () {
    if (!(($token = OAInput::post ('token')) && ($class = OAInput::post ('class')) && class_exists ($class) && in_array ($class, array ('Picture'))))
      return show_404 ();

    if (!($obj = $class::find_by_token ($token, array ('select' => 'id, pv'))))
      return $this->output_json (array ('status' => false));

    $obj->pv += 1;

    $update = $class::transaction (function () use ($obj) {
      return $obj->save ();
    });

    if (!$update)
      return $this->output_json (array ('status' => true, 'pv' => $obj->pv - 1));

    return $this->output_json (array ('status' => true, 'pv' => $obj->pv));
  }
}
