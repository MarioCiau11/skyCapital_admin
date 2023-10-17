@extends('layouts.layout')

@section('extra-css')
<style>
    * {
        margin: 0;
        padding: 0;
      }
  /* .slide {
    position: relative;
    margin: 100px auto;
    width: 940px;
    height: 480px;
    background: #ccc;
  } */
  .slide {
    position: relative;
    margin: 100px auto;
    margin-top:0%;
    margin-bottom: 0%; 
    width: 100vw; /* Ocupar todo el ancho de la pantalla */
    height: 100vh; /* Ocupar todo el alto de la pantalla */
    background: #ccc;

    /* border: 1px solid red; */
  }
  @media (max-width: 950px) {
    .slide {
        margin: 50px auto; /* Reducir el margen en pantallas pequeñas */
        height: 65vh; /* Ajustar el alto en pantallas pequeñas */
        background: #eee; /* Cambiar el color de fondo en pantallas pequeñas */
    }
}

  .slide ul li {
    position: absolute;
    left: 0;
    top: 0;
    display: block;
    width: 100%;
    height: 100%;
    list-style: none;
  }

  .slide .dots {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 20px;
    width: 100%;
    z-index: 3;
    text-align: center;
  }

  .slide .dots li {
    display: inline-block;
    margin: 0 10px;
    width: 10px;
    height: 10px;
    border: 2px solid #fff;
    border-radius: 50%;
    opacity: 0.4;
    cursor: pointer;
    transition: background .5s, opacity .5s;
    list-style: none;
  }

  .slide .dots li.active {
    background: #fff;
    opacity: 1;
  }

  .slide .arrow {
    position: absolute;
    z-index: 2;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    transition: background .5s, opacity .5s;
  }

  .slide .arrow .arrow-left,
  .slide .arrow .arrow-right {
    position: absolute;
    top: 50%;
    margin-top: -25px;
    display: block;
    width: 50px;
    height: 50px;
    cursor: pointer;
    opacity: 0.5;
    transition: background .5s, opacity .5s;
  }

  .slide .arrow  .arrow-left:hover,
  .slide .arrow  .arrow-right:hover {
    opacity: 1;
  }

  .slide .arrow .arrow-left {
    left: 20px;
    /* background: url("../img/arrow-left.png"); */
    background: url("{{ asset('images/arrow-left.png') }}");
  }

  .slide .arrow .arrow-right {
    right: 20px;
    /* background: url("../img/arrow-right.png"); */
    background: url("{{ asset('images/arrow-right.png') }}");
  }
  .slide .imgs{
    width: 100%;
    height: 100%;
  }

  .section{
    padding-top: 80px !important;
  }

  .ath-body {
    padding: 0px 0px 0px 0px !important;
}
</style>
@endsection

@section('content')
  <div class="slide">
    <ul>
      {{-- <li><img class="imgs" src="{{ asset('images/slide1.jpg') }}" ></li>
      <li><img class="imgs" src="{{ asset('images/slide2.jpg') }}" ></li>
      <li><img class="imgs" src="{{ asset('images/slide3.jpg') }}" ></li>  --}}
      <li><img class="imgs" src="{{ asset('images/slide4.webp') }}" ></li>
      {{-- <li data-bg="{{ asset('images/slide1.jpg') }}"></li>
      <li data-bg="{{ asset('images/slide2.jpg') }}"></li>
      <li data-bg="{{ asset('images/slide3.jpg') }}"></li> --}}
    </ul>
  </div>
@endsection

@section('extra-js')
  <script src="{{ asset('js/jquery.slide.min.js') }}"></script>
  <script type="text/javascript">
    $(function() {
      $('.slide').slide({
        isAutoSlide: true,                
        isHoverStop: true,               
        isBlurStop: true,                 
        isShowDots: true,                 
        isShowArrow: true,                
        isHoverShowArrow: true,           
        isLoadAllImgs: true,             
        slideSpeed: 10000,                
        switchSpeed: 500,                 
        dotsClass: 'dots',                
        dotActiveClass: 'active',         
        dotsEvent: 'click',               
        arrowClass: 'arrow',              
        arrowLeftClass: 'arrow-left',     
        arrowRightClass: 'arrow-right'    
      });

      //sacar el ancho y alto de la pantalla
      var ancho = $(window).width();
      var alto = $(window).height();

      console.log({ancho, alto});
    });
  </script>
@endsection
