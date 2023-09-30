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
                    <h4>বাঁজার তালিকা</h4>
                    <p class="card-text">{{ \Carbon\Carbon::now()->format('F Y') }}</p>
                </div>
                <div class="panel-heading" style="text-align: center;">
                    <button class="btn btn-success" data-toggle="modal" data-target="#bazardetailsmonth">অন্য মাসের
                        বাঁজার
                        তালিকা
                        দেখুন</button>
                </div><br>
                <!-- Modal -->
                <div class="modal fade" id="bazardetailsmonth" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">কোন মাসের বাঁজার
                                    তালিকা দেখতে চান?
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
                <div class="row justify-content-center">
                    <!-- Table Start -->
                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-success text-center">
                                    <tr>
                                        <th>S.L</th>
                                        <th>বাজারের বিবরণ</th>
                                        <th>টাকার পরিমাণ</th>
                                        <th>তারিখ</th>
                                        <th>বাজারকারী</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $totalcosts = 0;
                                    $counter = 0;
                                    @endphp
                                    @foreach($bazardetails as $row)
                                    <tr>
                                        <td class="text-center">{{ ++$counter }}</td>
                                        <td class="text-center">{{$row->bazardetails}}</td>
                                        <td class="text-center">{{$row->amount}} টাকা</td>
                                        <td class="text-center">{{$row->date}}</td>
                                        <td class="text-center">@foreach($border as $borderRow)
                                            @if($borderRow->id == $row->user_id)
                                            {{$borderRow->name}}
                                            @endif
                                            @endforeach</td>
                                        <td></td>
                                    </tr>
                                    @php
                                    $totalcosts += ($row ->amount);
                                    @endphp
                                    @endforeach
                                    <tr>
                                        <th>সর্বমোট খরচ</th>
                                        <td></td>
                                        <td class="text-center"><b>{{$totalcosts}}</b> টাকা</td>
                                        <td></td>
                                        <td></td>
                                        @if($hideButtons)
                                        <th>যোগ করুন</th>
                                        @endif
                                    </tr>
                                    @if($hideButtons)
                                    <tr>
                                        <form action="{{route('addbazar')}}" method="POST">
                                            @csrf
                                            <td></td>
                                            <td><input type="text" name="details" placeholder="বিবরণ লিখুন"
                                                    class="form-control form-control-sm" required></td>
                                            <td><input type="number" name="amount" placeholder="ইংরেজিতে লিখুন"
                                                    class="form-control form-control-sm" required></td>
                                            <td><input type="date" name="date" class="form-control form-control-sm"
                                                    required>
                                            <td>
                                                <select class="form-select" aria-label="Default select example"
                                                    name="id" required>
                                                    <option value="" disabled selected>নির্বাচন করুন</option>
                                                    @foreach($border as $bordername)
                                                    <option value="{{$bordername->id}}">{{$bordername->name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-primary" type="submit" name="addbazar">✓</button>
                                            </td>
                                        </form>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Table Start -->
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
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
