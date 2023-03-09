<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDO;

class Layout extends Controller
{
    //

    public function test(){
        return "dd";
    }

    public function layout_picker($args){
        $layout = "";
        if ($args['page']['layout'] == 'map'){
            $layout = $this->map_layout(['local'=>$args['page']['data'],'global'=>$args['global_data']]);
        }
        else if($args['page']['layout'] == "single_pic_with_caption"){
            $layout = $this->single_pic_with_caption(['local'=>$args['page']['data']]);
        }
        else if($args['page']['layout'] == "floor_plans"){
            $layout = $this->single_full_page_image_layout(['local'=>$args['page']['data']]);
        }
        else if($args['page']['layout'] == "text_img_img_text"){
            $layout = $this->text_img_img_text(['local'=>$args['page']['data']]);
        }
        else if($args['page']['layout'] == "description-2-images"){
            $layout = $this->text_2_imgs(['local'=>$args['page']['data']]);
        }

        return $layout;
    }

    private function break_text($text,$limit){
        $text_length = strlen($text);
        $half_length = $limit;

        // Find the index of the last space before the halfway point
        $last_space_index = strrpos(substr($text, 0, $half_length), ' ');

        // If there are no spaces before the halfway point, split at the halfway point
        if ($last_space_index === false) {
          $last_space_index = $half_length;
        }

        // Split the text into two parts
        $part1 = substr($text, 0, $last_space_index);
        $part2 = substr($text, $last_space_index + 1);

        // Trim any whitespace from the beginning and end of each part
        $part1 = trim($part1);
        $part2 = trim($part2);

        // Return an array containing both parts
        return array($part1, $part2);
    }

    public function page_coordinator($args){
        $final_file = '';
        $global_css = "<style>";
        $global_html = "<html>";
        $layout = $this->home_layout($args['global_data']);
        $global_html .= $layout[0];
        $global_css .= $layout[1];
        if(strlen($args['global_data']['area_description']) <= 782){

            $layout = $this->area_first_page($args['global_data']);
        }
        else{
            list($first_part, $second_part) = $this->break_text($args['global_data']['area_description'],782);
            $layout = $this->area_2_pager(['global' => $args['global_data'],'local'=>['first'=>$first_part,'second'=>$second_part]]);
            // dd($first_part);
        }
        $global_html .= $layout[0];
        $global_css .= $layout[1];

        foreach($args['pages'] as $page){
            $layout = $this->layout_picker(['page'=>$page,'global_data'=>$args['global_data']]);
            $global_html .= $layout[0];
            $global_css .= $layout[1];
        }

        if(count($args['global_data']['payment_plans']) > 0){
            $layout = $this->payment_plan_layout(['global'=>$args['global_data']]);
            $global_html .= $layout[0];
            $global_css .= $layout[1];

        }
        if(count($args['global_data']['projections']) > 0){
            $layout = $this->projections_layout(['global'=>$args['global_data']]);
            $global_html .= $layout[0];
            $global_css .= $layout[1];

        }
        $layout = $this->last_page_layout(['global'=>$args['global_data']]);
        $global_html .= $layout[0];
        $global_css .= $layout[1];


        $global_css .="</style>";
        $global_html .="</html>";
        $final_file .= $global_html.$global_css;
        return $final_file;
    }

