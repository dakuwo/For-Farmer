<?php
/*
Plugin Name: Custom Meta Table
Plugin URI: http://www.webopixel.net/wordpress/637.html
Description: カスタムフィールドの値をオリジナルのテーブル（DB）に保存する
Author: k.ishiwata
Version: 0.1
Author URI: http://www.webopixel.net/
*/
class CustomMetaTable
{
    //プラグインのテーブル名
    var $table_name;

    public function __construct()
    {
        global $wpdb;
        // 接頭辞（wp_）を付けてテーブル名を設定
        $this->table_name = $wpdb->prefix . 'ex_meta';
        // プラグイン有効かしたとき実行
        register_activation_hook(__FILE__, array($this, 'cmt_activate'));
        // カスタムフィールドの作成
        add_action('add_meta_boxes', array($this, 'ex_metabox'));
        add_action('save_post', array($this, 'save_meta'));
        add_action('delete_post', array($this, 'dalete_meta'));
    }

    function cmt_activate()
    {
        global $wpdb;

        $cmt_db_version = '1.0';
        $installed_ver = get_option('cmt_meta_version');
        // テーブルのバージョンが違ったら作成
        if ($installed_ver != $cmt_db_version) {
            $sql = "CREATE TABLE " . $this->table_name . " (
          meta_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
          post_id bigint(20) UNSIGNED DEFAULT '0' NOT NULL,
          item_name text,
          price int(11),
          UNIQUE KEY meta_id (meta_id)
        )
        CHARACTER SET 'utf8';";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            update_option('cmt_meta_version', $cmt_db_version);
        }
    }

    function ex_metabox($post)
    {
        add_meta_box(
            'exmeta_sectionid',
            'その他の項目',
            array($this, 'ex_meta_html'),
            'post'
        );
    }
    function ex_meta_html()
    {
        wp_nonce_field(plugin_basename(__FILE__), $this->table_name);
        global $post;
        global $wpdb;

        $get_meta = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM
        " . $this->table_name . " WHERE
        post_id = %d",
                $post->ID
            )
        );
        $get_meta = isset($get_meta[0]) ? $get_meta[0] : null;
        $item_name = isset($get_meta->item_name) ? $get_meta->item_name : null;
        $price = isset($get_meta->price) ? $get_meta->price : null;
?>
        <div>
            <table>
                <tr>
                    <th>商品名</th>
                    <td><input name="item_name" value="<?php echo $item_name ?>" /></td>
                </tr>
                <tr>
                    <th>価格</th>
                    <td><input name="price" value="<?php echo $price ?>" /></td>
                </tr>
            </table>
        </div>
<?php
    }

    function save_meta($post_id)
    {
        if (!isset($_POST[$this->table_name])) return;

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)  return;
        if (!wp_verify_nonce($_POST[$this->table_name], plugin_basename(__FILE__)))  return;

        global $wpdb;
        global $post;

        //リビジョンを残さない
        if ($post->ID != $post_id) return;

        $temp_item_name = isset($_POST['item_name']) ? $_POST['item_name'] : null;
        $temp_price = isset($_POST['price']) ? $_POST['price'] : null;

        //保存するために配列にする
        $set_arr = array(
            'item_name' => $temp_item_name,
            'price' => $temp_price
        );

        $get_id = $wpdb->get_var(
            $wpdb->prepare("SELECT post_id FROM
                  " . $this->table_name . " WHERE 
                  post_id = %d", $post_id)
        );
        //レコードがなかったら新規追加あったら更新
        if ($get_id) {
            $wpdb->update($this->table_name, $set_arr, array('post_id' => $post_id));
        } else {
            $set_arr['post_id'] = $post_id;
            $wpdb->insert($this->table_name, $set_arr);
        }
        $wpdb->show_errors();
    }

    function dalete_meta($post_id)
    {
        global $wpdb;
        $wpdb->query($wpdb->prepare("DELETE FROM $this->table_name WHERE post_id = %d", $post_id));
    }

    function get_meta($post_id)
    {
        if (!is_numeric($post_id)) return;
        global $wpdb;
        $get_meta = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM
        " . $this->table_name . " WHERE
        post_id = %d",
                $post_id
            )
        );
        return isset($get_meta[0]) ? $get_meta[0] : null;
    }
}
$exmeta = new CustomMetaTable;
