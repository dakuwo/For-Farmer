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


/* 管理画面表示 */
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


  /*register_activation_hook (__FILE__, 'crops_harvesttime_install');
  register_uninstall_hook ( __FILE__, 'crops_hrvesttime_delete_data' );

  /* 初回読み込み時にテーブル作成 */
  function crops_harvesttime_install()
  {
    global $wpdb;

    $table = $wpdb->prefix . 'crops_test';
    $charset_collate = $wpdb->get_charset_collate();

    if ($wpdb->get_var("show tables like '$table'") != $table) {

      $sql = "CREATE TABLE  {$table} (
              query_num int, 
              file_name VARCHAR(400),
              item1 VARCHAR(30),
              item2 VARCHAR(30),
              item3 VARCHAR(30)
              ) $charset_collate;";

      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);
    }
  }

  /* プラグイン削除時にはテーブルを削除 */
  function crops_harvesttime_delete_data()
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'crops_test';
    $sql = "DROP TABLE IF EXISTS {$table_name}";
    $wpdb->query($sql);
  }



  /** 農作物情報画面の表示 */
  function show_about_plugin()
  {
?>

<div class="wrap">
    <h1>農作物情報</h1>
    <p>農作物の情報を入力することで、農作物の収穫時期をカレンダー形式で表示します。</p>

    <form action="" method='post' id="menu-form">

        <p>
            <label for="vegetable">野菜　</label>
            <input type=" text" name="vegetable" placeholder="例）野菜" value="" />
        </p>

        <P>
            <label for="harvest-time">収穫期間　</label>
            <select name="harvest-time">
                <option value="">収穫期間を選択して下さい</option>
                <option value="2W">2 Week</option>
                <option value="1M">1 Month</option>
                <option value="3M">3 Month</option>
                <option value="6M">6 Month</option>
            </select>
        </p>

        <P>
            <label for="recommendation">オススメ表示　</label>
            <input type="radio" name="recommendation" value="なし" checked> なし
            <input type="radio" name="recommendation" value="あり"> あり
        </P>

        <P>
            <label for="vegetable-image">野菜の画像　</label>
            <input type="file" name="vegetable-image" accept="image/png, image/jpeg">
        </P>

        <p><input type='submit' value='保存' class='button button-primary button-large'></p>

    </form>
</div>

<?php
  }
  function show_config_form()
  {
  ?>
<h1>カスタムバナーの設定</h1>

<?php
  }
}
?>