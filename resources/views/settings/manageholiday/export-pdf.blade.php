@extends('layout.pdf-template')
@section('title', 'Holidays - '.$year)
@section('content')
<style>
    .tableHeading {
        padding: 15px;
        color: rgb(255 255 255);
        font-size: 16px;
        background: #16609e;
    }

    table{
        font-size: 14px;
        width: 80%;
        margin-top: 12px; 
    }
    tfoot tr td{
        padding: 25px 5px 5px 5px;
        border: none;
    }
    td{
        padding: 5px;
    }
    th{
        background: #225e9c40;
    }
    .logo{
        margin-top: 25px;
    }
    .tableHead{
        padding: 5px;
    }
</style>
<div class="text-center logo">
    <img src="{{asset(Helper::getCompanyLogo())}}" class="img-logo">    
    </div>
<table class="table table-striped" id="holiday_list" border="1">
    <thead>
        @if(Helper::getCompanyLogo())
        <tr >
            <th colspan="2" class="text-center tableHeading">
                Holidays: Year ( {{ $year }} )
            </th>
        </tr>
        @endif
        <tr>
            <th class="tableHead">Date</th>
            <th class="tableHead">Holiday</th>
        </tr>
    </thead>
    <tbody class="text-center">
        @foreach($lists as $key => $list)
        <tr>
            <td class="text-center" style= "border-bottom :{{count($lists)==$key+1 ? 'none': '1px solid gray'}}">
                {{ date('d M Y',strtotime($list->holiday_date)) }}
            </td>
            <td  style= "border-bottom :{{count($lists)==$key+1 ? 'none': '1px solid gray'}}">
                {{$list->holiday_name}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
