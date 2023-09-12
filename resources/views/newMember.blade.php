<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <div class="panel-heading">
                <h2 class="text-center mt-3">New Members Request</h2>
            </div>
            <!-- Table Start -->
            <div>
                <form class="col-5">
                    @csrf
                    <input class="form control" name="search" type="search" placeholder="Phone Number"
                        aria-label="search">
                    <button class="btn btn-success" type="submit">Search</button>
                    <a href="{{ route('newMember')}}">
                        <button class="btn btn-primary " type="button">
                            Reset
                        </button>
                    </a>
                </form><br>
            </div>
            <div>
                <table id="" class="table table-striped table-bordered">
                    <thead class="table-success text-center">
                        <tr>
                            <th>S.L</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Join Key</th>
                            <th>Approve</th>
                            <th>Reject</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $counter = 0;
                        @endphp
                        @foreach($members as $row)
                        <tr>
                            <td>{{ ++$counter }}</td>
                            <td>{{$row->name}}</td>
                            <td>{{$row->phone}}</td>
                            <form action="approvemember" method="POST">
                                @csrf
                                <input type="hidden" name="key" value="{{$row->key}}">
                                <input type="hidden" name="id" value="{{$row->id}}">
                                <td>{{$row->key}}</td>
                                <td class="text-center">
                                    <button class="btn btn-success" type="submit" name="update">Approve</button>
                                </td>
                            </form>
                            <form action="rejectmember" method="POST">
                                @csrf
                                <td class="text-center">
                                    <button class="btn btn-danger" type="submit" name="delete[{{$row->id}}]"
                                        value="{{$row->id}}" />Reject</button>
                                </td>
                            </form>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Table End -->
            <div class="row">
                {{$members->links()}}
            </div>
        </div>
    </x-slot>
</x-app-layout>
