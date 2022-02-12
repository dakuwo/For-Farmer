<?php

class MyCrops
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

class MyCropsInfo
{
    private $id;
    private $crops;
    private $type;

    public function getCrops()
    {
        return $this->crops;
    }

    public function getType()
    {
        return $this->type;
    }

    public function __construct($type, $crops)
    {
        $this->type = $type;
        $this->items = $crops;
    }

    public static function createDmyData()
    {
        return new MyCropsInfo(
            '農作物',
            [
                new MyCrops(1, 'ほうれん草', 'おいしいよ。', '1,11,12', 'organic', '太陽農園', '鈴木'),
                new MyCrops(2, '玉ねぎ', '辛くないよ！', '1,2,3,9,10,11,12', 'organic', 'シャキシャキファーム', '田中'),
                new MyCrops(3, '大根', '煮込み料理にどうぞ。', '1,2,10,11,12', 'organic', 'もくもくファーム', '伊賀'),
                new MyCrops(4, 'ピーマン', '甘いよ。', '6,7,8,9', 'organic', '太陽農園', '鈴木'),
                new MyCrops(5, '人参', 'カレーにどうぞ。', '1,2,9,10,11,12', 'organic', 'もくもくファーム', '佐藤')
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

    public function prepare_crops()
    {
        $info = MyCropsInfo::createDmyData();
        $this->items = $info->getCrops();

        // ソート実験用に農作物を追加
        $idCnt = 5;
        $this->items[] = new MyCrops(++$idCnt, 'レタス', 'サラダにどうぞ。', '1,2,3,4,5,6,7,8,9,10,11,12', 'organic', 'シャキシャキファーム', '田中');
        $this->items[] = new MyCrops(++$idCnt, '白菜', '鍋にどうぞ。', '1,2,10,11,12', 'organic', '太陽農園', '鈴木');
        $this->items[] = new MyCrops(++$idCnt, 'エンドウ豆', 'つまみにどうぞ。', '3,4,5,6', 'organic', '太陽農園', '鈴木');
        $this->items[] = new MyCrops(++$idCnt, 'ごぼう', 'きんぴらにどうぞ', '11,12', 'organic', 'もくもくファーム', '佐藤');


        /*
        // 検索
        $s = isset($_REQUEST['s']) ? (string)$_REQUEST['s'] : '';
        if (!empty($s)) {
            $this->items = array_filter($this->items, function ($item) use ($s) {
                return
                    strpos($item->getName(), $s) ||
                    strpos($item->getDescription(), $s);
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
            'id' => function ($item) {
                return $item->getId();
            },
            'price' => function ($item) {
                return $item->getPrice();
            }
        ];

        $getter = isset($fnames[$orderby]) ? $fnames[$orderby] : null;
        if ($getter) {
            usort(
                $this->items,
                function ($a, $b) use ($getter, $sort, $orderDir) {
                    return $sort($getter($a), $getter($b), $orderDir);
                }
            );
        }
        */


        /*
        // ページネーションを使う場合は設定
        $this->set_pagination_args([
            'total_items' => count($this->items),
            //'total_pages' => 5, //設定してないと、ceil(total_items / per_page)
            'per_page' => 2
        ]);

        // ページ数を取得
        $pageLen = $this->get_pagination_arg('total_pages');

        // 現在のページ($_REQUEST['paged'])を取得、範囲を外れると修正される。
        $paged = $this->get_pagenum();

        $per_page = $this->get_pagination_arg('per_page');


        // ページネーションを独自に計算
        $this->items = array_slice(
            $this->items,
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

    protected function column_default($crops, $name)
    {
        switch ($name) {
            case 'id':
                return (string)(int)$crops->getId();
            case 'name':
                return esc_html($crops->getName());
            case 'introduction':
                return esc_html($crops->getIntroduction());
            case 'season':
                return esc_html($crops->getSeason());
            case 'category':
                return esc_html($crops->getCategory());
            case 'farm':
                return esc_html($crops->getFarm());
            case 'farmer':
                return esc_html($crops->getFarmer());
        }
    }

    protected function column_cb($crops)
    {
        $id = (int)$crops->getId();
        return "<input type=\"checkbox\" name=\"checked[]\" value=\"{$id}\" />";
    }


    protected function _column_description($crops, $classes, $data, $primary)
    {
        $desc = esc_html($crops->getIntroduction());
        return "<td class=\" {$classes}\" {$data}><strong>{$desc}</strong></td>";
    }

    protected function column_name($crops)
    {
        $name = esc_html($crops->getName());
        return "<strong>{$name}</strong>";
    }



    protected function handle_row_actions($crops, $column_name, $primary)
    {
        if ($column_name === $primary) {
            $actions = [
                'edit' => '<a href="/">編集</a>',
                'delete' => '<a href="/">削除</a>'
            ];

            return $this->row_actions($actions);
        }
    }

    protected function column_season($crops)
    {
        $season = esc_html($crops->getSeason());
        return "<strong>{$season}</strong>";
    }

    protected function column_farm($crops)
    {
        $farm = esc_html($crops->getFarm());
        return "<strong>{$farm}</strong>";
    }

    protected function column_farmer($crops)
    {
        $farmer = esc_html($crops->getFarmer());
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

    public function _js_vars()
    {
        echo '<script type="text/javascript">
test = "abcdefg";
</script>';
    }
    */
}
