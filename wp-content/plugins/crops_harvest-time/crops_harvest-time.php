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
  const CREDENTIAL_ACTION = self::PLUGIN_ID . '-nonce-action';
  const CREDENTIAL_NAME   = self::PLUGIN_ID . '-nonce-key';
  const PLUGIN_DB_PREFIX  = self::PLUGIN_ID . '_';
  const CONFIG_MENU_SLUG  = self::PLUGIN_ID . '-config';
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
    // wp_optionsのデータをひっぱってくる
    $title = get_option(self::PLUGIN_DB_PREFIX . "_vegetable");
  ?>

<div class="wrap">
    <h1>農作物情報の追加</h1>

    <form action="" method='post' id="my-submenu-form">

        <?php // ②：nonceの設定 
        ?>
        <?php wp_nonce_field(self::CREDENTIAL_ACTION, self::CREDENTIAL_NAME) ?>

        <p>
            <label for="vegetable">野菜　</label>
            <input type=" text" name="vegetable" placeholder="例）野菜" value="<?= $title ?>" />
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

        <p><input type='submit' value='追加' class='button button-primary button-large'></p>

    </form>

</div>
<?php
  }

  /** 設定画面の項目データベースに追加する */
  function add_config()
  {

    // nonceで設定したcredentialのチェック
    if (isset($_POST[self::CREDENTIAL_NAME]) && $_POST[self::CREDENTIAL_NAME]) {
      if (check_admin_referer(self::CREDENTIAL_ACTION, self::CREDENTIAL_NAME)) {

        $title = $_POST['vegetable'];

        update_option(self::PLUGIN_DB_PREFIX . "_vegetable", $title);
        $completed_text = "設定の保存が完了しました。管理画面にログインした状態で、トップページにアクセスし変更が正しく反映されたか確認してください。";

        // 設定画面にリダイレクト
        wp_safe_redirect(menu_page_url(self::CONFIG_MENU_SLUG), false);
      }
    }
  }
}

?>