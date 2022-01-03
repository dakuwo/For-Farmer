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
add_action('init', 'CropsHarvestTime::init');

class CropsHarvestTime
{

  //
  const VERSION           = '1.0.0';
  const PLUGIN_ID         = 'crops_harvest-time';
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
      add_action('admin_init', [$this, 'add_config']);
      //テーブルの作成
      add_action('admin_init', [$this, 'create_table']);
      //データ追加
      add_action('admin_init', [$this, 'set_crops_data']);
    }
  }

  function set_plugin_menu()
  {
    add_menu_page(
      '農作物の収穫時期', // page title
      '農作物の収穫時期', // menu title
      'manage_options', // capability
      'crops_harvest-time', // slug
      [$this, 'show_about_plugin'], // callback
      'dashicons-calendar', // icon url（see: https://developer.wordpress.org/resource/dashicons/#awards ）
      65 // position
    );

    add_submenu_page(
      'crops_harvest-time', // parent slug
      '農作物情報', // page title
      '農作物情報', // menu title
      'manage_options', // capability
      'crops_harvest-time', // slug
      [$this, 'show_about_plugin'] //callback
    );
  }

  function set_plugin_sub_menu()
  {

    add_submenu_page(
      'crops_harvest-time', // parent slug
      '新規追加', // page title
      '新規追加', // menu title
      'manage_options', // capability
      'crops_harvesrst-time-config', // slug
      [$this, 'show_config_form'] //callback
    );
  }


  /** 農作物情報画面の表示 */
  function show_about_plugin()
  {
?>

<div class="wrap">
    <h1>農作物情報</h1>
    <p>農作物情報を元に収穫時期をカレンダー形式で表示します。</p>

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
            <label for="vegetable">野菜　</label>
            <input type="text" name="vegetable" placeholder="例）野菜" value="" />
        </p>

        <p>
            <label for="harveststart">収穫開始日　</label>
            <input type="date" name="harveststart" placeholder="年/月/日" value="" />
        </p>

        <P>
            <label for="harvesttime">収穫期間　</label>
            <select name="harvesttime">
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
    global $jal_db_version;

    $table_name = $wpdb->prefix . 'crops_data';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    vegetable tinytext NOT NULL,
    harveststart date NOT NULL,
    harvesttime time NOT NULL,
    recommendation text NOT NULL,
    url varchar(55) DEFAULT '' NOT NULL,
    UNIQUE KEY id (id)
  ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    add_option('jal_db_version', $jal_db_version);
  }

  //データを追加する
  function set_crops_data()
  {
    global $wpdb;

    $crops = $_POST['vegetable'];
    $harveststart = $_POST['harveststart'];
    $harvesttime = $_POST['harvesttime'];
    $recommendation = $_POST['recommendation'];

    $table_name = $wpdb->prefix . 'crops_data';

    $wpdb->insert(
      $table_name,
      array(
        'vegetable' => $crops,
        'harveststart' => $harveststart,
        'harvesttime' => $harvesttime,
        'recommendation' => $recommendation,
      )
    );
  }
}


?>