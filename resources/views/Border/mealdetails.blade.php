<x-border-app-layout>
    <div class="container">
        <div class="panel-heading">
            <h2 class="text-center mt-3">Meal Details</h2>
            <p class="text-center mt-3">{{ \Carbon\Carbon::now()->format('F Y') }}</p>
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
                            <th>যোগ করুন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $counter = 0;
                        $totalMeals = 0;
                        @endphp
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
                                    <button class="btn btn-primary" type="submit" name="addmeal">+</button>
                                </td>
                            </form>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Table End -->
    </div>
</x-border-app-layout>
