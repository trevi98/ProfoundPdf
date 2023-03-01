
<div class="page ">
    <div class="final-page-main">
        <div class="final-page-img">
            <img src="{{ asset('imgs/peninsula-31.jpg') }}" alt="">
            <div class="filter"></div>
        </div>
        <div class="sginiture">
            <img src="{{ asset('imgs/p.svg') }}" alt="">
        </div>
    </div>
</div>


<style>
    *{
        margin: 0px;
        padding: 0px;
        /* box-sizing: border-box; */
        box-sizing: border-box;
    }
    @font-face {
        font-family: 'nova';
        src: url("{{ asset('fonts/Proxima nova/Proxima Nova Regular.otf') }}") format('truetype');
    }
    .page{
        overflow: hidden;
        height: 100vh;
    }
    .final-page-main{
        height: 100vh;
        width: 100vw;
    }
    .final-page-img{
        width: 100%;
        height: 100%;
        position: relative;
    }
    .final-page-img .filter{
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0px;
        left: 0px;
        /* background-color: red; */
        background-color: rgba(1,20,22,0.4);
        mix-blend-mode: soft-light;
    }
    .final-page-img img{
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }
    .sginiture{
        position: absolute;
        width: 100%;
        height: 200px;
        bottom: 0px;
        left: 0px;
        opacity: 0.9;
    }
    .sginiture img{
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }



</style>