    public function home_layout($data){
        $html = "
        <div class=\"page\">
            <div class=\"header\">
                <img src=\"{{ asset('imgs/header.png') }}\" alt=\"\">
            </div>
            <div class=\"cover\">
                <div class=\"wrapper\">
                    <img src=\"{{ asset('storage/".$data['cover']."') }}\" alt=\"\">
                    ";
                    if($data['project_logo'] != null){
                        $html .= "

                           <img src=\"{{ asset('storage/".$data['project_logo']."') }}\" alt=\"\" class=\"projlogo\">
                        ";
                    }
        $html .= "
                    <div class=\"filter\"></div>
                </div>
            </div>
            <div class=\"footer\">
                <div class=\"wrapper\">
                    <img src=\"{{ asset('imgs/footer.png') }}\" alt=\"\">
                    <div class=\"dev\">
                    ";
                    if($data['developer_logo'] != null){
                        $html .= "<img class=\"dev-logo\" src=\"{{ asset('storage/".$data['developer_logo']."') }}\" alt=\"\">";
                    }
                    $html .="
                    </div>
                    <div class=\"location\">
                        <div class=\"wrapper\">

                            <img src=\"{{ asset('imgs/location.png') }}\" alt=\"\">
                            <p>".$data['area_title']."</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        ";

        $css = "
                @font-face {
                    font-family: 'nova';
                    src: url(\"{{ asset('fonts/Proxima nova/Proxima Nova Regular.otf') }}\") format('truetype');
                }
                *{
                    margin: 0px;
                    padding: 0px;
                }
                html {
                    -webkit-print-color-adjust: exact;

                }
                .page{
                    height: 100vh;
                    width: 100%;
                    page-break-after: always;
                    position: relative;
                    overflow: hidden;
                }
                .dev-logo{
                    object-fit: contain !important;
                    object-position: center !important;
                }

                .header{
                    width: 100vw;
                    height: 100px;
                    /*background-color:#D5DCDD;*/

                }
                .header img{
                    width: 100%;
                    height: 100%;
                    object-position:center;
                }
                .cover{
                    position: absolute;
                    top: 0px;
                    left: 0px;
                    height: 100%;
                    width: 100%;
                    z-index: -1;
                    /* background-color: #5e1771; */
                }
                .cover .wrapper{
                    position: relative;
                }
                .cover .projlogo{
                    width: 150px !important;
                    height: 150px !important;
                    position: absolute !important;
                    top: 120px !important;
                    left: 260px !important;
                    object-position: center !important;
                    object-fit: cover !important;
                }
                .cover img{
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    object-position: center;
                }
                .footer{
                    position: absolute;
                    bottom: 0px;
                    left: 0px;
                    height: 100px;
                    width: 100%;
                    /*background-color:#D5DCDD;*/

                }
                .footer img{
                    width: 100%;
                    height: 100%;
                    object-fit:cover;
                    object-position:bottom left;
                }
                .footer .wrapper{
                    width: 100%;
                    height: 100%;
                    position: relative;
                }
                .dev{
                    position: absolute;
                    top:-4px;
                    left: calc(50% - 129px);
                    width: 129px;
                    height: 75px;
                    margin: 20px 0px 10px 0px;
                    border-radius:15px;
                    opacity:0.9;
                    padding:0 10px;
                    ";
                    if($data['developer_logo'] != null){
                        $css .= "
                        background-image:url(\"{{asset('imgs/devback.png')}}\");
                        background-position: center;
                        background-size: cover;";
                    }
                        $css .="
                    }
                }
                .dev img{
                    height: 56px;
                    width: 60px;
                    margin: 8px calc(50% - 30px);
                  }
                }
                .location{
                    position: absolute;
                    top: 45px;
                    right: 20px;
                    display: inline-block;
                }
                .location img{
                    width: 35px;
                    height: 35px;
                    margin-right: 10px;
                    -webkit-border-top-left-radius: 10px;
                    -webkit-border-bottom-right-radius: 10px;
                    -moz-border-radius-topleft: 10px;
                    -moz-border-radius-bottomright: 10px;
                }
                .location p{
                    /* display: inline; */

                    font-family: 'nova';
                    position: absolute;
                    top: 3px;
                    right: 0px;
                    font-size: 20px;
                    color: #fff;
                    display:inline-block;
                    position:relative;
                    top:-14px;

                }
                .location .wrapper{
                    width: 100%;
                    height: 100%;
                    position: relative;
                    display:inline-block;
                }
                .cover .wrapper{
                    position:relative;
                    width:100%;
                    height:100%;
                }
                .cover .filter{
                    position: absolute;
                    width: 100%;
                    height: 100%;
                    top: 0px;
                    left: 0px;
                    background-color: rgba(1,20,22,0.4);
                    mix-blend-mode: soft-light;
                }

        ";
        return [$html,$css];
    }

