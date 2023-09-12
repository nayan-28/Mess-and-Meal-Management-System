<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <div class="panel-heading">
                <h2 class="text-center mt-3">Payment Details</h2>
                <p class="text-center mt-3">{{ \Carbon\Carbon::now()->format('F Y') }}</p>
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
                                <th>খাবার বাবদ জমা</th>
                                <th>Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $counter = 0;
                            @endphp
                            @foreach($borderPayments as $row)
                            <tr>
                                <form action="{{ route('updatedetails') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $row->id }}">
                                    <td>{{ ++$counter }}</td>
                                    <td>{{$row->name}}</td>
                                    <td><input type="text" name="house_rent" value="{{$row->house_rent}}"
                                            class="form-control form-control-sm"></td>
                                    <td><input type="text" name="wifi_bill" value="{{$row->wifi_bill}}"
                                            class="form-control form-control-sm"></td>
                                    <td><input type="text" name="electric_bill" value="{{$row->electric_bill}}"
                                            class="form-control form-control-sm"></td>
                                    <td><input type="text" name="radhuni_bill" value="{{$row->radhuni_bill}}"
                                            class="form-control form-control-sm"></td>
                                    <td><input type="text" name="extra_bill" value="{{$row->extra_bill}}"
                                            class="form-control form-control-sm"></td>
                                    <td class="text-center">
                                        <button class="btn btn-primary" type="submit" name="update">Update</button>
                                    </td>
                                </form>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            </div>
            <!-- Table End -->
        </div>

    </x-slot>
</x-app-layout>
