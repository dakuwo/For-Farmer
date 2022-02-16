<?php
/*
 * Plugin Name: Crops in Season
 * Description: An example of how to use the WP_List_Table class to display data in your WordPress Admin area
 * Plugin URI: 
 * Author: Kohei Okuda
 * Author URI: 
 * Version: 1.0
 * License: GPL2
 */

// activate
register_activation_hook(__FILE__, 'create_data_table');

// deactivate
register_deactivation_hook(__FILE__, 'drop_data_table');

// Create table when activate
function create_data_table()
{
    global $wpdb;
    global $db_version;

    $db_version = '1.0';

    $table_name = $wpdb->prefix . 'cis_data';

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
        ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    add_option('db_version', $db_version);
}


// Delete table when deactivate
function drop_data_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'cis_data';
    $sql = "DROP TABLE IF EXISTS $table_name;";
    $wpdb->query($sql);
    delete_option("db_version");
}



if (is_admin()) {
    new CIS_WP_List_Table();
}


//Push add button
if (isset($_POST['addition'])) {

    global $wpdb;

    $name = $_POST['name'];
    $introduction = $_POST['introduction'];
    $season = implode(",", $_POST['season']);
    $category = $_POST['category'];
    $farm = $_POST['farm'];
    $farmer = $_POST['farmer'];

    $table_name = $wpdb->prefix . 'cis_data';

    $wpdb->insert(
        $table_name,
        array(
            'name' => $name,
            'introduction' => $introduction,
            'season' => $season,
            'category' => $category,
            'farm' => $farm,
            'farmer' => $farmer,
        )
    );
}

/**
 * Paulund_Wp_List_Table class will create the page to load the table
 */
class CIS_WP_List_Table
{
    /**
     * Constructor will create the menu item
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_menu_cis_list_table_page']);
    }


    /**
     * Menu item will allow us to load the page to display the table
     */
    public function add_menu_cis_list_table_page()
    {
        add_menu_page(
            'Crops in Season', // page title
            'Crops in Season', // menu title
            'manage_options', // capability
            'cis-list', // slug
            [$this, 'list_table_page'], // callback
            'dashicons-calendar', // icon url（see: https://developer.wordpress.org/resource/dashicons/#awards ）
            // position
        );

        add_submenu_page(
            'cis-list', // parent slug
            '農作物一覧', // page title
            '農作物一覧', // menu title
            'manage_options', // capability
            'cis-list', // slug
            [$this, 'list_table_page'] //callback
        );

        add_submenu_page(
            'cis-list', // parent slug
            '新規追加', // page title
            '新規追加', // menu title
            'manage_options', // capability
            'cis-list-add', // slug
            [$this, 'list_add_page'] //callback
        );

        add_submenu_page(
            '', // parent slug
            '編集', // page title
            '編集', // menu title
            'manage_options', // capability
            'cis-list-edit', // slug
            [$this, 'list_edit_page'] //callback
        );
    }

    /**
     * Display the list table page
     *
     * @return Void
     */
    public function list_table_page()
    {
        $CISListTable = new CIS_List_Table();
        $CISListTable->prepare_items();
?>
        <div class="wrap">
            <div id="icon-users" class="icon32"></div>
            <h2>農作物一覧</h2>
            <h3>Shotecode</h3>
            <p>農作物情報を元に旬の農作物の紹介ページを作り、ショートコードとして利用できます。
                <br>[cis 〇]
                <br>補足：〇＝ID番号(半角)
            </p>
            <?php $CISListTable->display(); ?>
        </div>
    <?php
    }

    public function list_add_page()
    {
    ?>

        <div class="wrap">
            <h2>新規追加</h2>

            <form action="" method='post' id="">

                <?php // ②：nonceの設定 
                ?>
                <?php wp_nonce_field('cis-nonce-action', 'cis-nonce-key') ?>

                <p>
                    <label for="name">Name：　</label>
                    <input type="text" name="name" placeholder="例）野菜" value="" />
                </p>

                <p>
                    <label for="introduction">Introduction：　</label>
                    <textarea id="introduction" name="introduction" placeholder="例）農作物を紹介します。" value="" minlength="0" maxlength="30"></textarea>
                </p>

                <P>
                    <label for="season">Season：　</label>
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
                    <label for="category">Category：　</label>
                    <select name="category">
                        <option value="">選択して下さい</option>
                        <option value="null">表示なし</option>
                        <option value="organic">オーガニック</option>
                    </select>
                </p>

                <P>
                    <label for="crops-image">crops's Image　</label>
                    <input type="file" name="crops-image" accept="image/png, image/jpeg">
                </P>

                <p>
                    <label for="farm">Farm：　</label>
                    <input type="text" name="farm" placeholder="例）○○農園" value="" />
                </p>

                <p>
                    <label for="farmer">Farmer：　</label>
                    <input type="text" name="farmer" placeholder="例）栽培者" value="" />
                </p>

                <P>
                    <label for="farmer-image">Farmer's Image　</label>
                    <input type="file" name="farmer-image" accept="image/png, image/jpeg">
                </P>

                <p><input type='submit' name='addition' value='Addition' class='button button-primary button-large'></p>

            </form>

        </div>
    <?php
    }