    public function area_first_page($data){
        $html = "

        <div class=\"page page-back\">
            <div class=\"area-fp_first_background\">

            </div>

            <div class=\"area-fp_main\">
                <div class=\"area-fp_left\">
                    <div class=\"area-fp_title\">
                        <div class=\"area-fp_title_img\">
                            <img src=\"{{ asset('imgs/location.png') }}\" alt=\"\">
                        </div>
                        <div class=\"area-fp_title_title\">
                            ".$data['area_title']."
                        </div>
                    </div>
                    <div class=\"area-fp_description\">
                        <p>

                        ".$data['area_description']."
                        </p>

                    </div>
                </div>
                <div class=\"area-fp_right\">
                    <div class=\"wrapper\">
                        <img src=\"".asset('storage/'.$data['area_img_1'])."\" alt=\"\">
                        <div class=\"area-filter\"></div>
                    </div>
                    <div class=\"wrapper\">
                        <img src=\"".asset('storage/'.$data['area_img_2'])."\" alt=\"\">
                        <div class=\"area-filter\"></div>
                    </div>

                </div>
            </div>


            </div>


        </div>

         ";

            $css = "
            .page-back{
                background-color:#D5DCDD;
            }
            .area-fp_main .wrapper{
                position: relative;
                width:100%;
                height: calc(50% - 10px);
            }
            .area-filter{
                position: absolute;
                top: 0px;
                left: 0px;
                width: 100%;
                height: 100%;
                background-color: rgba(1,20,22,0.4);
                border-radius: 17px;
                mix-blend-mode: soft-light;
            }
            }
            .area-fp_first_background{
                position: absolute;
                width: 100vw;
                height: 100vh;
                top: 0px;
                left:0px;
                z-index: -1;
                background-color:#D5DCDD;
            }

            .area-fp_main{
                box-sizing: border-box;
                background-color:#002D31;
                background-repeat: no-repeat;
                height: calc(100vh - 20px);
                width: calc(100vw - 40px);
                margin: 10px 20px;
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                color: #fff;
                font-family: 'nova';
                padding: 40px 30px;
                overflow: hidden;
                border-radius:13px;
            }
            .area-fp_left{
                width: 48%;
                display: flex;
                flex-direction: column;
                gap: 50px;
            }
            .area-fp_title{
                display: flex;
                width: 450px;
                flex-direction: row;
                align-items: center;
                /* justify-content: space-between; */
                gap: 30px;
            }
            .area-fp_title_img img{
                width: 80px;
                height: 80px;
                -webkit-border-top-left-radius: 20px;
                -webkit-border-bottom-right-radius: 20px;
                -moz-border-radius-topleft: 20px;
                -moz-border-radius-bottomright: 20px;
            }
            .area-fp_title_title{
                font-size: 40px;
                letter-spacing: 3.5px;
            }
            .area-fp_right{
                width: 48%;
                display: flex;
                flex-direction: column;
                gap: 20px;
            }
            .area-fp_right img{
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center;
                border-radius:17px;
            }
            .area-fp_description{
                line-height: 1.9 !important;
                font-family: 'nova';
                height: calc(100% - 20px);
                overflow: hidden;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size:20px;
            }

