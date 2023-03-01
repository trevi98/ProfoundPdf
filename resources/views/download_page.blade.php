<div class="main">
<div class="cont">

      <div class="clippy">
        <div class="eye left"></div>
        <div class="eye right"></div>
      </div>
      <div class="dialog">
        Hey there, profound is generating your brochure, it won't take long ...
      </div>
    </div>
</div>
  <style>
    @font-face {
        font-family: 'nova';
        src: url("{{ asset('fonts/Proxima nova/Proxima Nova Regular.otf') }}") format('truetype');
    }
    .main{
        width: 100vw;
        height: 100vh;
        background-color: #002D31;
        position: absolute;
        top: 0px;
        left: 0px;
        margin: 0px;
        padding: 0px;
    }
    .dialog{
      width: 100%;
      text-align: center;
      color: #fff;
      margin-top: 100px;
      margin-left: -167px;
      font-size: 20px;
      font-family: 'nova'

    }
    .cont {
  word-break: break-word;
  height: 100vh;
  top: 30%;
  left: 45%;
  position: absolute;
  background-color: #002D31;
}
.clippy {
  overflow-x: hidden;
  -webkit-tap-highlight-color: transparent;
  height: 80px;
  width: 40px;
  border: 8px solid #6c6a6a;
  border-top-left-radius: 40px;
  border-top-right-radius: 40px;
  border-bottom: none;
}
.clippy:before {
  position: absolute;
  top: 75px;
  width: 60px;
  height: 80px;
  border: 8px solid #6c6a6a;
  left: 0;
  content: ' ';
  border-top: none;
  border-bottom-left-radius: 40px;
  border-bottom-right-radius: 40px;
}
.clippy:after {
  position: absolute;
  top: 87px;
  width: 20px;
  height: 35px;
  border: 8px solid #6c6a6a;
  left: 20px;
  content: ' ';
  border-top: none;
  border-bottom-left-radius: 40px;
  border-bottom-right-radius: 40px;
}
.eye {
  position: absolute;
  height: 29px;
  width: 35px;
  border: 10px solid #6c6a6a;
  border-radius: 50%;
  border-right-color: transparent;
  border-left-color: transparent;
  border-bottom-color: transparent;
  border-bottom-width: 2px;
  top: 30px;
  background: whitesmoke;
}
.eye.left {
  left: -29px;
  transform: rotate(-20deg);
  animation: leftEye 2.5s infinite ease-in-out ;
}
.eye.right {
  left: 29px;
  transform: rotate(20deg);
  animation: rightEye 2.5s infinite ease-in-out ;
}
.eye:after {
  width: 15px;
  height: 15px;
  background-color: transparent;
  border: 10px solid #6c6a6a;
  border-radius: 50%;
  position: absolute;
  top: 5px;
  content: ' ';
  transform-origin: center;
}
.eye:before {
  content: ' ';
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background-color: #6c6a6a;
  position: absolute;
  top: 10px;
  left: 8px;
  transform-origin: bottom;
  animation: blink 2.5s infinite ease-in-out normal;
}

@keyframes blink {
  0%, 75%, 100% {
    height: 0px;
  }
  90%{
    height: 20px;
    transform: rotate(-12deg)
  }
}

@keyframes leftEye {
  0%, 60% {
    transform: rotate(-20deg);
  }
  30% {
    transform: rotate(0deg) translateY(-15%) translateX(5%);
  }
}

@keyframes rightEye {
  0%, 60% {
    transform: rotate(20deg);
  }
  30% {
    transform: rotate(0deg) translateY(-15%) translateX(-5%);
  }

}
  </style>



<script>
    setTimeout(function(){
            let a= document.createElement('a');
            a.target= '_blank';
            a.href= "http://145.14.158.172:3000?url={{Request()->get('view')}}&name={{Request()->get('name')}}";
            a.click();
        },30000)
    setTimeout(function(){
        history.back()
        },30100)
</script>
