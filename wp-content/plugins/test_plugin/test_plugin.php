<?php
/*
   Plugin Name: デジラインアプリ
   Plugin URI: 
   Description: 試験用のアプリ
   Version: 1.0
   Author: Taka
   Author URI: http://twitter.com/degimonomedia
   License: GPL2
   */


add_action('admin_menu', 'dejimono_CategoryCreatorMenu');

/* 管理画面表示 */
function dejimono_CategoryCreatorMenu()
{
    add_menu_page('Dejimono Plugin', 'デジラインアプリ', 'administrator', __FILE__, 'dejimono_CategorySettingsPage', 'dashicons-buddicons-replies');
}

/* 管理画面表示 */
function dejimono_CategorySettingsPage()
{
    echo 'hello world';
    echo plugin_dir_path(__FILE__);
}

register_activation_hook(__FILE__, 'dejimono_install');
register_uninstall_hook(__FILE__, 'dejimono_delete_data');


/* 初回読み込み時にテーブル作成 */
function dejimono_install()
{
    global $wpdb;

    $table = $wpdb->prefix . 'dejimono_test';
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
function dejimono_delete_data()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'dejimono_test';
    $sql = "DROP TABLE IF EXISTS {$table_name}";
    $wpdb->query($sql);
}


/* デジラインテスト */
function dejimono_app()
{

    wp_enqueue_script('dejimono_app_handle', plugin_dir_url(__FILE__) . '/js/test.js', array('jquery'), false, true);

    ob_start();
?>

<h3>今日の天気</h3>
<div class="weather">
    <ul></ul>
</div>


<?php

    return ob_get_clean();
}

add_shortcode('dejimono_app', 'dejimono_app');