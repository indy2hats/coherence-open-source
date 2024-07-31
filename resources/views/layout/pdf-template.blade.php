<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            @yield('title', 'Efficiency & Project Management System')
        </title>
        <style>
        .text-center{
                text-align: center;
        }
        table{
            margin: 0 auto;
            width: 100%;
        }
        .img-logo{
                width: 20%;
                height: 20%;
        }
        .text-center{
            text-align: center;
        }
        .text-left{
            text-align: left;
        }
        .m-auto{
            margin: 0 auto;
        } 
        .img-logo{
            width: 150px;
            height: 150px;
        }   

        body{
            font-size: 13px;
        }

        table th, td, tr{
            border: none
        }
        </style>
    </head>
    <body>
        @yield('content')    
    </body>
</html>