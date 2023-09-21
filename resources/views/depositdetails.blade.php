<x-app-layout>
    <div class="container">
        <div class="card mt-5">
            <div class="card-body">
                <div>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                </div>
                <div class="text-center">
                    <h4>টাকা জমার বিস্তারিত</h4>
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
                                        <th>জমার পরিমাণ</th>
                                        <th>তারিখ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $totaldeposit = 0;
                                    $counter = 0;
                                    @endphp
                                    @foreach($deposithistory as $row)
                                    <tr>
                                        <td class="text-center">{{ ++$counter }}</td>
                                        <td class="text-center">{{$row->amount}}</td>
                                        <td class="text-center">{{$row->date}}</td>
                                    </tr>
                                    @php
                                    $totaldeposit += $row->amount;
                                    @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">সর্বমোট জমা</td>
                                        <td class="text-center">{{$totaldeposit}}</td>
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
