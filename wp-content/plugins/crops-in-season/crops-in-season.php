<?php
/*
Plugin Name: Crops in Season
Description: 
Version:     1.0
Author:      Kohei Okuda
Author URI:  
License:     GPL2
Crops in Season is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
this plugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Custom List Table With Database Example. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

/**
 * PART 1. Defining Database Table
 * ============================================================================
 * データベーステーブルの定義
 * 
 * http://codex.wordpress.org/Creating_Tables_with_Plugins
 */

/**
 * DB VERSION
 */
global $cis_db_version;
$cltd_example_db_version = '1.0';


/**
 * DB CREATE TABLE
 */
register_activation_hook(__FILE__, 'cis_create_table');

function cis_create_table()
{
    global $wpdb;
    global $cis_db_version;

    $table_name = $wpdb->prefix . 'cis_db';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name tinytext NOT NULL,
        introduction text NOT NULL,
        season tinytext NOT NULL,
        category tinytext NOT NULL,
        farm tinytext NOT NULL,
        farmer tinytext NOT NULL,
        url varchar(55) DEFAULT '' NOT NULL,
        UNIQUE KEY id (id)
    )
    $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    add_option('cis_db_version', $cis_db_version);

    /**
     * [OPTION] DB VERSION UPDATE
     */
    $installed_ver = get_option('cis_db_version');
    if ($installed_ver != $cis_db_version) {
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name tinytext NOT NULL,
            introduction text NOT NULL,
            season tinytext NOT NULL,
            category tinytext NOT NULL,
            farm tinytext NOT NULL,
            farmer tinytext NOT NULL,
            url varchar(55) DEFAULT '' NOT NULL,
            UNIQUE KEY id (id)
        )
        $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        update_option('cis_db_version', $cis_db_version);
    }
}


/**
 * DB INSERT DUMMY DATA
 */
register_activation_hook(__FILE__, 'cis_insert_data');

function cis_insert_data()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'cis_db';

    $wpdb->insert($table_name, array(
        'id'           => '1',
        'name'         => 'ほうれん草',
        'introduction' => 'おひたしにどうぞ。',
        'season'       => '1,11,12',
        'category'     => 'organic',
        'farm'         => '太陽農園',
        'farmer'       => '鈴木'
    ));
    $wpdb->insert($table_name, array(
        'id'           => '2',
        'name'         => '玉ねぎ',
        'introduction' => '辛くないよ！',
        'season'       => '1,2,3,9,10,11,12',
        'category'     => 'organic',
        'farm'         => 'いきいき農園',
        'farmer'       => '信田'
    ));
    $wpdb->insert($table_name, array(
        'id'           => '3',
        'name'         => '大根',
        'introduction' => '煮込み料理にどうぞ。',
        'season'       => '1,2,10,11,12',
        'category'     => 'organic',
        'farm'         => 'もくもくファーム',
        'farmer'       => '田中'
    ));
    $wpdb->insert($table_name, array(
        'id'           => '4',
        'name'         => 'ピーマン',
        'introduction' => '甘いよ！',
        'season'       => '6,7,8,9',
        'category'     => 'organic',
        'farm'         => 'みどり農園',
        'farmer'       => '佐藤'
    ));
    $wpdb->insert($table_name, array(
        'id'           => '5',
        'name'         => 'Carot',
        'introduction' => 'Pony likes!',
        'season'       => '1,2,9,10,11,12',
        'category'     => 'organic',
        'farm'         => 'Delicious Farm',
        'farmer'       => 'John'
    ));
    $wpdb->insert($table_name, array(
        'id'           => '6',
        'name'         => 'レタス',
        'introduction' => 'サラダにどうぞ。',
        'season'       => '1,2,3,4,5,6,7,8,9,10,11,12',
        'category'     => 'organic',
        'farm'         => '太陽農園',
        'farmer'       => '鈴木'
    ));
    $wpdb->insert($table_name, array(
        'id'           => '7',
        'name'         => 'ごぼう',
        'introduction' => '皮の剥き過ぎ注意！',
        'season'       => '11,12',
        'category'     => 'organic',
        'farm'         => 'いきいき農園',
        'farmer'       => '信田'
    ));
    $wpdb->insert($table_name, array(
        'id'           => '8',
        'name'         => 'エンドウ豆',
        'introduction' => 'つまみにどうぞ。',
        'season'       => '3,4,5,6',
        'category'     => 'organic',
        'farm'         => 'もくもくファーム',
        'farmer'       => '田中'
    ));
}


