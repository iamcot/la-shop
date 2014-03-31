<?php
class Vcategory extends Eloquent{
    protected $table = 'v_categories';
    public static function getCategoriesTree($id = 0, $path = '', $last_path = '')
    {
        $categories_array = array();

        $categories = Vcategory::where('laparent_id', '=', $id)->get();

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
                'numproduct'=> $categorie->numproduct,

                'children' => array()
            );

            $categories_array[$categorie->id]['children'] = Vcategory::getCategoriesTree($categorie->id, $path, $categorie->latitle);
        }

        return $categories_array;
    }
    public static function shopCatTree($id=0,$categories=null,$level = 0){
        if($level == 0)
            $categories = Vcategory::getCategoriesTree();
        $html="<ul class='".(($level==0)?'menu':'')."'>";
        foreach ($categories as $cat) {
            $html .= "<li><a href='".URL::to("/list/" . $cat['laurl'])."' ".(($id == $cat['id'])?"class='active'":'').">
                    ".(($level==0)?'<i class="'.$cat['laicon'].'"></i>':'')." ". $cat['latitle'] . " <span><b>".$cat['numproduct']."</b></span>
            </a> </li>";
            $html .= Vcategory::shopCatTree($id,$cat['children'],$level+1);
        }
        $html.="</ul>";
        return $html;
    }
    public static function randCat(){
        $parentcat =  DB::table('v_categories')
            ->where('laparent_id','=','0')
            ->orderBy(DB::raw('RAND()'))
            ->get();
        $array = array();
        foreach($parentcat as $cat){
            $ranproduct = DB::table('v_products')
                ->where('cat1id','=',$cat->id)
                ->orwhere('cat2id','=',$cat->id)
                ->orwhere('cat3id','=',$cat->id)
                ->orderBy(DB::raw('RAND()'))
                ->take(3)
                ->get();
            $array[$cat->id]['cat'] = $cat;
            $array[$cat->id]['product'] = $ranproduct;
        }
        return $array;
    }
}