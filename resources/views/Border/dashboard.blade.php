<x-border-app-layout>
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
                <td>Meal Details</td>
                <td class="text-center"><a href="{{ route('border.mealdetails')}}" class="btn btn-primary">
                        View
                    </a>
                </td>
            </tr>
            <tr>
                <th scope="row" class="text-center">2</th>
                <td>Payment Details</td>
                <td class="text-center"><a href="{{ route('border.paymentdetails')}}" class=" btn btn-secondary">View
                    </a>
                </td>
            </tr>
            <tr>
                <th scope="row" class="text-center">3</th>
                <td>Meal & Others Information</td>
                <td class="text-center"><a href="#" class="btn btn-success">View
                    </a> </td>
            </tr>
        </tbody>
    </table>
</x-border-app-layout>