/**
 * Trick to update plugin database, see docs
 */
add_action('plugins_loaded', 'cis_update_db_check');

function cis_update_db_check()
{
    global $cis_db_version;
    if (get_site_option('cis_db_version') != $cis_db_version) {
        cis_insert_data();
    }
}


/**
 * DB DROP TABLE
 */
register_deactivation_hook(__FILE__, 'drop_table');

function drop_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'cis_db';
    $sql = "DROP TABLE IF EXISTS $table_name;";
    $wpdb->query($sql);
    delete_option("cis_db_version");
}


/**
 * PART 2. Defining Table List
 * ============================================================================
 * テーブルリストの定義
 *
 * http://codex.wordpress.org/Class_Reference/WP_List_Table
 * http://wordpress.org/extend/plugins/custom-list-table-example/
 */

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

/**
 * OVERRIDE
 */
class CIS_List_Table extends WP_List_Table
{
    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'crops',
            'plural'   => 'cropses',
        ));
    }


    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }


    function column_name($item)
    {
        $actions = array(
            'edit' => sprintf('<a href="?page=cropses_form&id=%s">%s</a>', $item['id'], __('編集', 'cis_data')),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'], __('削除', 'cis_data')),
        );

        return sprintf(
            '%s %s',
            $item['name'],
            $this->row_actions($actions)
        );
    }


    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }


    function get_columns()
    {
        $columns = array(
            'cb'           => '<input type="checkbox" />',
            'name'         => __('名前', 'cis_data'),
            'introduction' => __('紹介文', 'cis_data'),
            'season'       => __('旬', 'cis_data'),
            'category'     => __('カテゴリ', 'cis_data'),
            'farm'         => __('農園', 'cis_data'),
            'farmer'       => __('農家', 'cis_data')
        );
        return $columns;
    }


    function get_sortable_columns()
    {
        $sortable_columns = array(
            'id'           => array('id', true),
            'name'         => array('name', false),
            'introduction' => array('introduction', false),
            'season'       => array('season', false),
            'category'     => array('category', false),
            'farm'         => array('farm', false),
            'farmer'       => array('farmer', false),
        );
        return $sortable_columns;
    }


    function get_bulk_actions()
    {
        $actions = array(
            'delete' => '削除'
        );
        return $actions;
    }


    function process_bulk_action()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cis_db';

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        }
    }


    function prepare_items()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cis_db';

        $per_page = 5;

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        // table headers
        $this->_column_headers = array($columns, $hidden, $sortable);

        // bulk action
        $this->process_bulk_action();

        // pagination settings
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");

        // query params
        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged'] - 1) * $per_page) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'name';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

        // define $items array
        $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);

        // configure pagination
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));
    }
}

/**
 * PART 3. Admin page
 * ============================================================================
 * 管理画面
 *
 * http://codex.wordpress.org/Administration_Menus
 */

/**
 * ADMIN MENU
 */
add_action('admin_menu', 'cis_admin_menu');

function cis_admin_menu()
{
    add_menu_page(
        __('Crops in Season', 'cis_data'),  // page title
        __('Crops in Season', 'cis_data'),  // menu title
        'activate_plugins',  //capability
        'cropses',  // slug
        'cis_cropses_page_handler',  // callback
        'dashicons-calendar', // icon url（see: https://developer.wordpress.org/resource/dashicons/#awards ）
        // position
    );

    add_submenu_page(
        'cropses',
        __('Cropses', 'cis_data'),
        __('Cropses', 'cis_data'),
        'activate_plugins',
        'cropses',
        'cis_cropses_page_handler'
    );

    add_submenu_page(
        'cropses',
        __('Add new', 'cis_data'),
        __('Add new', 'cis_data'),
        'activate_plugins',
        'cropses_form',
        'cis_cropses_form_page_handler'
    );
}


/**
 * List page handler
 */
function cis_cropses_page_handler()
{
    global $wpdb;

    $table = new CIS_List_Table();
    $table->prepare_items();

    $message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'cis_data'), count((array)$_REQUEST['id'])) . '</p></div>';
    }
?>
    <div class="wrap">

        <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
        <h2>
            <?php _e('Cropses', 'cis_data') ?>
            <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=cropses_form'); ?>">
                <?php _e('Add new', 'cis_data') ?>
            </a>
        </h2>
        <?php echo $message; ?>
        <div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
            <p>農作物情報を元に旬の農作物の紹介ページを作り、ショートコードとして利用できます。
                <br>[cis 〇〇]
                <br>補足：〇〇＝name
            </p>
        </div>

        <form id="cropsess-table" method="GET">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <?php $table->display() ?>
        </form>

    </div>
