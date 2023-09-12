<x-border-app-layout>
    <div class="container">
        <div class="panel-heading">
            <h2 class="text-center mt-3">Payment Details</h2>
        </div>
        <!-- Table Start -->
        <div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-success text-center">
                        <tr>
                            <th>S.L</th>
                            <th>মাস</th>
                            <th>বাসা ভাড়া</th>
                            <th>ওয়াইফাই বিল</th>
                            <th>বিদ্যুৎ বিল</th>
                            <th>রাধুনী বিল</th>
                            <th>খাবার বাবদ জমা</th>
                            <th>সর্বমোট</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $counter = 0;
                        @endphp
                        @foreach($borderPayments as $row)
                        <tr>
                            <td>{{ ++$counter }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->date)->format('F') }}</td>
                            <td>{{$row->house_rent}}</td>
                            <td>{{$row->wifi_bill}}</td>
                            <td>{{$row->electric_bill}}</td>
                            <td>{{$row->radhuni_bill}}</td>
                            <td>{{$row->extra_bill}}</td>
                            <td>{{$row->house_rent + $row->wifi_bill + $row->electric_bill + $row->radhuni_bill + $row->extra_bill}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        <!-- Table End -->
    </div>
</x-border-app-layout>
