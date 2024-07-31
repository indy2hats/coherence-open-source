<div class="ibox-content">
    <div id="printTable">
        <table class="table table-bordered dataproject">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Task</th>
                    <th>User</th>
                    <th>Time Spent</th>
                    <th>Billed</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dataset as $data)
                <tr>
                    <td>{{$data['created_at']}}</td>
                    <td>{{$data['title']}}</td>
                    <td>{{$data['user_name']}}</td>
                    <td>{{floor($data['total']/60).'h '.($data['total']%60).'m'}}</td>
                    <td>{{floor($data['billed']/60).'h '.($data['billed']%60).'m'}}</td>
                </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td>Project Name : <strong>{{$project_name}}</strong></td>
                    <td>Total: <strong>{{floor($total['time_spent']/60).'h '.($total['time_spent']%60).'m'}}</strong></td>
                    <td>Total: <strong>{{floor($total['billed']/60).'h '.($total['billed']%60).'m'}}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>