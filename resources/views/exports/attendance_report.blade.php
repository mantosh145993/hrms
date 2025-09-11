<table>
    <thead>
        <tr>
            <th>Employee</th>
            <th>Present</th>
            <th>Half Day</th>
            <th>Holiday</th>
            <th>Sunday</th>
            <th>On Leave</th>
            <th>Paid Leave</th>
            <th>Absent</th>
            <th>Total Days</th>
        </tr>
    </thead>
    <tbody>
        @foreach($report as $row)
            <tr>
                <td>{{ $row->user->name }}</td>
                <td>{{ $row->present_count }}</td>
                <td>{{ $row->halfday_count }}</td>
                <td>{{ $row->holiday_count }}</td>
                <td>{{ $row->sunday_count }}</td>
                <td>{{ $row->leave_count }}</td>
                <td>{{ $row->paid_leave_count }}</td>
                <td>{{ $row->absent_count }}</td>
                <td>
                    {{ $row->present_count + $row->halfday_count + $row->holiday_count + 
                       $row->sunday_count + $row->leave_count + $row->absent_count }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
