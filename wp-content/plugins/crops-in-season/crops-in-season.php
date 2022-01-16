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
//register_activation_hook(__FILE__, 'create_database');

/* deactivate */
register_deactivation_hook(__FILE__, 'drop_database');


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

  static function init()
  {
    return new self();
  }

  function __construct()
  {
    if (is_admin() && is_user_logged_in()) {
      // テーブル作成
      add_action('admin_menu', [$this, 'create_table']);
      // メニュー追加
      add_action('admin_menu', [$this, 'set_plugin_menu']);
      add_action('admin_menu', [$this, 'set_plugin_sub_menu']);
    }
  }


  // Create table when activate
  function create_table()
  {
    global $wpdb;
    global $db_version;

    $db_version = '1.0';

    $table_name = $wpdb->prefix . self::PLUGIN_DB_PREFIX;

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
   id mediumint(9) NOT NULL AUTO_INCREMENT,
   crops tinytext NOT NULL,
   introduction text NOT NULL,
   season tinytext NOT NULL,
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

  // Admin Menu
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

  // Admin Submenu
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
    <p>
        農作物情報を元に旬の農作物の紹介ページを作り、ショートコードとして利用できます。
        <br>
        （例）[crops_data 〇]　〇＝ID番号(半角)
    </p>


    <table>
        <thread>
            <tr>
                <th>ID</th>
                <th>農作物</th>
                <th>紹介文</th>
                <th>旬</th>
                <th>カテゴリ</th>
                <th>農園</th>
                <th>農家</th>
            </tr>
        </thread>

        <?php for ($i = 1; $i <= 50; $i++) { ?>
        <tbody>
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
                    echo $row->season;
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
        </tbody>
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
                <input type="checkbox" name="season[]" value="1"> 1月　
                <input type="checkbox" name="season[]" value="2"> 2月　
                <input type="checkbox" name="season[]" value="3"> 3月　
                <input type="checkbox" name="season[]" value="4"> 4月　
                <input type="checkbox" name="season[]" value="5"> 5月　
                <input type="checkbox" name="season[]" value="6"> 6月　
                <input type="checkbox" name="season[]" value="7"> 7月　
                <input type="checkbox" name="season[]" value="8"> 8月　
                <input type="checkbox" name="season[]" value="9"> 9月　
                <input type="checkbox" name="season[]" value="10"> 10月　
                <input type="checkbox" name="season[]" value="11"> 11月　
                <input type="checkbox" name="season[]" value="12"> 12月　
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
}




// Delete table when deactivate
function drop_database()
{
  global $wpdb;
  $table_name = $wpdb->prefix . 'cis_crops_data';
  $sql = "DROP TABLE IF EXISTS $table_name;";
  $wpdb->query($sql);
  delete_option("db_version");
}


//Push add button
if (isset($_POST['add'])) {

  global $wpdb;

  $crops = $_POST['crops'];
  $introduction = $_POST['introduction'];
  $season = implode(",", $_POST['season']);
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

// Shortcode
add_shortcode('crops_data', 'display_crops_data');

function display_crops_data($atts)
{
  global $wpdb;
  $table_name = $wpdb->prefix . 'cis_crops_data';
  $query = "SELECT * FROM $table_name WHERE id='$atts[0]' ORDER BY ID LIMIT 40;";
  $results = $wpdb->get_results($query);
  foreach ($results as $row) {
    ?>
    <h2 class="crops">
        <?php echo $row->crops; ?>
    </h2>

    <div class="introduction">
        <?php echo $row->introduction; ?>
    </div>

    <?php
      /*if (in_array("1", $row->season)) {
        add_action('wp_enqueue_scripts', 'add_style_jan');
      }*/
      ?>

    <ul class="season_1">
        <li class="jan"> 1月</li>
        <li class="feb"> 2月</li>
        <li class="mar"> 3月</li>
        <li class="apr"> 4月</li>
        <li class="may"> 5月</li>
        <li class="jun"> 6月</li>
    </ul>
    <ul class="season_2">
        <li class="jul"> 7月</li>
        <li class="aug"> 8月</li>
        <li class="sep"> 9月</li>
        <li class="oct">10月</li>
        <li class="nov">11月</li>
        <li class="dec">12月</li>
    </ul>

    <div class="farm">
        <?php echo $row->farm; ?>
    </div>

    <div class="farmer">
        <?php echo $row->farmer; ?>
    </div>

    <?php
  }
}

// CSS
add_action('wp_enqueue_scripts', 'add_style');

function add_style()
{
  wp_enqueue_style('cis_plugin_style', plugins_url('/style.css', __FILE__));
}

function add_style_jan()
{
  wp_enqueue_style('cis_plugin_style_jan', plugins_url('/jan.css', __FILE__));
}
  ?>