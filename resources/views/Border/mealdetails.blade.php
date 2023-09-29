<x-border-app-layout>
    <div class="container">
        <div class="panel-heading">
            <h2 class="text-center mt-3">Meal Details</h2>
            <p class="text-center mt-3">{{ \Carbon\Carbon::now()->format('F Y') }}</p>
        </div>
        <div class="panel-heading" style="text-align: center;">
            <button class="btn btn-success" data-toggle="modal" data-target="#bazardetailsmonth">অন্য মাসের
                মিলের
                তালিকা
                দেখুন</button>
        </div><br>
        <!-- Modal -->
        <div class="modal fade" id="bazardetailsmonth" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
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
        <!-- Table Start -->
        <div>
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
                            <th>যোগ করুন</th>
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
                        </tr>
                        @php
                        $totalMeals += ($row->morning + $row->lunch + $row->dinner);
                        @endphp
                        @endforeach
                        <tr>
                            <td>সর্বমোট মিল সংখ্যা</td>
                            <td colspan="4">{{$totalMeals}}</td>
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
                                <td><input type="date" name="date" class="form-control form-control-sm"></td>
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
        <!-- Table End -->
    </div>
</x-border-app-layout>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
