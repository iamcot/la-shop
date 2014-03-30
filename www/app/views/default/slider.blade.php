<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
        {{--*/ $i = 0 /*--}}
        @foreach($slider as $itemslide)
        <div class="item @if($i==0) active @endif">
            {{HTML::image('uploads/product/'.$itemslide['pic'])}}
            <div class="carousel-caption">
                {{$itemslide['title']}}
            </div>
        </div>
        {{--*/ $i +=1 /*--}}
        @endforeach
    </div>

    <!-- Controls -->
    <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
    </a>
    <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
    </a>
</div>
@section('jscript')
<script>
    $('.carousel').carousel();
</script>

@stop