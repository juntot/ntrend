<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <link rel="icon" href="./public/images/HRIS.ico" />
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                min-height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                /* text-align: center; */
                padding: 15px;
                max-width: 600px;
            }

            .title {
                font-size: 30px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            .btn-primary{
                background: #3f51b5 !important;
                color: #fff;
                background: rgba(158,158,158,.2);
                box-shadow: 0 2px 2px 0 rgb(0 0 0 / 14%), 0 3px 1px -2px rgb(0 0 0 / 20%), 0 1px 5px 0 rgb(0 0 0 / 12%);
                border: none;
                border-radius: 2px;
                position: relative;
                height: 36px;
                margin: 0;
                min-width: 64px;
                padding: 0 16px;
                display: inline-block;
                font-family: "Roboto","Helvetica","Arial",sans-serif;
                font-size: 14px;
                font-weight: 500;
                text-transform: uppercase;
                letter-spacing: 0;
                overflow: hidden;
                will-change: box-shadow;
                transition: box-shadow .2s cubic-bezier(.4,0,1,1),background-color .2s cubic-bezier(.4,0,.2,1),color .2s cubic-bezier(.4,0,.2,1);
                outline: none;
                cursor: pointer;
                text-decoration: none;
                text-align: center;
                line-height: 36px;
                vertical-align: middle;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))

            @endif

            <div class="content">
                <!-- <div class="title m-b-md">
                    eFORMS TERMS AND CONDITIONS
                </div> -->
                <div>
                    @if (count($markup)>0)
                        <?php
                            echo htmlspecialchars_decode($markup[0]->markup)
                        ?>
                    @endif
                </div>
                <div style="text-align: right;">
                    <button class="btn btn-primary" onclick="window.history.go(-1); return false;"> I agree & understand</button>
                </div>
            </div>
        </div>
    </body>
</html>
