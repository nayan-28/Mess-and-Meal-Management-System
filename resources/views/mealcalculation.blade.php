<x-app-layout>
    <div class="container">
        <div class="card mt-5">
            <div class="card-body">
                <div>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Back</a>
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
