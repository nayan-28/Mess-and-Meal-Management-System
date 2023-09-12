<x-app-layout>
    <div class="container">
        <div class="card mt-5">
            <div class="card-body">
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
                                        <td colspan="5">Name</td>
                                    </tr>
                                    <tr>
                                        <th>S.L</th>
                                        <th>সকাল</th>
                                        <th>দুপুর</th>
                                        <th>রাত</th>
                                        <th>তারিখ</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Table End -->
                    <!-- Table Start -->
                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-success text-center">
                                    <tr>
                                        <td colspan="5">Name</td>
                                    </tr>
                                    <tr>
                                        <th>S.L</th>
                                        <th>সকাল</th>
                                        <th>দুপুর</th>
                                        <th>রাত</th>
                                        <th>তারিখ</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Table Start -->
                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-success text-center">
                                    <tr>
                                        <td colspan="5">Name</td>
                                    </tr>
                                    <tr>
                                        <th>S.L</th>
                                        <th>সকাল</th>
                                        <th>দুপুর</th>
                                        <th>রাত</th>
                                        <th>তারিখ</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
.table-container {
    width: 25%;
    /* Adjust the width as needed */
    display: inline-block;
    margin-right: 15px;
    /* Add some space between tables */
}
</style>