<?php
}

/**
 * PART 4. FORM for ADD and EDIT
 * ============================================================================
 * 新規追加、編集画面
 *
 * http://codex.wordpress.org/Data_Validation
 * http://codex.wordpress.org/Function_Reference/selected
 */
function cis_cropses_form_page_handler()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'cis_db';

    $message = '';
    $notice = '';

    $default = array(
        'id'           => 0,
        'name'         => '',
        'introduction' => '',
        'season'       => '',
        'category'     => '',
        'farm'         => '',
        'farmer'       => ''
    );

    if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        $item = shortcode_atts($default, $_REQUEST);
        $item_valid = cis_validate_cropses($item);
        if ($item_valid === true) {
            if ($item['id'] == 0) {
                $result = $wpdb->insert($table_name, $item);
                $item['id'] = $wpdb->insert_id;
                if ($result) {
                    $message = __('Item was successfully saved', 'cis_data');
                } else {
                    $notice = __('There was an error while saving item', 'cis_data');
                }
            } else {
                $result = $wpdb->update($table_name, $item, array('id' => $item['id']));
                if ($result) {
                    $message = __('Item was successfully updated', 'cis_data');
                } else {
                    $notice = __('There was an error while updating item', 'cis_data');
                }
            }
        } else {
            $notice = $item_valid;
        }
    } else {
        $item = $default;
        if (isset($_REQUEST['id'])) {
            $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $_REQUEST['id']), ARRAY_A);
            if (!$item) {
                $item = $default;
                $notice = __('Item not found', 'cis_data');
            }
        }
    }

    add_meta_box('cropses_form_meta_box', 'Crops data', 'cis_cropses_form_meta_box_handler', 'crops', 'normal', 'default');

?>
    <div class="wrap">
        <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
        <h2>
            <?php _e('Cropses', 'cis_data') ?>
            <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=cropses'); ?>">
                <?php _e('back to list', 'cis_data') ?>
            </a>
        </h2>

        <?php if (!empty($notice)) : ?>
            <div id="notice" class="error">
                <p><?php echo $notice ?></p>
            </div>
        <?php endif; ?>
        <?php if (!empty($message)) : ?>
            <div id="message" class="updated">
                <p><?php echo $message ?></p>
            </div>
        <?php endif; ?>

        <form id="form" method="POST">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__)) ?>" />
            <input type="hidden" name="id" value="<?php echo $item['id'] ?>" />

            <div class="metabox-holder" id="poststuff">
                <div id="post-body">
                    <div id="post-body-content">
                        <?php do_meta_boxes('crops', 'normal', $item); ?>
                        <input type="submit" value="<?php _e('保存', 'cis_data') ?>" id="submit" class="button-primary" name="submit">
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php
}


function cis_cropses_form_meta_box_handler($item)
{
?>

    <table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
        <tbody>
            <tr class="form-field">
                <th valign="top" scope="row">
                    <label for="name"><?php _e('名前', 'cis_data') ?></label>
                </th>
                <td>
                    <input id="name" name="name" type="text" style="width: 95%" value="<?php echo esc_attr($item['name']) ?>" size="50" class="code" placeholder="<?php _e('農作物', 'cis_data') ?>" required>
                </td>
            </tr>
            <tr class="form-field">
                <th valign="top" scope="row">
                    <label for="introduction"><?php _e('紹介文', 'cis_data') ?></label>
                </th>
                <td>
                    <textarea id="introduction" name="introdution" style="width: 95%" value="<?php echo esc_attr($item['introduction']) ?>" size="50" class="code" placeholder="<?php _e('農作物を紹介します。', 'cis_data') ?>" required></textarea>
                </td>
            </tr>
            <tr class="form-field">
                <th valign="top" scope="row">
                    <label for="season"><?php _e('旬', 'cis_data') ?></label>
                </th>
                <td>
                    <input id="season" name="season" type="text" style="width: 95%" value="<?php echo esc_attr($item['season']) ?>" size="50" class="code" placeholder="<?php _e('旬', 'cis_data') ?>" required>
                </td>
            </tr>
            <tr class="form-field">
                <th valign="top" scope="row">
                    <label for="category"><?php _e('カテゴリ', 'cis_data') ?></label>
                </th>
                <td>
                    <input id="category" name="category" type="text" style="width: 95%" value="<?php echo esc_attr($item['category']) ?>" size="50" class="code" placeholder="<?php _e('カテゴリ', 'cis_data') ?>" required>
                </td>
            </tr>
            <tr class="form-field">
                <th valign="top" scope="row">
                    <label for="farm"><?php _e('農園', 'cis_data') ?></label>
                </th>
                <td>
                    <input id="farm" name="farm" type="text" style="width: 95%" value="<?php echo esc_attr($item['farm']) ?>" size="50" class="code" placeholder="<?php _e('農園', 'cis_data') ?>" required>
                </td>
            </tr>
            <tr class="form-field">
                <th valign="top" scope="row">
                    <label for="farmer"><?php _e('農家', 'cis_data') ?></label>
                </th>
                <td>
                    <input id="farmer" name="farmer" type="text" style="width: 95%" value="<?php echo esc_attr($item['farmer']) ?>" size="50" class="code" placeholder="<?php _e('農家', 'cis_data') ?>" required>
                </td>
            </tr>
        </tbody>
    </table>
    <?php
}


