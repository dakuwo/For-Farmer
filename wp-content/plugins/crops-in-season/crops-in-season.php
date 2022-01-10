<?php

/*
 * Plugin Name:       Crops in Season
 * Version:           1.0
 * Description:       必要情報を入力することで、旬の農作物について紹介します。
 * Author:            dakuwo
 */



/* initial */
add_action('init', 'CropsInSeason::init');

/* activate */


/* deactivate */
// Delete table when deactivate
function my_plugin_remove_database()
{
  global $wpdb;
  $table_name = $wpdb->prefix . 'cis_crops_data';
  $sql = "DROP TABLE IF EXISTS $table_name;";
  $wpdb->query($sql);
  delete_option("cropsdata_db_version");
}
register_deactivation_hook(__FILE__, 'my_plugin_remove_database');


class CropsInSeason
{
  //
  const VERSION              = '1.0';                                   //self::VERSION
  const PLUGIN_ID            = 'cis';                                   //self::PLUGIN_ID
  const CONFIG_MENU_SLUG     = 'crops-in-season';                       //self::CONFIG_MENU_SLUG
  const CONFIG_SUBMENU_SLUG  = self::CONFIG_MENU_SLUG . '-config';      //self::CONFIG_SUBMENU_SLUG
  const CREDENTIAL_ACTION    = self::PLUGIN_ID . '-nonce-action';       //self::CREDENTIAL_ACTION
  const CREDENTIAL_NAME      = self::PLUGIN_ID . '-nonce-key';          //self::CREDENTIAL_NAME
  const PLUGIN_DB_PREFIX     = self::PLUGIN_ID . '_crops_data';         //self::PLUGIN_DB_PREFIX
  //

  /* イニシャル処理 */
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
      //テーブルの作成
      add_action('admin_init', [$this, 'create_table']);
      //データ追加
      //add_action('admin_init', [$this, 'set_crops_data']);
    }
  }


  function set_plugin_menu()
  {
    add_menu_page(
      'Crops in Season', // page title
      'Crops in Season', // menu title
      'manage_options', // capability
      self::CONFIG_MENU_SLUG, // slug
      [$this, 'show_about_plugin'], // callback
      'dashicons-calendar', // icon url（see: https://developer.wordpress.org/resource/dashicons/#awards ）
      65 // position
    );

    add_submenu_page(
      self::CONFIG_MENU_SLUG, // parent slug
      '農作物情報', // page title
      '農作物情報', // menu title
      'manage_options', // capability
      self::CONFIG_MENU_SLUG, // slug
      [$this, 'show_about_plugin'] //callback
    );
  }

  function set_plugin_sub_menu()
  {

    add_submenu_page(
      self::CONFIG_MENU_SLUG, // parent slug
      '新規追加', // page title
      '新規追加', // menu title
      'manage_options', // capability
      self::CONFIG_SUBMENU_SLUG, // slug
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

    <table>
        <tr>
            <th>ID</th>
            <th>農作物</th>
            <th>紹介文</th>
            <th>カテゴリ</th>
            <th>農園</th>
            <th>農家</th>
        </tr>

        <?php for ($i = 1; $i <= 50; $i++) { ?>
        <tr>
            <td>
                <?php
              if (current_user_can('administrator') || current_user_can('editor') || current_user_can('author')) :
                global $wpdb;
                $table_name = $wpdb->prefix . self::PLUGIN_DB_PREFIX;
                $query = "SELECT * FROM $table_name WHERE id='$i' ORDER BY ID LIMIT 40;";
                $results = $wpdb->get_results($query);
                foreach ($results as $row) {
                  echo $row->id;
                }
              endif;
              ?>
            </td>
            <td>
                <?php
              if (current_user_can('administrator') || current_user_can('editor') || current_user_can('author')) :
                global $wpdb;
                $table_name = $wpdb->prefix . self::PLUGIN_DB_PREFIX;
                $query = "SELECT * FROM $table_name WHERE id='$i' ORDER BY ID LIMIT 40;";
                $results = $wpdb->get_results($query);
                foreach ($results as $row) {
                  echo $row->crops;
                }
              endif;
              ?>
            </td>
            <td>
                <?php
              if (current_user_can('administrator') || current_user_can('editor') || current_user_can('author')) :
                global $wpdb;
                $table_name = $wpdb->prefix . self::PLUGIN_DB_PREFIX;
                $query = "SELECT * FROM $table_name WHERE id='$i' ORDER BY ID LIMIT 40;";
                $results = $wpdb->get_results($query);
                foreach ($results as $row) {
                  echo $row->introduction;
                }
              endif;
              ?>
            </td>
            <td>
                <?php
              if (current_user_can('administrator') || current_user_can('editor') || current_user_can('author')) :
                global $wpdb;
                $table_name = $wpdb->prefix . self::PLUGIN_DB_PREFIX;
                $query = "SELECT * FROM $table_name WHERE id='$i' ORDER BY ID LIMIT 40;";
                $results = $wpdb->get_results($query);
                foreach ($results as $row) {
                  echo $row->category;
                }
              endif;
              ?>
            </td>
            <td>
                <?php
              if (current_user_can('administrator') || current_user_can('editor') || current_user_can('author')) :
                global $wpdb;
                $table_name = $wpdb->prefix . self::PLUGIN_DB_PREFIX;
                $query = "SELECT * FROM $table_name WHERE id='$i' ORDER BY ID LIMIT 40;";
                $results = $wpdb->get_results($query);
                foreach ($results as $row) {
                  echo $row->farm;
                }
              endif;
              ?>
            </td>
            <td>
                <?php
              if (current_user_can('administrator') || current_user_can('editor') || current_user_can('author')) :
                global $wpdb;
                $table_name = $wpdb->prefix . self::PLUGIN_DB_PREFIX;
                $query = "SELECT * FROM $table_name WHERE id='$i' ORDER BY ID LIMIT 40;";
                $results = $wpdb->get_results($query);
                foreach ($results as $row) {
                  echo $row->farmer;
                }
              endif;
              ?>
            </td>
        </tr>
        <?php } ?>
    </table>

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

            <p><input type='submit' name='add' value='追加' class='button button-primary button-large'></p>

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

    $db_version = self::VERSION;

    $table_name = $wpdb->prefix . self::PLUGIN_DB_PREFIX;

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

    add_option('db_version', $db_version);
  }
}

//追加ボタン後動作
if (isset($_POST['add'])) {

  global $wpdb;

  $crops = $_POST['crops'];
  $introduction = $_POST['introduction'];
  $season = $_POST['season'];
  $category = $_POST['category'];
  $farm = $_POST['farm'];
  $farmer = $_POST['farmer'];

  $table_name = $wpdb->prefix . 'cis_crops_data';

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




  ?>