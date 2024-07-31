@extends('layout.main')

@section('content')
<div class="row">
    <div class="col-md-7 pull-left">
        <strong>
            <h2 class="page-title">View Santas</h3>
        </strong>
    </div>
    <div class="col-md-5 text-right ml-auto m-b-30">
        <a href="{{ route('santa-members.index') }}" class="btn btn-w-m btn-success" > Go Back</a>
    </div>
</div>
<div class="list animated fadeInUp">
	<div class="ibox-content panel session-list">
        <table class="table table-hover santa-table">

            <thead>

                <tr>
                    <th>User</th>

                    <th>Secret Santa</th>

                </tr>

            </thead>

            <tbody>

                @foreach($pairs as $pair)

                <tr>
                    <td>{{$pair['santa']}}</td>
                    <td>{{$pair['giftee']}}</td>
                </tr>
                @endforeach

            </tbody>

        </table>

    </div>
</div>
@endsection

