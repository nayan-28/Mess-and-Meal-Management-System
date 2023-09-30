<x-app-layout>
    <div class="container">
        <div class="card mt-5">
            <div class="card-body">
                <div>
                    <a href="{{route('dashboard') }}" class="btn btn-warning"><svg xmlns="http://www.w3.org/2000/svg"
                            width="30" height="30" fill="currentColor" class="bi bi-arrow-left-square-fill"
                            viewBox="0 0 16 16">
                            <path
                                d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1z" />
                        </svg></a>
                </div>
                <div class="text-center">
                    <h4>হিসাবের বিবরণী</h4>
                </div>
                <div class="row justify-content-center">
                    <!-- Table Start -->
                    @php
                    $counter = 0;
                    $counttotalmeal = 0;
                    $totaldeposit = 0;
                    $mealcharge = 0;
                    @endphp
                    <div class="table-container">
                        @php
                        $counter = 0;
                        $counttotalmeal = 0;
                        @endphp

                        @foreach($mealdetails as $row)
                        @php
                        $counttotalmeal += ($row->total_morning + $row->total_lunch + $row->total_dinner);
                        @endphp

                        @foreach($bordertotaldeposit as $deposit)
                        @if($deposit->user_id == $row->user_id)
                        @php
                        $totaldeposit += $deposit->total_amount;
                        @endphp
                        @endif
                        @endforeach
                        @endforeach
                        <div class="table-container">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead class="table-success text-center">
                                        <tr>
                                            <th>S.L</th>
                                            <th>সর্বমোট বাঁজার খরচ</th>
                                            <th>সর্বমোট মিল</th>
                                            <th>মিল চার্জ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $counter = 0;
                                        $mealcharge = $counttotalmeal > 0 ? number_format(($totalAmount->totalAmount) /
                                        $counttotalmeal, 2) : 0;
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ ++$counter }}</td>
                                            <td class="text-center">{{ $totalAmount->totalAmount ?? 0 }}</td>
                                            <td class="text-center">{{ $counttotalmeal }}</td>
                                            <td class="text-center">
                                                {{$mealcharge}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-success text-center">
                                    <tr>
                                        <th>S.L</th>
                                        <th>নাম</th>
                                        <th>সর্বমোট মিল</th>
                                        <th>সর্বমোট জমা</th>
                                        <th>খরচ</th>
                                        <th>টাকা পাবে</th>
                                        <th>টাকা দেবে</th>
                                        <th>ক্যাশ আছে</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $counter1 = 0;
                                    $bordermeal = 0;
                                    $totalcost = 0;
                                    $totalbordercost = 0;
                                    $totaldeposit = 0;
                                    $takadebe = 0;
                                    $takapabe = 0;
                                    @endphp
                                    @foreach($mealdetails as $row)
                                    <tr>
                                        <td class="text-center">{{ ++$counter1 }}</td>
                                        <td class="text-center">{{$row->name}}</td>
                                        @php
                                        $bordermeal = ($row->total_morning + $row->total_lunch
                                        +$row->total_dinner);
                                        @endphp
                                        <td class="text-center">
                                            {{$bordermeal}}</td>
                                        <td class="text-center">@foreach($bordertotaldeposit as $deposit)
                                            @if($deposit->user_id == $row->user_id)
                                            {{$deposit->total_amount}}
                                            @php
                                            $totaldeposit += ($deposit->total_amount);
                                            @endphp
                                            @endif
                                            @endforeach</td>
                                        @php
                                        $totalcost=(round($mealcharge * $bordermeal) );
                                        $totalbordercost +=$totalcost;
                                        @endphp
                                        <td class="text-center">
                                            {{$totalcost}}
                                        </td>
                                        <td class="text-center">
                                            @foreach($bordertotaldeposit as $deposit)
                                            @if($deposit->user_id == $row->user_id)
                                            @php
                                            $difference = $deposit->total_amount - $totalcost;
                                            @endphp
                                            @if($difference < 0) {{ 0 }} @else {{ $difference }} @php $takapabe
                                                +=$difference; @endphp @endif @endif @endforeach </td>
                                        <td class="text-center">
                                            @foreach($bordertotaldeposit as $deposit)
                                            @if($deposit->user_id == $row->user_id)
                                            @php
                                            $difference = $deposit->total_amount - $totalcost;
                                            @endphp

                                            @if($difference < 0) {{ abs($difference) }} @php $takadebe
                                                +=abs($difference); @endphp @else {{ 0 }} @endif @endif @endforeach
                                                </td>
                                        <td></td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td class="text-center">মোট</td>
                                        <td class="text-center">{{$totaldeposit}}</td>
                                        <td class="text-center">{{$totalbordercost}}</td>
                                        <td class="text-center">{{$takapabe}}</td>
                                        <td class="text-center">{{$takadebe}}</td>
                                        <td class="text-center">{{$totaldeposit - $totalbordercost}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div style="text-align: center;">
                    <button onclick="printContent()" class="btn btn-warning">Print</button>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
<script>
function printContent() {
    window.print();
}
</script>
