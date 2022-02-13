<?php
class MyItem
{
    private $id;
    private $name;
    private $introduction;
    private $season;
    private $category;
    private $farm;
    private $farmer;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getIntroduction()
    {
        return $this->introduction;
    }

    public function getSeason()
    {
        return $this->season;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getFarm()
    {
        return $this->farm;
    }

    public function getFarmer()
    {
        return $this->farmer;
    }

    public function __construct($id, $name, $introduction, $season, $category, $farm, $farmer)
    {
        $this->id = $id;
        $this->name = $name;
        $this->introduction = $introduction;
        $this->season = $season;
        $this->category = $category;
        $this->farm = $farm;
        $this->farmer = $farmer;
    }
}

class MyItemInfo
{
    private $id;
    private $items;
    private $type;

    public function getItems()
    {
        return $this->items;
    }

    public function getType()
    {
        return $this->type;
    }

    public function __construct($type, $items)
    {
        $this->type = $type;
        $this->items = $items;
    }

    public static function createDmyData()
    {
        return new MyItemInfo(
            '農作物',
            [
                new MyItem(1, 'ほうれん草', 'おいしいよ。', '1,11,12', 'organic', '太陽農園', '鈴木'),
                new MyItem(2, '玉ねぎ', '辛くないよ！', '1,2,3,9,10,11,12', 'organic', 'シャキシャキファーム', '田中'),
                new MyItem(3, '大根', '煮込み料理にどうぞ。', '1,2,10,11,12', 'organic', 'もくもくファーム', '伊賀'),
                new MyItem(4, 'ピーマン', '甘いよ。', '6,7,8,9', 'organic', '太陽農園', '鈴木'),
                new MyItem(5, '人参', 'カレーにどうぞ。', '1,2,9,10,11,12', 'organic', 'もくもくファーム', '佐藤')
            ]
        );
    }
}


class MyListTable extends WP_List_Table
{
    public function __construct()
    {
        parent::__construct(
            [
                'ajax' => true
            ]
        );
    }

    public function prepare_items()
    {
        $info = MyItemInfo::createDmyData();
        $this->crops = $info->getItems();

        // ソート実験用に農作物を追加
        $idCnt = 5;
        $this->items[] = new MyItem(++$idCnt, 'レタス', 'サラダにどうぞ。', '1,2,3,4,5,6,7,8,9,10,11,12', 'organic', 'シャキシャキファーム', '田中');
        $this->items[] = new MyItem(++$idCnt, '白菜', '鍋にどうぞ。', '1,2,10,11,12', 'organic', '太陽農園', '鈴木');
        $this->items[] = new MyItem(++$idCnt, 'エンドウ豆', 'つまみにどうぞ。', '3,4,5,6', 'organic', '太陽農園', '鈴木');
        $this->items[] = new MyItem(++$idCnt, 'ごぼう', 'きんぴらにどうぞ', '11,12', 'organic', 'もくもくファーム', '佐藤');


        /*
        // 検索
        $s = isset($_REQUEST['s']) ? (string)$_REQUEST['s'] : '';
        if (!empty($s)) {
            $this->cropss = array_filter($this->cropss, function ($crops) use ($s) {
                return
                    strpos($crops->getName(), $s) ||
                    strpos($crops->getDescription(), $s);
            });
        }


        // ソート(数値のみ対応)
        $sort = function (int $a, int $b, int $bigA) {
            if ($a === $b) return 0;
            return $a > $b ? $bigA : -$bigA; //$bigAが1なら昇順、-1なら降順
        };

        $orderby = isset($_GET['orderby']) ? (string)$_GET['orderby'] : '';
        $order = isset($_GET['order']) ? (string)$_GET['order'] : '';
        $orderDir = $order === 'asc' ? 1 : -1;

        $fnames = [
            'id' => function ($crops) {
                return $crops->getId();
            },
            'price' => function ($crops) {
                return $crops->getPrice();
            }
        ];

        $getter = isset($fnames[$orderby]) ? $fnames[$orderby] : null;
        if ($getter) {
            usort(
                $this->crops,
                function ($a, $b) use ($getter, $sort, $orderDir) {
                    return $sort($getter($a), $getter($b), $orderDir);
                }
            );
        }
        */


        /*
        // ページネーションを使う場合は設定
        $this->set_pagination_args([
            'total_crops' => count($this->crops),
            //'total_pages' => 5, //設定してないと、ceil(total_crops / per_page)
            'per_page' => 2
        ]);

        // ページ数を取得
        $pageLen = $this->get_pagination_arg('total_pages');

        // 現在のページ($_REQUEST['paged'])を取得、範囲を外れると修正される。
        $paged = $this->get_pagenum();

        $per_page = $this->get_pagination_arg('per_page');


        // ページネーションを独自に計算
        $this->crops = array_slice(
            $this->crops,
            $per_page * ($paged - 1),
            $per_page
        );
    */
    }


    public function get_columns()
    {
        return [
            'cb' => 'チェックボックス',
            'id' => 'ID',
            'name' => '名前',
            'introduction' => '紹介文',
            'season' => '旬',
            'category' => 'カテゴリ',
            'farm' => '農園',
            'farmer' => '農家'
        ];
    }

    protected function column_default($item, $name)
    {
        switch ($name) {
            case 'id':
                return (string)(int)$item->getId();
            case 'name':
                return esc_html($item->getName());
            case 'introduction':
                return esc_html($item->getIntroduction());
            case 'season':
                return esc_html($item->getSeason());
            case 'category':
                return esc_html($item->getCategory());
            case 'farm':
                return esc_html($item->getFarm());
            case 'farmer':
                return esc_html($item->getFarmer());
        }
    }

    protected function column_cb($item)
    {
        $id = (int)$item->getId();
        return "<input type=\"checkbox\" name=\"checked[]\" value=\"{$id}\" />";
    }


    protected function _column_description($item, $classes, $data, $primary)
    {
        $desc = esc_html($item->getIntroduction());
        return "<td class=\" {$classes}\" {$data}><strong>{$desc}</strong></td>";
    }

    protected function column_name($item)
    {
        $name = esc_html($item->getName());
        return "<strong>{$name}</strong>";
    }



    protected function handle_row_actions($item, $column_name, $primary)
    {
        if ($column_name === $primary) {
            $actions = [
                'edit' => '<a href="/">編集</a>',
                'delete' => '<a href="/">削除</a>'
            ];

            return $this->row_actions($actions);
        }
    }

    protected function column_season($item)
    {
        $season = esc_html($item->getSeason());
        return "<strong>{$season}</strong>";
    }

    protected function column_farm($item)
    {
        $farm = esc_html($item->getFarm());
        return "<strong>{$farm}</strong>";
    }

    protected function column_farmer($item)
    {
        $farmer = esc_html($item->getFarmer());
        return "<strong>{$farmer}</strong>";
    }


    /**
     * bulk_action-{ScreenID} でフィルタ登録されている。
     */
    /*
    protected function get_bulk_actions()
    {
        return [
            'delete' => 'アイテムを削除する',
            'priceup' => '1G値上げする',
            'pricedown' => '1G値下げする'
        ];
    }

    protected function extra_tablenav($witch)
    {
        echo "<div class=\"alignleft actions bulkactions\">←bulk　[間だよ！] ページネーション→</div>";
    }

    protected function get_views()
    {
        return [
            'home' => '<a href="/">ホームへGo</a>',
            'sort' => '<a href="?page=mylist&orderby=price&order=desc">高い順！</a>'
        ];
    }
*/
    public function _js_vars()
    {
        echo '<script type="text/javascript">
test = "abcdefg";
</script>';
    }
}
