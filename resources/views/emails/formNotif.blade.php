<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        body{
            font-size: 2em;
            color: #333;
            /* background: #e9ebee; */
        }
        .white-text{
            color: white;
        }
        .container{
            max-width: 600px;
        }
        .dark-purple-bg{
            background: #303F9F;
        }
        .glyphicon{
            color:#E3F2FD;
            font-size: xx-large;
        }
        .intro-text{
            padding:10px;

        }
    </style>
</head>
<body style="background: #e9ebee;">
    <center>
    <table style="margin: 0 auto; text-align: center; color: white; width: 800px;" >
        <thead style="background: #303F9F; padding: 60px 0px; display: block;">
            <tr>
                <td style="width: 1366px;">
                    <img src="http://dev.northtrend.com/public/images/mailer/mail-info-01.png"
                    style="width: 90px; height: auto;"
                    alt="information">
                </td>
            </tr>
            <tr>
                <td style="padding: 10px;">
                    <h1>Approval Request</h1>
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 20 20 20x; font-size: 14px;"> {{date("l\, jS \of F Y")}}</td>
            </tr>
        </thead>
        <tbody style="background: white; padding: 80px 15px; display: block; color: #333; text-align: center;">
            <tr>
                <td style="
                    width: 1366px;
                    font-size: 14px;
                ">
                    <!-- Your Approval is Requested by <span style="color:#F97000">{{$name}}</span>
                    <br><br>for <span style="color:#F97000">{{$formType}}</span> -->

                <div>
                    <h1 style="text-align: center; color: #303f9f; margin-top: 0;"><center>Exceltrend Portal</center></h1>
                    <h1 style="text-align: center;"><?php
                        echo htmlspecialchars_decode($emailBody);
                    ?>
                    </h1>
                </div>
            </td>
            </tr>
            <tr>
                <td style="padding-top: 50px;">
                    <a style="cursor: pointer;" href="http://dev.northtrend.com">
                        <button style="
                        text-decoration: none;
                        cursor: pointer;
                        display: inline-block;
                        margin-bottom: 0;
                        font-weight: 400;
                        text-align: center;
                        white-space: nowrap;
                        vertical-align: middle;
                        -ms-touch-action: manipulation;
                        touch-action: manipulation;
                        cursor: pointer;
                        background-image: none;
                        border: 1px solid transparent;
                        padding: 15px 22px;
                        font-size: 14px;
                        line-height: 1.42857143;
                        border-radius: 8px;
                        background: #303F9F;
                        color: white;
                        font-size: 16px;
                        ">
                        Click here to view <br>
                        the details of the request
                        </button>
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
    </center>
</body>
</html>