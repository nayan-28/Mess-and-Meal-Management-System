<x-app-layout>
    <div class="panel-heading">
        <h2 class="text-center mt-3">Mess & Meal Management System</h2>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col" class="text-center">S.L</th>
                <th scope="col" class="text-center">Details</th>
                <th scope="col" class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row" class="text-center">1</th>
                <td>New Members Request</td>
                <td class="text-center"><a href="{{ route('newMember')}}" class="btn btn-primary">
                        View
                    </a>
                </td>
            </tr>
            <tr>
                <th scope="row" class="text-center">2</th>
                <td>Old Members</td>
                <td class="text-center"><a href="{{ route('oldMember')}}" class="btn btn-secondary">View </a>
                </td>
            </tr>
            <tr>
                <th scope="row" class="text-center">3</th>
                <td>Meal Management</td>
                <td class="text-center"><a href="{{route('mealdetails')}}" class="btn btn-success">View
                    </a> </td>
            </tr>
            <tr>
                <th scope="row" class="text-center">4</th>
                <td>Payment</td>
                <td class="text-center"><a href="{{ route('payment')}}" class="btn btn-danger">View </a>
                </td>
            </tr>
            <tr>
                <th scope="row" class="text-center">5</th>
                <td>Bazar Information</td>
                <td class="text-center"><a href="#" class="btn btn-warning">View </a>
                </td>
            </tr>
        </tbody>
    </table>
</x-app-layout>
