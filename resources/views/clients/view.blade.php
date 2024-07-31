<div class="panel-body">

    <div id="contact-1" class="tab-pane active">
        @if($oneClient)
        <div class="row m-b-lg">

            <div class="col-lg-4 text-center">
                <div class="m-b-sm">

                    <img alt="image" class="img-circle" src="@if($oneClient->image){{ asset('storage/'.$oneClient->image) }}@else{{ asset('img/company.png') }}@endif" style="width: 62px">
                </div>
            </div>
            <div class="col-lg-8">
                <h2> <strong>{{$oneClient->company_name}}</strong> </h2>
            </div>
        </div>
        <div class="client">

            <ul class="list-group clear-list">

                <li class="list-group-item fist-item">

                    <span class="pull-right">{{ $oneClient->email }} </span>

                    Email

                </li>

                <li class="list-group-item">

                    <span class="pull-right"> {{ $oneClient->country}} </span>

                    Country

                </li>

                <li class="list-group-item">

                    <span class="pull-right"> {{ $oneClient->currency }}</span>

                    Currency

                </li>

                <li class="list-group-item">

                    <span class="pull-right"> {{ $oneClient->state }} </span>

                    State

                </li>

                <li class="list-group-item">

                    <span class="pull-right"> {{ $oneClient->city }} </span>

                    City

                </li>
                <li class="list-group-item">

                    <span class="pull-right"> {{ $oneClient->address }} </span>

                    Address

                </li>
                <li class="list-group-item">

                    <span class="pull-right"> {{ $oneClient->phone }} </span>

                    Phone

                </li>
                <li class="list-group-item">

                    <span class="pull-right"> {{ $oneClient->post_code }} </span>

                    Zip Code

                </li>

                <li class="list-group-item">

                    <span class="pull-right"> {{ $oneClient->vat_id }} </span>

                    VAT ID / TAX ID

                </li>

                <li class="list-group-item">

                    <span class="pull-right"> {{ $oneClient->account_manager->full_name ?? 'None Assigned' }} </span>

                    Assigned Account Manager

                </li>

            </ul>

            <strong>Projects</strong>
            <ol>
                @foreach($oneClient->project as $list)
                <li> {{ $list->project_name }}</li>
                @endforeach
            </ol>

        </div>
        @else
        <div class="alert alert-danger">No Clients Added to the system.</div>
        @endif
    </div>

</div>