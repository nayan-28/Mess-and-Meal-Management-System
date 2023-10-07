<x-app-layout>
    @php
    $totals = [];
    @endphp

    @foreach($bordertotaldeposit as $deposit)
    @php
    $user_id = $deposit->user_id;
    $amount = $deposit->amount;
    if (!isset($totals[$user_id])) {
    $totals[$user_id] = 0;
    }
    $totals[$user_id] += $amount;
    @endphp
    @endforeach
    <x-slot name="header">
        <div class="container">
            <div>
                <a href="{{route('dashboard') }}" class="btn btn-warning"><svg xmlns="http://www.w3.org/2000/svg"
                        width="30" height="30" fill="currentColor" class="bi bi-arrow-left-square-fill"
                        viewBox="0 0 16 16">
                        <path
                            d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1z" />
                    </svg></a>
            </div>
            <div class="text-center">
                <h4 class="text-center mt-3">টাকা জমার বিবরণী</h4>
                @if($hideButtons)<p class="card-text">{{ \Carbon\Carbon::now()->format('F Y') }}</p>@endif
                @if($hidedate)
                @php
                $carbon = \Carbon\Carbon::create()->month($month);
                $monthName = $carbon->format('F');
                @endphp
                <h5>{{ $monthName }}</h5>
                @endif
            </div>
            <div class="panel-heading" style="text-align: center;">
                <button class="btn btn-success" data-toggle="modal" data-target="#paymentmonth">অন্য মাসের পেমেন্ট
                    দেখুন</button>
            </div><br>
            <!-- Modal -->
            <div class="modal fade" id="paymentmonth" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">কোন মাসের হিসাব দেখতে
                                চান?
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
            <div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-success text-center">
                            <tr>
                                <th>S.L</th>
                                <th>নাম</th>
                                <th>বাসা ভাড়া</th>
                                <th>ওয়াইফাই বিল</th>
                                <th>বিদ্যুৎ বিল</th>
                                <th>রাধুনী বিল</th>
                                <th>অতিরিক্ত</th>
                                <th>খাবার বাবদ জমা</th>
                                @if($hideButtons)
                                <th>আপডেট</th>
                                <th>খাবার বাবদ জমা করুন</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $counter = 0;
                            @endphp
                            <tr></tr>
                            @foreach($borderPayments as $row)
                            <tr>
                                <form action="{{ route('updatedetails') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $row->user_id }}">
                                    <td>{{ ++$counter }}</td>
                                    <td>{{$row->name}}</td>
                                    @if($hideButtons)
                                    <td><input type="number" name="house_rent" value="{{$row->house_rent}}"
                                            class="form-control form-control-sm"></td>
                                    @endif
                                    @if($hidedate)
                                    <td>{{$row->house_rent}}</td>
                                    @endif
                                    @if($hideButtons)
                                    <td><input type="number" name="wifi_bill" value="{{$row->wifi_bill}}"
                                            class="form-control form-control-sm"></td>
                                    @endif
                                    @if($hidedate)
                                    <td>{{$row->wifi_bill}}</td>
                                    @endif
                                    @if($hideButtons)
                                    <td><input type="number" name="electric_bill" value="{{$row->electric_bill}}"
                                            class="form-control form-control-sm"></td>
                                    @endif
                                    @if($hidedate)
                                    <td>{{$row->electric_bill}}</td>
                                    @endif
                                    @if($hideButtons)
                                    <td><input type="number" name="radhuni_bill" value="{{$row->radhuni_bill}}"
                                            class="form-control form-control-sm"></td>
                                    @endif
                                    @if($hidedate)
                                    <td>{{$row->radhuni_bill}}</td>
                                    @endif
                                    @if($hideButtons)
                                    <td><input type="number" name="extra_bill" value="{{$row->extra_bill}}"
                                            class="form-control form-control-sm"></td>
                                    @endif
                                    @if($hidedate)
                                    <td>{{$row->extra_bill}}</td>
                                    @endif
                                    <td>
                                        @foreach($totals as $user_id => $total)
                                        @if($user_id == $row->user_id)
                                        {{$total}}
                                        @endif
                                        @endforeach
                                    </td>
                                    @if($hideButtons)
                                    <td class="text-center">
                                        <button class="btn btn-primary" type="submit" name="update">✓</button>
                                    </td>

                                </form>
                                <td class="text-center">
                                    <button class="btn btn-primary add-payment-button" type="button" data-toggle="modal"
                                        data-target="#addPaymentModal{{$row->user_id}}"><b>+</b></button>
                                </td>
                                @endif
                            </tr>

                            <!-- Add Payment Modal -->
                            <div class="modal fade" id="addPaymentModal{{$row->user_id}}" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">খাবার বাবদ জমা করুন</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('adddeposit') }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="amount">টাকা</label>
                                                    <input type="number" name="amount" placeholder="Amount"
                                                        class="form-control form-control-sm" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="date">তারিখ</label>
                                                    <input type="date" name="date" class="form-control form-control-sm"
                                                        required min="{{ date('Y-m-d') }}"
                                                        max="{{ date('Y-m-' . date('t')) }}">
                                                </div>
                                                <!-- Add a hidden input field to store user_id -->
                                                <input type="hidden" name="user_id" value="{{$row->user_id}}">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">বন্ধ করুন</button>
                                                <button type="submit" class="btn btn-success">যোগ করুন</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Table End -->
        </div>
        <div style="text-align: center;">
            <button onclick="printContent()" class="btn btn-warning">Print</button>
        </div>
    </x-slot>
</x-app-layout>
<script>
function printContent() {
    window.print();
}
</script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
