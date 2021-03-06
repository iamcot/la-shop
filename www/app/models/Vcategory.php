<?php
class Vcategory extends Eloquent
{
    protected $table = 'v_categories';

    public static function getCategoriesTree($id = 0, $path = '', $last_path = '',$force = false)
    {
        $categories_array = array();

        if(Cache::has('categories_tree') && !$force) {
            $categories_array = Cache::get('categories_tree');
        }
        else {
            $categories_array = static::buildCategoriesTree($id,$path,$last_path);
            Cache::forget('categories_tree');
            Cache::add('categories_tree',$categories_array,1);
        }

        return $categories_array;
    }

    public static function buildCategoriesTree($id = 0, $path = '', $last_path = ''){
        $categories_array = array();
        $categories = Vcategory::where('laparent_id', '=', $id)
            ->orderBy('laorder')
            ->orderBy('latitle')
            ->get();

        $path .= $last_path . "/";

        foreach ($categories as $categorie) {
            $categories_array[$categorie->id] = array(
                'latitle' => $categorie->latitle,
                'id' => $categorie->id,
                'laurl' => $categorie->laurl,
                'laorder' => $categorie->laorder,
                'lainfo' => $categorie->lainfo,
                'laparent_id' => $categorie->laparent_id,
                'laicon' => $categorie->laicon,
                'laimage' => $categorie->laimage,
                'path' => $path . $categorie->latitle,
                'isnews' => $categorie->isnews,

                'children' => array()
            );

            $categories_array[$categorie->id]['children'] = static::buildCategoriesTree($categorie->id, $path, $categorie->latitle);
        }
        return $categories_array;
    }

    public static function shopCatTree($id = 0, $categories = null, $level = 0)
    {
        $html = "<ul class='" . (($level == 0) ? 'menu' : '') . "'  " . (($level == 0) ? 'itemscope itemtype="http://schema.org/ItemList"' : '') . ">";
        foreach ($categories as $cat) {
            if($cat['isnews']) continue;
            $html .= "<li " . (($level == 0) ? 'itemprop="name"' : 'itemprop="itemListElement"') . ">
            <a itemprop='url' href='" . URL::to("/" . $cat['laurl']) . "' " . (($id == $cat['id']) ? "class='active'" : '')." >
                    " . (($level == 0) ? '<i class="' . $cat['laicon'] . '"></i>' : '') . " " . $cat['latitle'] . "
            </a>
            </li>";
            $html .= Vcategory::shopCatTree($id, $cat['children'], $level + 1);
        }
        $html .= "</ul>";
        return $html;
    }

    public static function makeBreadcrumCat($categories, $caturl, $level = 0, $path = array())
    {

        foreach ($categories as $cat) {
            if ($cat['laurl'] == $caturl) {
                $path[$level] = $cat;
                return $path;
            }
            $path = Vcategory::makeBreadcrumCat($cat['children'], $caturl, $level + 1, $path);
            if (isset($path[($level + 1)])) {
                $path[$level] = $cat;
                return $path;
            }
        }
        return $path;
    }

    public static function randCat()
    {
        $array = array();

        if(Cache::has('start_rand_cat')){
            return Cache::get('start_rand_cat');
        }
        $parentcat = DB::table('v_categories')
            ->where('laparent_id', '=', '0')
            ->where('laurl', '!=', 'tin-tuc')
            ->orderBy(DB::raw('RAND()'))
            ->get();
        foreach ($parentcat as $cat) {
            $ranproduct = DB::table('v_products')
                ->where('ladeleted','!=','1')
                ->where(function($query) use ($cat){
                    $query->where('cat1id', '=', $cat->id)
                        ->orwhere('cat2id', '=', $cat->id)
                        ->orwhere('cat3id', '=', $cat->id);
                })
                ->orderBy(DB::raw('RAND()'))
                ->take(3)
                ->get();
            $array[$cat->id]['cat'] = $cat;
            $array[$cat->id]['product'] = $ranproduct;
        }
        Cache::add('start_rand_cat',$array,5);
        return $array;
    }

}