function cis_validate_cropses($item)
{
    $messages = array();

    if (empty($item['name'])) $messages[] = __('Name is required', 'cis_data');
    if (empty($item['introduction'])) $messages[] = __('Introduction is required', 'cis_data');
    if (empty($item['season'])) $messages[] = __('Season is required', 'cis_data');
    if (empty($item['category'])) $messages[] = __('Category is required', 'cis_data');
    if (empty($item['farm'])) $messages[] = __('Farm is required', 'cis_data');
    if (empty($item['farmer'])) $messages[] = __('Farmer is required', 'cis_data');

    if (empty($messages)) return true;
    return implode('<br />', $messages);
}



/**
 * PART 5. Shortcode
 * ============================================================================
 * ショートコード
 *
 * https://codex.wordpress.org/Shortcode_API
 */

/**
 * Shortcode
 */
add_shortcode('cis', 'display_crops');

function display_crops($atts)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'cis_db';
    $query = "SELECT * FROM $table_name WHERE name='$atts[0]' ORDER BY ID LIMIT 40;";
    $results = $wpdb->get_results($query);
    foreach ($results as $row) {
    ?>
        <div class="<?php
                    if ($row->category == "organic") {
                        echo "crops-image-wrapper";
                    } ?>">
            <h3 class="<?php
                        if ($row->category == "organic") {
                            echo "crops-image";
                        } ?>"><?php echo $row->category; ?></h3>

        </div>

        <div class="name">
            <?php echo $row->name; ?>
        </div>

        <div class="introduction">
            <?php echo $row->introduction; ?>
        </div>

        <?php $season_array = explode(',', $row->season); ?>
        <ul class="season">
            <li class="<?php
                        if (in_array("1", $season_array)) {
                            echo "season_mark";
                        } ?>"> 1月</li>
            <li class="<?php
                        if (in_array("2", $season_array)) {
                            echo "season_mark";
                        } ?>"> 2月</li>
            <li class="<?php
                        if (in_array("3", $season_array)) {
                            echo "season_mark";
                        } ?>"> 3月</li>
            <li class="<?php
                        if (in_array("4", $season_array)) {
                            echo "season_mark";
                        } ?>"> 4月</li>
            <li class="<?php
                        if (in_array("5", $season_array)) {
                            echo "season_mark";
                        } ?>"> 5月</li>
            <li class="<?php
                        if (in_array("6", $season_array)) {
                            echo "season_mark";
                        } ?>"> 6月</li>
        </ul>
        <ul class="season">
            <li class="<?php
                        if (in_array("7", $season_array)) {
                            echo "season_mark";
                        } ?>"> 7月</li>
            <li class="<?php
                        if (in_array("8", $season_array)) {
                            echo "season_mark";
                        } ?>"> 8月</li>
            <li class="<?php
                        if (in_array("9", $season_array)) {
                            echo "season_mark";
                        } ?>"> 9月</li>
            <li class="<?php
                        if (in_array("10", $season_array)) {
                            echo "season_mark";
                        } ?>">10月</li>
            <li class="<?php
                        if (in_array("11", $season_array)) {
                            echo "season_mark";
                        } ?>">11月</li>
            <li class="<?php
                        if (in_array("12", $season_array)) {
                            echo "season_mark";
                        } ?>">12月</li>
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


/**
 * Shortcode CSS
 */
add_action('wp_enqueue_scripts', 'addition_style');

function addition_style()
{
    wp_enqueue_style('cis_plugin_style', plugins_url('/style.css', __FILE__));
}


?>