<?php
/*
  Plugin Name: 農作物の収穫時期表示
  Plugin URI:
  Description: 必要情報を入力することで、農作物の収穫時期をカレンダー形式で表示することができる
  Version: 1.0.0
  Author: dakuwo
  Author URI: https://github.com/dakuwo/For-Farmer/tree/main/wp-content/plugins/crops_harvest-time
  License: GPLv2
 */

?>


<?php
add_action('init', 'CropsData::init');

class CropsData
{
  static function init()
  {
    return new self();
  }

  function __construct()
  {
    if (is_admin() && is_user_logged_in()) {
      // メニュー追加
      add_action('admin_menu', [$this, 'set_plugin_menu']);
      add_action('admin_menu', [$this, 'set_plugin_sub_menu']);
    }
  }

  function set_plugin_menu()
  {
    add_menu_page(
      '農作物の収穫時期',           /* ページタイトル*/
      '農作物の収穫時期',           /* メニュータイトル */
      'manage_options',         /* 権限 */
      'crops_harvest-time',    /* ページを開いたときのURL */
      [$this, 'show_about_plugin'],       /* メニューに紐づく画面を描画するcallback関数 */
      'dashicons-format-gallery', /* アイコン see: https://developer.wordpress.org/resource/dashicons/#awards */
      99                          /* 表示位置のオフセット */
    );
  }
  function set_plugin_sub_menu()
  {

    add_submenu_page(
      'crops_harvest-time',  /* 親メニューのslug */
      '設定',   /* ページタイトル */
      '設定',   /* メニュータイトル */
      'manage_options',     /* 権限 */
      'crops_harvesrst-time-config',    /* ページを開いたときのURL */
      [$this, 'show_config_form']    /* メニューに紐づく画面を描画するcallback関数 */
    );
  }
} // end of class

?>