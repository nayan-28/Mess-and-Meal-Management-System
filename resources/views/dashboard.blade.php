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
                <td>Meal & Deposit Management</td>
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
                <td class="text-center"><a href="{{ route('bazardetails')}}" class="btn btn-warning">View </a>
                </td>
            </tr>
            <tr>
                <th scope="row" class="text-center">6</th>
                <td>Meal Charge Calculation</td>
                <td class="text-center"><button class="btn btn-success" data-toggle="modal"
                        data-target="#addPaymentModal">View</button>
                </td>
                <!-- Modal -->
                <div class="modal fade" id="addPaymentModal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">কোন মাসের হিসাব তৈরি করতে চান?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{route('calculation')}}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group">
                                        <select class="form-select" aria-label="Default select example" name="month"
                                            style="width: 180px; text-align: center;" required>
                                            <option value="" disabled selected>মাস নির্বাচন করুন</option>
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
                                    <button type="submit" class="btn btn-success">হিসাব করুন</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </tr>
        </tbody>
    </table>
</x-app-layout>

<script>
// Get references to the popup card and buttons
const popupCard = document.getElementById('popupCard');
const showPopupBtn = document.getElementById('showPopupBtn');
const closePopupBtn = document.getElementById('closePopupBtn');
const monthName = document.getElementById('monthName');

// Function to show the popup card with the selected Month name
showPopupBtn.addEventListener('click', () => {
    // Replace 'Month Name' with the desired Month name
    const selectedMonth = 'সেপ্টেম্বর'; // Change this to the desired Month
    monthName.textContent = selectedMonth;
    popupCard.style.display = 'block';
});

// Function to close the popup card
closePopupBtn.addEventListener('click', () => {
    popupCard.style.display = 'none';
});
</script>
<style>
/* Custom CSS for the popup card */
.popup-card {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 20px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
    z-index: 9999;
}
</style>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
