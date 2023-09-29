<x-app-layout>
    @if(session('message'))
    <div class="alert alert-success" id="success-message">
        {{ session('message') }}
    </div>

    <script>
    setTimeout(function() {
        document.getElementById('success-message').style.display = 'none';
    }, 2500);
    </script>
    @endif
    <div class="container">
        <div class="card mt-5">
            <div class="card-body">
                <div>
                    <a href="{{route('dashboard') }}" class="btn btn-secondary">Back</a>
                </div>
                <div class="text-center">
                    <h2 class="card-title">Meal Details</h2>
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
                                        <th>নাম</th>
                                        <th>সর্বমোট মিল</th>
                                        <th>সর্বমোট জমা</th>
                                        <th>জমার বিস্তারিত</th>
                                        <th>মিলের তালিকা</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $counter = 0;
                                    @endphp
                                    @foreach($mealdetails as $row)
                                    <tr>
                                        <form action="{{ route('meallist') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $row->user_id }}">
                                            <td class="text-center">{{ ++$counter }}</td>
                                            <td>{{$row->name}}</td>
                                            <td class="text-center">
                                                {{$row->total_morning + $row->total_lunch +$row->total_dinner}}</td>
                                            <td class="text-center">@foreach($bordertotaldeposit as $deposit)
                                                @if($deposit->user_id == $row->user_id)
                                                {{$deposit->total_amount}}
                                                @endif
                                                @endforeach</td>
                                            <td class="text-center">
                                                <button class="btn btn-warning" type="submit"
                                                    name="deposithistory">দেখুন</button>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-primary" type="submit"
                                                    name="meallist">দেখুন</button>
                                            </td>
                                        </form>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