            ";
        return [$html,$css];
    }

    public function area_2_pager($data){
        $html = "

        <div class=\"page page-back\">
            <div class=\"area-fp_first_background\">

            </div>

            <div class=\"area-fp_main\">
                <div class=\"area-fp_left\">
                    <div class=\"area-fp_title\">
                        <div class=\"area-fp_title_img\">
                            <img src=\"{{ asset('imgs/location.png') }}\" alt=\"\">
                        </div>
                        <div class=\"area-fp_title_title\">
                            ".$data['global']['area_title']."
                        </div>
                    </div>
                    <div class=\"area-fp_description\">
                        <p>

                        ".$data['local']['first']."
                        </p>

                    </div>
                </div>
                <div class=\"area-fp_right\">
                    <div class=\"wrapper\">
                        <img src=\"".asset('storage/'.$data['global']['area_img_1'])."\" alt=\"\">
                        <div class=\"area-filter\"></div>
                    </div>
                    <div class=\"wrapper\">
                        <img src=\"".asset('storage/'.$data['global']['area_img_2'])."\" alt=\"\">
                        <div class=\"area-filter\"></div>
                    </div>

                </div>
            </div>


            </div>


        </div>
        <div class=\"page page-back\">
            <div class=\"area-fp_first_background\">

            </div>

            <div class=\"area-fp_main\">

                <div class=\"area-fp_right\">
                    <div class=\"wrapper\">
                        <img src=\"".asset('storage/'.$data['global']['area_img_3'])."\" alt=\"\">
                        <div class=\"area-filter\"></div>
                    </div>
                    <div class=\"wrapper\">
                        <img src=\"".asset('storage/'.$data['global']['area_img_4'])."\" alt=\"\">
                        <div class=\"area-filter\"></div>
                    </div>

                </div>

                <div class=\"area-fp_left\">
                <div class=\"area-fp_description\">
                    <p>

                    ".$data['local']['second']."
                    </p>

                </div>
            </div>
            </div>


            </div>


        </div>

         ";

            $css = "
            .page-back{
                background-color:#D5DCDD;
            }
            .area-fp_main .wrapper{
                position: relative;
                width:100%;
                height: calc(50% - 10px);
            }
            .area-filter{
                position: absolute;
                top: 0px;
                left: 0px;
                width: 100%;
                height: 100%;
                background-color: rgba(1,20,22,0.4);
                border-radius: 17px;
                mix-blend-mode: soft-light;
            }
            }
            .area-fp_first_background{
                position: absolute;
                width: 100vw;
                height: 100vh;
                top: 0px;
                left:0px;
                z-index: -1;
                background-color:#D5DCDD;
            }

            .area-fp_main{
                box-sizing: border-box;
                background-color:#002D31;
                background-repeat: no-repeat;
                height: calc(100vh - 20px);
                width: calc(100vw - 40px);
                margin: 10px 20px;
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                color: #fff;
                font-family: 'nova';
                padding: 40px 30px;
                overflow: hidden;
                border-radius:13px;
            }
            .area-fp_left{
                width: 48%;
                display: flex;
                flex-direction: column;
                gap: 50px;
            }
            .area-fp_title{
                display: flex;
                width: 450px;
                flex-direction: row;
                align-items: center;
                /* justify-content: space-between; */
                gap: 30px;
            }
            .area-fp_title_img img{
                width: 80px;
                height: 80px;
                -webkit-border-top-left-radius: 20px;
                -webkit-border-bottom-right-radius: 20px;
                -moz-border-radius-topleft: 20px;
                -moz-border-radius-bottomright: 20px;
            }
            .area-fp_title_title{
                font-size: 40px;
                letter-spacing: 3.5px;
            }
            .area-fp_right{
                width: 48%;
                display: flex;
                flex-direction: column;
                gap: 20px;
            }
            .area-fp_right img{
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center;
                border-radius:17px;
            }
            .area-fp_description{
                line-height: 1.9 !important;
                font-family: 'nova';
                height: calc(100% - 20px);
                overflow: hidden;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size:18px;
            }

            ";
        return [$html,$css];
    }

    public function map_layout($data){
        $html = "
            <div class=\"page\">
                <div class=\"map-page-main\">
                    <div class=\"map-page-left\">
                        <div class=\"map-page-title\">
                            ".$data['local']->title."
                        </div>
                        <div class=\"map-page-description\">
                            ".$data['local']->description."
                        </div>
                        <div class=\"map-page-table\">";
                            if(count($data['global']['landmarks']) > 0){
                                $html .= "
                                <table>
                                    <tr>

                                        <th>LANDMARK</th>
                                        <th>DISTANCE</th>
                                        <th>DRIVE</th>
                                    </tr>
                                ";
                            }

                                foreach($data['global']['landmarks'] as $landmark){
                                    $html .="
                                        <tr>
                                            <td>".$landmark['landmark']."</td>
                                            <td>".$landmark['distance']."</td>
                                            <td>".$landmark['drive']."</td>
                                        </tr>
                                    ";
                                }

                                if(count($data['global']['landmarks']) > 0){
                                    $html .= "
                                        </table>
                                    ";
                                }

                                $html .= "
                        </div>
                    </div>
                    <div class=\"map-page-right\">
                        <img src=\"{{ asset('storage/".$data['local']->map."') }}\" alt=\"\">
                    </div>
                </div>
            </div>
        ";

        $css = "
        .map-page-main{
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: row;
            background-color: ".$data['local']->background_color.";
            /* flex-basis: 1 3; */
            gap:40px;



        }
        .map-page-left{
            /* background-color: red; */
            color: ".$data['local']->text_color.";
            font-family: 'nova';
            flex-basis: calc(45% - 40px);
            padding: 5px 0px 5px 20px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;

        }
        .map-page-title{
            font-size: 25px;
            width: 250px;
            letter-spacing: 3.5px;
        }
        .map-page-description{
            font-size: 20px;
            line-height: 1.6;
        }
        .map-page-left table{
            color: ".$data['local']->text_color.";
            border: 1px ".$data['local']->text_color." solid;
            font-size: 11px;
        }
        .map-page-left table tr, .map-page-left table th, .map-page-left table td{
            /* color: #fff; */
            border-left: 1px ".$data['local']->text_color." solid;
            border-bottom: 1px ".$data['local']->text_color." solid;
            padding: 5px 25px;
        }
        .map-page-left table th:first-of-type, .map-page-left table td:first-of-type{
            border-left: none;
        }
        .map-page-left table tr:last-of-type th, .map-page-left table tr:last-of-type td{
            border-bottom:none;
        }
        .map-page-right{
            /* background-color: blue; */
            flex-basis: calc(55% - 40px);
            height: 70vh;
            margin: 15vh 0;
        }
        .map-page-right img{
            right: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            border-radius:17px;
            object-fit: cover;
            object-position: center;
        }
        ";

        return [$html,$css];
    }

    public function single_pic_with_caption($args){
        $html = "
            <div class=\"page\">
                <div class=\"singl-page-with-caption-main\">
                    <div class=\"singl-page-with-caption-img\">
                        <div class=\"s-i-w-c-wrapper\">
                            <img src=\"{{ asset('storage/".$args['local']->img."') }}\" alt=\"\">
                            <div class=\"s-i-w-c-filter\"></div>
                        </div>
                    </div>
                    <div class=\"singl-page-with-caption-text\">
                        <p>
                            ".$args['local']->caption."
                        </p>
                    </div>
                </div>
            </div>
        ";
    $css = "
            .s-i-w-c-wrapper{
                position:relative;
            }
            .s-i-w-c-filter{
                position: absolute;
                width: 100%;
                height: 100%;
                border-radius: 17px;
                top: 0px;
                left: 0px;
                background-color: rgba(1,20,22,0.4);
                mix-blend-mode: soft-light;
            }
            .singl-page-with-caption-main{
                width: 100vw;
                height: 100vh;
                background-color:".$args['local']->background_color.";
                display: flex;
                flex-direction: column;
                position:relative;
            }
            .singl-page-with-caption-img{
                    width: calc(100vw - 80px);
                    height: calc(100vh - 80px);
                    padding: 40px 40px 10px 40px;
                    border-radius: 17px;
            }
            .singl-page-with-caption-img img{
                    width: 100%;
                    height: 100%;
                    border-radius: 17px;
                    object-fit: cover;
                    object-position: center;
            }
            .singl-page-with-caption-text{
                    color: ".$args['local']->text_color.";
                    font-family: 'nova';
                    font-size: 18px;
                    padding: 0px 40px;
                    font-style: italic;
            }
        ";

        return [$html,$css];
    }

    public function single_full_page_image_layout($args){
        $html = "
            <div class=\"page\">
                <div class=\"single-full-page-image\">
                    <img src=\"{{ asset('storage/".$args['local']->floorplan."') }}\" alt=\"\">
                </div>
            </div>
        ";
        $css = "
            .single-full-page-image{
                width: 100vw;
                height: 100vh;
            }
            .single-full-page-image img{
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center;
            }
        ";

        return [$html,$css];
    }
    public function payment_plan_layout($args){
        // dd($args['global']['payment_plans']);
        $total = 6;
        $counter = 0;
        $html = "
            <div class=\"page page-back\">
                <div class=\"payment-plan-page-main-title\">
                    <p>
                        Payment Plan
                    </p>
                </div>
                <div class=\"payment-plan-page-main\">
                    <table>
                        <tr>
                            <th>INSTALLMENT</th>
                            <th>MILESTONE</th>
                            <th>PAYMENT</th>
                        </tr>";
                        foreach($args['global']['payment_plans'] as $plan){
                            $html .= "
                                <tr>
                                    <td>".$plan['installment']."</td>
                                    <td>".$plan['milestone']."</td>
                                    <td>".$plan['payment']."</td>
                                </tr>
                            ";
                        }

                    $html .= "
                    </table>
                </div>
            </div>
        ";
        $css = "
            .payment-plan-page-main-title{
                height: min-content;
                width: 100vw;
                display: flex;
                justify-content: center;
                margin: 70px 0px 0px 0px;
                font-size: 30px;
                font-family: 'nova';
            }
            .payment-plan-page-main{
                    width: 100vw;
                    height: calc(100vh - 100px);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    overflow: hidden;
            }
            .payment-plan-page-main table{
                border: 2px #000 solid;
                font-size: 20px;
                font-family: 'nova';
            }
            .payment-plan-page-main table th{
                background-color: #002d31;
                color:#fff;
            }

            .payment-plan-page-main table th,.payment-plan-page-main table td{
                border-left: 2px #000 solid;
                border-bottom: 2px #000 solid;
                padding: 5px 30px;
                text-align: center;
                }
            .payment-plan-page-main table th:first-of-type,.payment-plan-page-main table td:first-of-type{
                border-left: none;
            }
            .payment-plan-page-main table tr:last-of-type th,.payment-plan-page-main table tr:last-of-type td{
                border-bottom: none;
            }
        ";
        return [$html,$css];
    }

    public function text_img_img_text($args){
        $html = "
            <div class=\"page\">
                <div class=\"t-i-i-t\">
                    <div class=\"t-i-i-t-left_text\">
                        <div class=\"t-i-i-t-title\">
                        ".$args['local']->title1."
                        </div>
                        <p>
                            ".$args['local']->description1."
                        </p>
                    </div>
                    <div class=\"t-i-i-t-left_img\">
                        <img src=\"{{ asset('storage/".$args['local']->img1."') }}\" alt=\"\">
                        <div class=\"t-i-i-t-filter\"></div>
                    </div>
                    <div class=\"t-i-i-t-right_img\">
                        <img src=\"{{ asset('storage/".$args['local']->img2."') }}\" alt=\"\">
                        <div class=\"t-i-i-t-filter\"></div>

                    </div>
                    <div class=\"t-i-i-t-right_text\">
                        <div class=\"t-i-i-t-title\">
                        ".$args['local']->title2."
                        </div>
                        <p>
                            ".$args['local']->description2."
                        </p>
                    </div>

                </div>
            </div>
        ";
        $css = "
            .t-i-i-t-filter{
                position: absolute;
                left: 0px;
                top: 0px;
                width: 100%;
                height: 100%;
                background-color: rgba(1,20,22,0.4);
                border-radius: 13px;
                mix-blend-mode: soft-light;
            }
            .t-i-i-t{
                width: calc(100vw - 40px);
                height: 100vh;
                padding: 20px;
                display: grid;
                grid-template-columns: 1fr 1fr;
                grid-template-rows: calc(50vh - 20px) calc(50vh - 20px) ;
                gap: 5px;
                background-color: ".$args['local']->background_color.";
        }
        .t-i-i-t-left_text, .t-i-i-t-right_text{
                height: 100%;
                width: 100%;
                color: ".$args['local']->text_color.";
                font-family: 'nova';
        }
        .t-i-i-t-left_img{
            position:relative;
        }
        .t-i-i-t-right_img{
            position:relative;
        }

        .t-i-i-t-left_img img{
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center;
                border-radius: 13px;
        }
        .t-i-i-t-right_img img{
            position: relative;
                width: 100%;
                height: 100%;
                /* aspect-ratio: 1/1; */
                object-fit: cover;
                object-position: center;
                border-radius: 13px;
        }
        .t-i-i-t-title{
            text-align: center;
            margin-top: 10px;
            margin-bottom: 20px;
            font-size: 22px;
            letter-spacing: 1.3px;
        }
        .t-i-i-t-left_text p, .t-i-i-t-right_text p{
            line-height: 1.5;
            font-size: 20px;
            padding: 0 20px;
            text-align:center;
        }
        ";

        return[$html,$css];
    }

    public function projections_layout($args){
        $html = "
            <div class=\"page page-back\">
                <div class=\"projection-page page-back\">
                    <div class=\"p-p-text\">
                        Here is an example of the short term and long term rental projection for a 1 bedroom apartment:
                    </div>
                    <div class=\"p-p-short\">
                        <table>
                            <tr>
                                <th>Short term rental projection</th>
                                <th>Ammount in AED</th>
                                <th>Ammount in USD</th>
                                <th>Ammount in EUR</th>
                            </tr>
                            <tr>
                                <td>
                                    Property Price
                                </td>
                                <td>
                                    ".$args['global']['projections']['property_price']."
                                </td>
                                <td>
                                    ".intval($args['global']['projections']['property_price']) * 0.27."
                                </td>
                                <td>
                                    ".intval($args['global']['projections']['property_price']) * 0.26."
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Minimum Rate per Night
                                </td>
                                <td>
                                    ".$args['global']['projections']['minimum_rate_per_night']."
                                </td>
                                <td>
                                    ".intval($args['global']['projections']['minimum_rate_per_night']) * 0.27."
                                </td>
                                <td>
                                    ".intval($args['global']['projections']['minimum_rate_per_night']) * 0.26."
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Minimum Yearly Occupancy (80%)  = 292 Days
                                </td>
                                <td>
                                    ".$args['global']['projections']['minimum_yearly_occupancy']."
                                </td>
                                <td>
                                    ".intval($args['global']['projections']['minimum_yearly_occupancy']) * 0.27."
                                </td>
                                <td>
                                    ".intval($args['global']['projections']['minimum_yearly_occupancy']) * 0.26."
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Short Term Holiday Homr Managment fee = 15% of  Profit
                                </td>
                                <td>
                                    ".$args['global']['projections']['short_term_holiday_managment_fee']."
                                </td>
                                <td>
                                    ".intval($args['global']['projections']['short_term_holiday_managment_fee']) * 0.27."
                                </td>
                                <td>
                                    ".intval($args['global']['projections']['short_term_holiday_managment_fee']) * 0.26."
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Yearly Service Chargr (Approximate)
                                </td>
                                <td>
                                    ".$args['global']['projections']['yearly_service_charge']."
                                </td>
                                <td>
                                    ".intval($args['global']['projections']['yearly_service_charge']) * 0.27."
                                </td>
                                <td>
                                    ".intval($args['global']['projections']['yearly_service_charge']) * 0.26."
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Total Yearly Deductions
                                </td>
                                <td>
                                    ".$args['global']['projections']['total_yearly_deductions']."
                                </td>
                                <td>
                                    ".intval($args['global']['projections']['total_yearly_deductions']) * 0.27."
                                </td>
                                <td>
                                    ".intval($args['global']['projections']['total_yearly_deductions']) * 0.26."
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Net Profet After Deduction
                                </td>
                                <td>
                                    ".$args['global']['projections']['net_profit_after_deduction']."
                                </td>
                                <td>
                                    ".intval($args['global']['projections']['net_profit_after_deduction']) * 0.27."
                                </td>
                                <td>
                                    ".intval($args['global']['projections']['net_profit_after_deduction']) * 0.26."
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Return on investment
                                </td>
                                <td colspan=\"3\">
                                    ".round($args['global']['projections']['roi'],1)."%
                                </td>
                            </tr>

                        </table>
                    </div>
                    <div class=\"p-p-long\">
                        <table>
                            <tr>
                                <th>Long term rental projection</th>
                                <th>Ammount in AED</th>
                                <th>Ammount in USD</th>
                                <th>Ammount in EUR</th>
                            </tr>
                            <tr>
                                <td>
                                    Property Price
                                </td>
                                <td>
                                    ".$args['global']['projections']['property_price']."
                                </td>
                                <td>
                                    ".intval($args['global']['projections']['property_price']) * 0.27."
                                </td>
                                <td>
                                    ".intval($args['global']['projections']['property_price']) * 0.26."
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Yearly Service Chargr (Approximate)
                                </td>
                                <td>
                                    ".$args['global']['projections']['yearly_service_charge']."
                                </td>
                                <td>
                                    ".intval($args['global']['projections']['yearly_service_charge']) * 0.27."
                                </td>
                                <td>
                                    ".intval($args['global']['projections']['yearly_service_charge']) * 0.26."
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Net Profet After Deduction
                                </td>
                                <td>
                                    ".$args['global']['projections']['long_term_net_profit_after_deduction']."
                                </td>
                                <td>
                                    ".intval($args['global']['projections']['long_term_net_profit_after_deduction']) * 0.27."
                                </td>
                                <td>
                                    ".intval($args['global']['projections']['long_term_net_profit_after_deduction']) * 0.26."
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Return on investment
                                </td>
                                <td colspan=\"3\">
                                    ".round($args['global']['projections']['long_term_roi'],1)."%
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        ";
        $css = "
            .p-p-text{
                font-size: 18px;
            }
            .projection-page{
                width: calc(100vw - 40px);
                height: calc(100vh - 40px);
                display: flex;
                flex-direction: column;
                justify-content: space-evenly;
                align-items: center;
                font-family: 'nova';
                margin: 20px;
            }
            .p-p-long, .p-p-short{
                width: 60%;
                margin: 0 auto;
            }
            .projection-page table{
                border: 2px #000 solid;
                font-size: 16px;
                font-family: 'nova';
                width: 100%;
            }
            .projection-page table tr:first-of-type{
                background-color: #002d31;
                color: #fff;
            }
            .projection-page table tr:last-of-type{
                background-color: #002d31;
                color: #fff;
            }

            .projection-page table th,.projection-page table td{
                border-left: 2px #000 solid;
                border-bottom: 2px #000 solid;
                padding: 5px 5px;
                text-align: center;
            }
            .projection-page table th:first-of-type,.projection-page table td:first-of-type{
                border-left: none;
                text-align: left !important;
            }
            .projection-page table tr:last-of-type th,.projection-page table tr:last-of-type td{
                border-bottom: none;
            }
        ";
        return [$html,$css];
    }
    public function last_page_layout($data){
        $html = "
            <div class=\"page \" style=\"background-color:#fff;\">
                <div class=\"final-page-main\">
                    <div class=\"final-page-img\">
                        ";
                        if($data['global']['last'] != null){

                            $html .= "
                            <img src=". asset('storage/'.$data['global']['last'])." alt=\"\">";
                        }
                        $html .="
                        <div class=\"filter\"></div>
                    </div>
                    <div class=\"sginiture\">
                        <img src=". asset('imgs/'.Auth::user()->email.'.svg')." alt=\"\">
                    </div>
                </div>
            </div>
        ";
        $css = "
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
                height: 149px;
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
        ";
        return [$html,$css];
    }
    public function text_2_imgs($data){
        $html = "

        <div class=\"page page-back\">
            <div class=\"area-fp_first_background\">

            </div>

            <div class=\"area-fp_main\">
                <div class=\"area-fp_left\">
                    <div class=\"area-fp_title\">
                        <div class=\"area-fp_title_title\">
                            ".$data['local']->title."
                        </div>
                    </div>
                    <div class=\"area-fp_description\">
                        <p>

                        ".$data['local']->description."
                        </p>

                    </div>
                </div>
                <div class=\"area-fp_right\">
                    <div class=\"wrapper\">
                        <img src=\"".asset('storage/'.$data['local']->img1)."\" alt=\"\">
                        <div class=\"area-filter\"></div>
                    </div>
                    <div class=\"wrapper\">
                        <img src=\"".asset('storage/'.$data['local']->img2)."\" alt=\"\">
                        <div class=\"area-filter\"></div>
                    </div>

                </div>
            </div>


            </div>


        </div>

         ";

            $css = "
            .page-back{
                background-color:#D5DCDD;
            }
            .area-fp_main .wrapper{
                position: relative;
                width:100%;
                height: calc(50% - 10px);
            }
            .area-filter{
                position: absolute;
                top: 0px;
                left: 0px;
                width: 100%;
                height: 100%;
                background-color: rgba(1,20,22,0.4);
                border-radius: 17px;
                mix-blend-mode: soft-light;
            }
            }
            .area-fp_first_background{
                position: absolute;
                width: 100vw;
                height: 100vh;
                top: 0px;
                left:0px;
                z-index: -1;
                background-color:#D5DCDD;
            }

            .area-fp_main{
                box-sizing: border-box;
                background-color:#002D31;
                background-repeat: no-repeat;
                height: calc(100vh - 20px);
                width: calc(100vw - 40px);
                margin: 10px 20px;
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                color: #fff;
                font-family: 'nova';
                padding: 40px 30px;
                overflow: hidden;
                border-radius:13px;
            }
            .area-fp_left{
                width: 48%;
                display: flex;
                flex-direction: column;
                gap: 50px;
            }
            .area-fp_title{
                display: flex;
                width: 450px;
                flex-direction: row;
                align-items: center;
                /* justify-content: space-between; */
                gap: 30px;
            }
            .area-fp_title_img img{
                width: 80px;
                height: 80px;
                -webkit-border-top-left-radius: 20px;
                -webkit-border-bottom-right-radius: 20px;
                -moz-border-radius-topleft: 20px;
                -moz-border-radius-bottomright: 20px;
            }
            .area-fp_title_title{
                font-size: 40px;
                letter-spacing: 3.5px;
            }
            .area-fp_right{
                width: 48%;
                display: flex;
                flex-direction: column;
                gap: 20px;
            }
            .area-fp_right img{
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center;
                border-radius:17px;
            }
            .area-fp_description{
                line-height: 2.3;
                font-family: 'nova';
                height: calc(100% - 20px);
                overflow: hidden;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size:20px;
            }

            ";
        return [$html,$css];
    }
}
