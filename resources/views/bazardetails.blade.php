<x-app-layout>
    <div class="container">
        <div class="card mt-5">
            <div class="card-body">
                <div>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                </div>
                <div class="text-center">
                    <h4>বাঁজার তালিকা</h4>
                    <p class="card-text">{{ \Carbon\Carbon::now()->format('F Y') }}</p>
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
                                        <td class="text-center">{{$row->amount}}</td>
                                        <td class="text-center">{{$row->date}}</td>
                                        <td class="text-center">@foreach($border as $borderRow)
                                            @if($borderRow->id == $row->user_id)
                                            {{$borderRow->name}}
                                            @endif
                                            @endforeach</td>
                                    </tr>
                                    @php
                                    $totalcosts += ($row ->amount);
                                    @endphp
                                    @endforeach
                                    <tr>
                                        <th>সর্বমোট খরচ</th>
                                        <td></td>
                                        <td class="text-center"><b>{{$totalcosts}}</b></td>
                                        <td></td>
                                        <td></td>
                                        <th>যোগ করুন</th>
                                    </tr>
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
                                                    <option value="">নির্বাচন করুন</option>
                                                    @foreach($border as $bordername)
                                                    <option value="{{$bordername->id}}">{{$bordername->name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-primary" type="submit" name="addbazar">+</button>
                                            </td>
                                        </form>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Table Start -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
