@extends('layout')
@section('body')
    <div class="span3">
        <div class="list-group">
                <a class="list-group-item @if(!isset($sidecat) || $sidecat=='view') active @endif" href="{{url('admin/factor')}}">Xem<i class="icon-chevron-right"></i></a>
                <a class="list-group-item @if(isset($sidecat) && $sidecat=='create') active @endif"  href="{{url('admin/factor/create')}}">Tạo / Sửa <i class="icon-chevron-right"></i></a>
        </div>
    </div>
    <div class="span9 panel panel-default">
         {{--*/ $adminNav = Config::get('admin.adminnav') /*--}}
         {{--*/ $strActCat = $adminNav[$actCat] /*--}}

        <div class="panel-heading">{{trans('common.LD'.$sidecat)}} {{trans('common.'.$strActCat['title'])}}</div>
        <div class="panel-body">
            @if (!isset($sidecat) || $sidecat=='view')
            @include('admin/factorview')
            @else
            @include('admin/factorcreate')
            @endif
        </div>

    </div>
@stop
@section('jscript')
@stop