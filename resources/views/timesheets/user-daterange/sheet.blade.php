<div class="ibox-content">
    <div id="printTable" class="table-responsive">
        <table class="table table-bordered dataUser">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Project</th>
                    <th>Task</th>
                    <th>Time Spent</th>
                    <th>Billed</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dataset as $data)
                <tr>
                    <td>{{$data['created_at']}}</td>
                    <td>{{$data['project_name']}}</td>
                    <td>{{$data['title']}}</td>
                    <td>{{floor($data['total']/60).'h '.($data['total']%60).'m'}}</td>
                    <td>{{floor($data['billed']/60).'h '.($data['billed']%60).'m'}}</td>
                </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td>Employee Name : <strong>{{$employee_name}}</strong></td>
                    <td>Total: <strong>{{floor($total['time_spent']/60).'h '.($total['time_spent']%60).'m'}}</strong>
                    </td>
                    <td>Total: <strong>{{floor($total['billed']/60).'h '.($total['billed']%60).'m'}}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>