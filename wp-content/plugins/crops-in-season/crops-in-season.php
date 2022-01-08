<?php

/*
 * Plugin Name:       Crops in Season
 * Version:           1.0
 * Description:       必要情報を入力することで、旬の農作物について紹介します。
 * Author:            dakuwo
 */


/* 管理画面表示 */
add_action('init', 'CropsInSeason::init');

class CropsInSeason
{

  //
  const VERSION           = '1.0.0';
  const PLUGIN_ID         = 'crops-in-season';
  const CONFIG_MENU_SLUG  = self::PLUGIN_ID . '-config';
  const CREDENTIAL_ACTION = self::PLUGIN_ID . '-nonce-action';
  const CREDENTIAL_NAME   = self::PLUGIN_ID . '-nonce-key';
  const PLUGIN_DB_PREFIX  = self::PLUGIN_ID . '_';
  //

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

      // コールバック関数定義
      // add_action('admin_init', [$this, 'add_config']);
      //テーブルの作成
      add_action('admin_init', [$this, 'create_table']);
      //データ追加
      add_action('admin_init', [$this, 'set_crops_data']);
    }
  }

  function set_plugin_menu()
  {
    add_menu_page(
      'Crops in Season', // page title
      'Crops in Season', // menu title
      'manage_options', // capability
      'crops-in-season', // slug
      [$this, 'show_about_plugin'], // callback
      'dashicons-calendar', // icon url（see: https://developer.wordpress.org/resource/dashicons/#awards ）
      65 // position
    );

    add_submenu_page(
      'crops-in-season', // parent slug
      '農作物情報', // page title
      '農作物情報', // menu title
      'manage_options', // capability
      'crops-in-season', // slug
      [$this, 'show_about_plugin'] //callback
    );
  }

  function set_plugin_sub_menu()
  {

    add_submenu_page(
      'crops-in-season', // parent slug
      '新規追加', // page title
      '新規追加', // menu title
      'manage_options', // capability
      'crops-in-season-config', // slug
      [$this, 'show_config_form'] //callback
    );
  }


  /** 農作物情報画面の表示 */
  function show_about_plugin()
  {
?>

<div class="wrap">
    <h1>農作物情報</h1>
    <p>農作物情報を元に旬の農作物の紹介ページを作ります。</p>

    <div>
        <?php
        if (current_user_can('administrator') || current_user_can('editor') || current_user_can('author')) :
          global $wpdb;
          $query = "SELECT * FROM $wpdb->crops_data_table ORDER BY ID LIMIT 40;";
          $results = $wpdb->get_results($query);
          foreach ($results as $row) {
            $id = $row->id;
          }
          echo "農作物：" . $results[1]->crops . nl2br("\n") . "紹介文：" . $results[1]->introduction;
        endif;
        ?>
    </div>
    <div>
        <div>
            <?php
          echo "投稿抜粋：" . nl2br("\n")  . $results[1]->season;
          ?>
        </div>
    </div>

    <?php
  }

  function show_config_form()
  {
    ?>

    <div class="wrap">
        <h1>農作物情報の追加</h1>

        <form action="" method='post' id="my-submenu-form">

            <?php // ②：nonceの設定 
          ?>
            <?php wp_nonce_field(self::CREDENTIAL_ACTION, self::CREDENTIAL_NAME) ?>

            <p>
                <label for="crops">農作物：　</label>
                <input type="text" name="crops" placeholder="例）野菜" value="" />
            </p>

            <p>
                <label for="introduction">紹介文：　</label>
                <textarea id="introduction" name="introduction" placeholder="例）農作物を紹介します。" value="" minlength="0"
                    maxlength="30"></textarea>
            </p>

            <P>
                <label for="season">旬：　</label>
                <input type="checkbox" name="season" value="1"> 1月　
                <input type="checkbox" name="season" value="2"> 2月　
                <input type="checkbox" name="season" value="3"> 3月　
                <input type="checkbox" name="season" value="4"> 4月　
                <input type="checkbox" name="season" value="5"> 5月　
                <input type="checkbox" name="season" value="6"> 6月　
                <input type="checkbox" name="season" value="7"> 7月　
                <input type="checkbox" name="season" value="8"> 8月　
                <input type="checkbox" name="season" value="9"> 9月　
                <input type="checkbox" name="season" value="10"> 10月　
                <input type="checkbox" name="season" value="11"> 11月　
                <input type="checkbox" name="season" value="12"> 12月　
            </P>

            <P>
                <label for="category">カテゴリ表示：　</label>
                <select name="category">
                    <option value="">選択して下さい</option>
                    <option value="null">表示なし</option>
                    <option value="organic">オーガニック</option>
                </select>
            </p>

            <P>
                <label for="crops-image">農作物の画像　</label>
                <input type="file" name="crops-image" accept="image/png, image/jpeg">
            </P>

            <p>
                <label for="farm">農園：　</label>
                <input type="text" name="farm" placeholder="例）○○農園" value="" />
            </p>

            <p>
                <label for="farmer">農家：　</label>
                <input type="text" name="farmer" placeholder="例）栽培者" value="" />
            </p>

            <P>
                <label for="farmer-image">農家の画像　</label>
                <input type="file" name="farmer-image" accept="image/png, image/jpeg">
            </P>

            <p><input type='submit' value='追加' class='button button-primary button-large'></p>

        </form>

    </div>
    <?php
  }

  /** 設定画面の項目データベースに追加する */

  //テーブルの作成
  function create_table()
  {
    global $wpdb;
    global $cropsdata_db_version;

    $cropsdata_db_version = '1.0';

    $table_name = $wpdb->prefix . 'crops_data_table';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    crops tinytext NOT NULL,
    introduction text NOT NULL,
    season tinyint NOT NULL,
    category tinytext NOT NULL,
    farm tinytext NOT NULL,
    farmer tinytext NOT NULL,
    url varchar(55) DEFAULT '' NOT NULL,
    UNIQUE KEY id (id)
  ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    add_option('cropsdata_db_version', $cropsdata_db_version);
  }


  //テーブルを削除する
  function drop_table()
  {
    global $wpdb;

    $table_name = $wpdb->prefix . 'crops_data_table';

    $wpdb->query("DROP TABLE IF EXISTS $table_name");

    delete_option("cropsdata_db_version");
  }


  //データを追加する
  function set_crops_data()
  {
    global $wpdb;

    $crops = $_POST['crops'];
    $introduction = $_POST['introduction'];
    $season = $_POST['season'];
    $category = $_POST['category'];
    $farm = $_POST['farm'];
    $farmer = $_POST['farmer'];

    $table_name = $wpdb->prefix . 'crops_data_table';

    $wpdb->insert(
      $table_name,
      array(
        'crops' => $crops,
        'introduction' => $introduction,
        'season' => $season,
        'category' => $category,
        'farm' => $farm,
        'farmer' => $farmer,
      )
    );
  }

  //データを削除する



}




  ?>