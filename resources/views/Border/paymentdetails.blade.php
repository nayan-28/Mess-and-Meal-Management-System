<x-border-app-layout>
    <div class="container">
        <div class="panel-heading">
            <h2 class="text-center mt-3">Payment Details</h2>
        </div>
        <div class="panel-heading" style="text-align: center;">
            <button class="btn btn-success" data-toggle="modal" data-target="#paymentdetailsmonth">অন্য মাসের
                হিসাব
                দেখুন</button>
        </div><br>
        <!-- Modal -->
        <div class="modal fade" id="paymentdetailsmonth" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">কোন মাসের হিসাব দেখতে চান?
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form>
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <select class="form-select" aria-label="Default select example" name="month"
                                    style="width: 180px; text-align: center;" required>
                                    <option value="" disabled selected>মাস নির্বাচন করুন
                                    </option>
                                    @for ($i = 1; $i <= 12; $i++) <option value="{{ $i }}">
                                        {{ \Carbon\Carbon::createFromDate(null, $i, 1)->format('F') }}
                                        </option>
                                        @endfor
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">বন্ধ
                                করুন</button>
                            <button type="submit" class="btn btn-success">দেখুন</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Table Start -->
        <div class="row justify-content-center">
            <!-- Table Start -->
            @php
            $counter = 0;
            $counttotalmeal = 0;
            $totalMeals = 0;
            $totaldeposit = 0;
            $mealcharge = 0;
            $takadebe = 0;
            $takapabe = 0;
            $totalcost = 0;
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
                @foreach($bordermeals as $row)
                @php
                $totalMeals += ($row->morning + $row->lunch + $row->dinner);
                @endphp
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
                                    <th>আমার মিল</th>
                                    <th>খরচ</th>
                                    <th>পাবো</th>
                                    <th>দিতে হবে</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $counter = 0;
                                $mealcharge = $counttotalmeal > 0 ? number_format(($totalAmount->totalAmount) /
                                $counttotalmeal, 2) : 0;
                                $totalcost=(round($mealcharge * $totalMeals) );
                                @endphp
                                <tr>
                                    <td class="text-center">{{ ++$counter }}</td>
                                    <td class="text-center">{{ $totalAmount->totalAmount ?? 0 }}</td>
                                    <td class="text-center">{{ $counttotalmeal }}</td>
                                    <td class="text-center">
                                        {{$mealcharge}}
                                    </td>
                                    <td class="text-center">{{$totalMeals}}</td>
                                    <td class="text-center">{{$totalcost}}</td>
                                    <td class="text-center">
                                        @foreach($bordertotaldeposit as $deposit)
                                        @php
                                        $difference = $deposit->total_amount - $totalcost;
                                        @endphp
                                        @if($difference < 0) {{ 0 }} @else {{ $difference }} @php $takapabe
                                            +=$difference; @endphp @endif @endforeach </td>
                                    <td class="text-center">
                                        @foreach($bordertotaldeposit as $deposit)
                                        @php
                                        $difference = $deposit->total_amount - $totalcost;
                                        @endphp
                                        @if($difference < 0) {{ abs($difference) }} @php $takadebe +=abs($difference);
                                            @endphp @else {{ 0 }} @endif @endforeach </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
                                        <th>অতিরিক্ত জমা</th>
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
                                        <td class="text-center">{{ ++$counter }}</td>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($row->date)->format('F') }}
                                        </td>
                                        <td class="text-center">{{$row->house_rent}}</td>
                                        <td class="text-center">{{$row->wifi_bill}}</td>
                                        <td class="text-center">{{$row->electric_bill}}</td>
                                        <td class="text-center">{{$row->radhuni_bill}}</td>
                                        <td class="text-center">{{$row->extra_bill}}</td>
                                        <td class="text-center">
                                            @foreach($bordertotaldeposit as $deposit)
                                            {{ isset($deposit->total_amount) ? $deposit->total_amount : 0 }}
                                            @endforeach
                                        </td class="text-center">
                                        <td class="text-center">
                                            {{$row->house_rent + $row->wifi_bill + $row->electric_bill + $row->radhuni_bill + $row->extra_bill + (isset($deposit->total_amount) ? $deposit->total_amount : 0)}}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Table End -->
                    </div>
</x-border-app-layout>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
