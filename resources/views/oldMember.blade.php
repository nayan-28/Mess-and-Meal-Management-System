<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <div class="panel-heading">
                <h2 class="text-center mt-3">Approved Members</h2>
            </div>
            <!-- Table Start -->
            <div>
                <form class="col-5">
                    @csrf
                    <input class="form control" name="search" type="search" placeholder="Phone Number"
                        aria-label="search">
                    <button class="btn btn-success" type="submit">Search</button>
                    <a href="{{ route('oldMember') }}">
                        <button class="btn btn-primary " type="button">
                            Reset
                        </button>
                    </a>
                </form>
            </div><br>
            <div>
                <table id="" class="table table-striped table-bordered">
                    <thead class="table-success text-center">
                        <tr>
                            <th>S.L</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Join Key</th>
                            <th>Remove</th>
                            <th>Add For Current Month</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $counter = 0;
                        $currentMonth = date('Y-m'); // Get the current month in 'YYYY-MM' format
                        @endphp

                        @foreach($members as $row)
                        <tr>
                            <td>{{ ++$counter }}</td>
                            <td>{{$row->name}}</td>
                            <td>{{$row->phone}}</td>
                            <td>{{$row->key}}</td>
                            <form action="removemember" method="POST">
                                @csrf
                                <td class="text-center">
                                    <button class="btn btn-danger" type="submit" name="remove[{{$row->id}}]"
                                        value="{{$row->id}}">Remove</button>
                                </td>
                            </form>

                            @php
                            $isCurrentMonth = date('Y-m', strtotime($row->date)) == $currentMonth;
                            @endphp

                            <form action="addcurrentmember" method="POST">
                                @csrf
                                <input type="hidden" name="key" value="{{$row->key}}">
                                <input type="hidden" name="id" value="{{$row->id}}">
                                <td class="text-center">
                                    <button class="btn btn-success" type="submit" name="update" @if ($isCurrentMonth)
                                        disabled @endif>
                                        Add
                                    </button>
                                </td>
                            </form>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
            <!-- Table End -->
        </div>
    </x-slot>
</x-app-layout>
