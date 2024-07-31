<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Payroll</title>    
    <style>
    .text-center{
            text-align: center;
    }
    .m-auto{margin: 0 auto}
              .table,th,tr,td{
            border: 1px solid rgb(36, 35, 35);
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
    th{
        margin: 0;
        padding: 5px;
        border-left: none;
        border-spacing: 0ch;
        color: black;
        text-align: left;
    }
    td{
        padding: 5px;
        border: 1px solid rgb(0, 0, 0);     
    } 
    .img-logo{
        width: 10%;
        height: 10%;
        object-fit:contain;
    }   

    body{
        font-size: 13px;
    }
    table{
        width: 75%;
    }
    </style>
</head>
<body>
    @include('payroll.employee-payroll.pdf-view')           
</body>
</html>