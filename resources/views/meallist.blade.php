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
                    <b class="card-title">
                        @foreach($meallist as $index => $row)
                        @if($index === 0)
                        <p>{{$row->name}} এর মিলের তালিকা</p>
                        @endif
                        @endforeach
                    </b>
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
                                        <th>সকাল</th>
                                        <th>দুপুর</th>
                                        <th>রাত</th>
                                        <th>তারিখ</th>
                                        <th>আপডেট করুন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $totalMeals = 0;
                                    $counter = 0;
                                    @endphp
                                    @foreach($meallist as $row)
                                    <tr>
                                        <form action="{{ route('updatemeallist') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $row->id }}">
                                            <input type="hidden" name="user_id" value="{{ $row->user_id }}">
                                            <td class="text-center">{{ ++$counter }}</td>
                                            <td><input type="number" name="morning" value="{{$row->morning}}"
                                                    class="form-control form-control-sm"></td>
                                            <td><input type="number" name="lunch" value="{{$row->lunch}}"
                                                    class="form-control form-control-sm"></td>
                                            <td><input type="number" name="dinner" value="{{$row->dinner}}"
                                                    class="form-control form-control-sm"></td>
                                            <td class="text-center">{{$row->date}}</td>
                                            <td class="text-center">
                                                <button type="submit" class="btn btn-primary">✓</button>
                                            </td>
                                        </form>
                                    </tr>
                                    @php
                                    $totalMeals += ($row->morning + $row->lunch + $row->dinner);
                                    @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="5"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">সর্বমোট মিল</td>
                                        <td class="text-center" colspan="3">
                                            <b>{{$totalMeals}}</b>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
