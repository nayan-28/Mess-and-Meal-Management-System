<x-border-app-layout>
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
                <div class="panel-heading">
                    <h2 class="text-center mt-3">Payment Details</h2>
                </div>
                <div class="panel-heading" style="text-align: center;">
                    <button class="btn btn-success" data-toggle="modal" data-target="#paymentdetailsmonth">অন্য মাসের
                        হিসাব
                        দেখুন</button>
                </div><br>
                <!-- Modal -->
                <div class="modal fade" id="paymentdetailsmonth" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                @if($difference < 0) {{ abs($difference) }} @php $takadebe
                                                    +=abs($difference); @endphp @else {{ 0 }} @endif @endforeach </td>
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
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($row->date)->format('F') }}
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-border-app-layout>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