    public function list_edit_page()
    {
    ?>

        <div class="wrap">
            <h2>編集</h2>

            <form action="" method='post' id="">

                <?php // ②：nonceの設定 
                ?>
                <?php wp_nonce_field('cis-nonce-action', 'cis-nonce-key') ?>

                <p>
                    <label for="name">Name：　</label>
                    <input type="text" name="name" placeholder="例）野菜" value="" />
                </p>

                <p>
                    <label for="introduction">Introduction：　</label>
                    <textarea id="introduction" name="introduction" placeholder="例）農作物を紹介します。" value="" minlength="0" maxlength="30"></textarea>
                </p>

                <P>
                    <label for="season">Season：　</label>
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
                    <label for="category">Category：　</label>
                    <select name="category">
                        <option value="">選択して下さい</option>
                        <option value="null">表示なし</option>
                        <option value="organic">オーガニック</option>
                    </select>
                </p>

                <P>
                    <label for="crops-image">crops's Image　</label>
                    <input type="file" name="crops-image" accept="image/png, image/jpeg">
                </P>

                <p>
                    <label for="farm">Farm：　</label>
                    <input type="text" name="farm" placeholder="例）○○農園" value="" />
                </p>

                <p>
                    <label for="farmer">Farmer：　</label>
                    <input type="text" name="farmer" placeholder="例）栽培者" value="" />
                </p>

                <P>
                    <label for="farmer-image">Farmer's Image　</label>
                    <input type="file" name="farmer-image" accept="image/png, image/jpeg">
                </P>

                <p><input type='submit' name='addition' value='Addition' class='button button-primary button-large'></p>

            </form>

        </div>
    <?php
    }
}

// WP_List_Table is not loaded automatically so we need to load it in our application
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class CIS_List_Table extends WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();
        usort($data, array(&$this, 'sort_data'));

        $perPage = 5;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args(array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ));

        $data = array_slice($data, (($currentPage - 1) * $perPage), $perPage);

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'id'           => 'ID',
            'name'         => 'Name',
            'introduction' => 'Introduction',
            'season'       => 'Season',
            'category'     => 'Category',
            'farm'         => 'Farm',
            'farmer'       => 'Farmer'
        );

        return $columns;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array('id' => array('id', false));
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function handle_row_actions($item, $column_name, $primary)
    {
        if ($column_name === $primary) {
            $actions = [
                'edit' => '<a href="/wp-admin/admin.php?page=cis-list-edit">編集</a>',
                'delete' => '<a href="/">削除</a>'
            ];

            return $this->row_actions($actions);
        }
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {
        $data = array();

        for ($i = 1; $i <= 50; $i++) {
            if (current_user_can('administrator') || current_user_can('editor') || current_user_can('author')) :
                global $wpdb;
                $table_name = $wpdb->prefix . 'cis_data';
                $query = "SELECT * FROM $table_name WHERE id='$i' ORDER BY ID LIMIT 40;";
                $results = $wpdb->get_results($query);
                foreach ($results as $row) {
                    $data[] = array(
                        'id'           => $i,
                        'name'         => $row->name,
                        'introduction' => $row->introduction,
                        'season'       => $row->season,
                        'category'     => $row->category,
                        'farm'         => $row->farm,
                        'farmer'       => $row->farmer
                    );
                }
            endif;
        }
        return $data;
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'id':
            case 'name':
            case 'introduction':
            case 'season':
            case 'category':
            case 'farm':
            case 'farmer':
                return $item[$column_name];

            default:
                return print_r($item, true);
        }
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data($a, $b)
    {
        // Set defaults
        $orderby = 'name';
        $order = 'asc';

        // If orderby is set, use this as the sort column
        if (!empty($_GET['orderby'])) {
            $orderby = $_GET['orderby'];
        }

        // If order is set use this as the order
        if (!empty($_GET['order'])) {
            $order = $_GET['order'];
        }


        $result = strcmp($a[$orderby], $b[$orderby]);

        if ($order === 'asc') {
            return $result;
        }

        return -$result;
    }
}

// Shortcode
add_shortcode('cis', 'display_crops_data');

function display_crops_data($atts)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'cis_data';
    $query = "SELECT * FROM $table_name WHERE id='$atts[0]' ORDER BY ID LIMIT 40;";
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

// Shortcode CSS
add_action('wp_enqueue_scripts', 'addition_style');

function addition_style()
{
    wp_enqueue_style('cis_plugin_style', plugins_url('/style.css', __FILE__));
}


?>