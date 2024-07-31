@if(count($clients)>0)
<div class="table-responsive list">

    <table class="table table-hover listTable">

        <tbody>
            @foreach($clients as $client)

            <tr>
                <td class="client-avatar"><img alt="image" src="@if($client->image){{ asset('storage/'.$client->image) }}@else{{ asset('img/company.png') }}@endif"> </td>
                <td><a onclick="view('{{ $client->id }}')">{{ $client->company_name }}</a></td>
                <td>{{ $client->country }}</td>
                <td class="mail-cell"><i class="ri-mail-fill"> </i> {{ $client->email }}</td>
                <td style="width:15%;">{{\Carbon\Carbon::parse($client->created_at)->format('M d, Y')}}</td>
                <td><a class="edit-client" data-id="{{$client->id}}" data-tooltip="tooltip" data-placement="top" title="Edit"><i class="ri-pencil-line"></i></a></td>
            </tr>

            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <td colspan="4"><strong>Total Number Of Clients : {{ count($clients) }}</strong></td>
            <tr>
        </tfoot>


    </table>

</div>
@else
<div class="alert alert-danger">Client Not Found.</div>
@endif