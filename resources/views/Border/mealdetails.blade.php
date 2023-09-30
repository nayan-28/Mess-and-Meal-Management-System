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
                    <h2 class="text-center mt-3">Meal Details</h2>
                    <p class="text-center mt-3">{{ \Carbon\Carbon::now()->format('F Y') }}</p>
                </div>
                <div class="panel-heading" style="text-align: center;">
                    <button class="btn btn-success" data-toggle="modal" data-target="#bazardetailsmonth">অন্য
                        মাসের
                        মিলের
                        তালিকা
                        দেখুন</button>
                </div><br>
                <!-- Modal -->
                <div class="modal fade" id="bazardetailsmonth" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">কোন মাসের মিল
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
            </div>
            <!-- Table Start -->
            <div class="row justify-content-center">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-success text-center">
                            <tr>
                                <th>S.L</th>
                                <th>সকাল</th>
                                <th>দুপুর</th>
                                <th>রাত</th>
                                <th>তারিখ</th>
                                @if($hideButtons)
                                <th></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $counter = 0;
                            $totalMeals = 0;
                            @endphp
                            <tr></tr>
                            @foreach($bordermeals as $row)
                            <tr>
                                <td class="text-center">{{ ++$counter }}</td>
                                <td class="text-center">{{$row->morning}}</td>
                                <td class="text-center">{{$row->lunch}}</td>
                                <td class="text-center">{{$row->dinner}}</td>
                                <td class="text-center">{{$row->date}}</td>
                                <td></td>
                            </tr>
                            @php
                            $totalMeals += ($row->morning + $row->lunch + $row->dinner);
                            @endphp
                            @endforeach
                            <tr>
                                <th>সর্বমোট মিল সংখ্যা</th>
                                <td colspan="4" class="text-center">{{$totalMeals}}</td>
                                <th>যোগ করুন</th>
                            </tr>
                            @if($hideButtons)
                            <tr>
                                <form action="{{ route('border.addmeals') }}" method="POST">
                                    @csrf
                                    <td></td>
                                    <td><input type="number" name="morning" placeholder="দশমিক সংখ্যা অগ্রহণযোগ্য"
                                            class="form-control form-control-sm" required></td>
                                    <td><input type="number" name="lunch" placeholder="দশমিক সংখ্যা অগ্রহণযোগ্য"
                                            class="form-control form-control-sm" required></td>
                                    <td><input type="number" placeholder="দশমিক সংখ্যা অগ্রহণযোগ্য" name="dinner"
                                            class="form-control form-control-sm" required></td>
                                    <td><input type="date" name="date" class="form-control form-control-sm">
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-primary" type="submit" name="addmeal">✓</button>
                                    </td>
                                </form>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-border-app-layout>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
