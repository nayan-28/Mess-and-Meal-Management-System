<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <div class="panel-heading">
                <h2 class="text-center mt-3">টাকা জমার বিবরণী</h2>
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
                                <th>অতিরিক্ত</th>
                                <th>খাবার বাবদ জমা</th>
                                <th>আপডেট</th>
                                <th>খাবার বাবদ জমা করুন</th>
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
                                    <input type="hidden" name="id" value="{{ $row->user_id }}">
                                    <td>{{ ++$counter }}</td>
                                    <td>{{$row->name}}</td>
                                    <td><input type="number" name="house_rent" value="{{$row->house_rent}}"
                                            class="form-control form-control-sm"></td>
                                    <td><input type="number" name="wifi_bill" value="{{$row->wifi_bill}}"
                                            class="form-control form-control-sm"></td>
                                    <td><input type="number" name="electric_bill" value="{{$row->electric_bill}}"
                                            class="form-control form-control-sm"></td>
                                    <td><input type="number" name="radhuni_bill" value="{{$row->radhuni_bill}}"
                                            class="form-control form-control-sm"></td>
                                    <td><input type="number" name="extra_bill" value="{{$row->extra_bill}}"
                                            class="form-control form-control-sm"></td>
                                    <td>
                                        @foreach($bordertotaldeposit as $deposit)
                                        @if($deposit->user_id == $row->user_id)
                                        {{$deposit->total_amount}}
                                        @endif
                                        @endforeach
                                    </td>

                                    <td class="text-center">
                                        <button class="btn btn-primary" type="submit" name="update">Update</button>
                                    </td>

                                </form>
                                <td class="text-center">
                                    <button class="btn btn-primary add-payment-button" type="button" data-toggle="modal"
                                        data-target="addPaymentModal" value="{{$row->user_id}}"><b>+</b></button>
                                </td>
                            </tr>

                            <!-- Add Payment Modal -->
                            <!-- The pop-up/modal container -->
                            <div id="addPaymentModal" class="modal">
                                <div class="modal-content">
                                    <span class="close">&times;</span>
                                    <h3 class="modal-title">খাবার বাবদ জমা করুন</h3>
                                    <!-- Form to enter Name, Amount, and Date -->
                                    <form action="{{ route('adddeposit') }}" method="POST">
                                        @csrf
                                        <!-- Input fields for Name, Amount, and Date -->
                                        <div class="form-group">
                                            <label for="amount">টাকা</label>
                                            <input type="number" name="amount" placeholder="Amount"
                                                class="form-control form-control-sm" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="date">তারিখ</label>
                                            <input type="date" name="date" class="form-control form-control-sm"
                                                required>
                                        </div>

                                        <!-- Add a hidden input field to store user_id -->
                                        <input type="hidden" name="user_id" value="{{$row->user_id}}">

                                        <!-- Submit button to add the payment -->
                                        <button type="submit" class="btn btn-success">যোগ করুন</button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Table End -->
        </div>
    </x-slot>
</x-app-layout>
<style>
/* The modal (hidden by default) */
/* Modal container */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.7);
}

/* Modal content */
.modal-content {
    background-color: #fff;
    margin: 10% auto;
    padding: 20px;
    border: none;
    width: 50%;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    position: relative;
}

/* Close button for the modal */
.close {
    position: absolute;
    top: 10px;
    right: 10px;
    color: #aaa;
    font-size: 20px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: #333;
}

/* Modal title */
.modal-title {
    font-size: 24px;
    margin-bottom: 20px;
}

/* Form inputs */
.form-group {
    margin-bottom: 15px;
}

/* Submit button */
.btn-success {
    background-color: #4CAF50;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 18px;
}

.btn-success:hover {
    background-color: #45a049;
}
</style>

<script>
// Get the modal element
var modal = document.getElementById('addPaymentModal');

// Get all buttons with class "add-payment-button"
var addPaymentButtons = document.querySelectorAll('.add-payment-button');

// Get the <span> element that closes the modal
var span = document.getElementsByClassName('close')[0];

// Function to open the modal when an "Add" button is clicked
// Function to open the modal when an "Add" button is clicked
addPaymentButtons.forEach(function(button) {
    button.onclick = function() {
        // Get the user_id from the button's value attribute
        var userId = button.value;

        // Set the user_id in the hidden input field within the modal form
        var modalUserIdInput = modal.querySelector('input[name="user_id"]');
        modalUserIdInput.value = userId;

        // Display the modal
        modal.style.display = 'block';
    }
});

// Function to close the modal when the close button is clicked
span.onclick = function() {
    modal.style.display = 'none';
}

// Function to close the modal when clicking outside of it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>
