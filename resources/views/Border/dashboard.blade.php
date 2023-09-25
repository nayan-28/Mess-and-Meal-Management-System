<x-border-app-layout>
    <div class="panel-heading">
        <h2 class="text-center mt-3">Mess & Meal Management System</h2>
    </div>
    @foreach($borderPayments as $row)
    @foreach($bordertotaldeposit as $deposit)
    @if ($row->house_rent == 0 || $row->wifi_bill == 0 || $row->electric_bill == 0 || $deposit->total_amount == 0)
    <!-- Generate a unique id for each modal using a variable -->
    @php
    $modalId = 'paymentReminder_' . uniqid();
    @endphp

    <!-- Modal pop-up with a unique id -->
    <div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!-- Remove the close button from the modal header -->
                <div class="modal-header" style="text-align: center;">
                    <h5 class="modal-title">Payment Reminder</h5>
                </div>
                <div class="modal-body">
                    আপনার টাকা বাকি রয়েছে।দয়া করে দ্রুত পরিশোধ করুন।
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to automatically show the modal only once per session -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        $(window).on('load', function() {
            var modal = $('#{{ $modalId }}');

            // Check if the modal has been shown in this session
            var hasModalBeenShown = sessionStorage.getItem('modalShown');

            if (!hasModalBeenShown) {
                // Show the modal
                modal.modal('show');

                // Mark the modal as shown in this session
                sessionStorage.setItem('modalShown', 'true');

                // Automatically hide the modal after 5 seconds (adjust the duration as needed)
                setTimeout(function() {
                    modal.modal('hide');
                }, 5000); // 5000 milliseconds = 5 seconds

                // Once the modal is hidden, remove it from the DOM
                modal.on('hidden.bs.modal', function() {
                    modal.remove();
                });
            }
        });
    });
    </script>

    </script>
    @endif
    @endforeach
    @endforeach

    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-success text-center">
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
                                <td class="text-center"><a href="{{ route('border.mealdetails')}}"
                                        class="btn btn-primary">
                                        View
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-center">2</th>
                                <td>Payment Details</td>
                                <td class="text-center"><a href="{{ route('border.paymentdetails')}}"
                                        class=" btn btn-secondary">View
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
                    <div id="myModal" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeModal()">&times;</span>
                            <p id="modal-message">{{ session('message') }}</p>
                            <div>
                                <button class="custom-close-button" onclick="closeModal()">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-border-app-layout>
<style>
/* CSS for the modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
    background-color: #fff;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 50%;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
    /* Add a box shadow for a card-like appearance */
    border-radius: 5px;
    /* Add rounded corners for a card-like appearance */
    text-align: center;
}

.close {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 20px;
    cursor: pointer;
}

.custom-close-button {
    background-color: red;
    /* Red background color */
    color: white;
    /* White text color */
    border: none;
    /* Remove border */
    padding: 10px 20px;
    /* Add padding for better appearance */
    cursor: pointer;
    margin-top: 20px;
    /* Add some space below the message */
    border-radius: 5px;
    /* Rounded corners */
}

.custom-close-button:hover {
    background-color: darkred;
    /* Darker red color on hover */
}
</style>

<script>
// JavaScript to display and close the modal
var modal = document.getElementById("myModal");
var message = document.getElementById("modal-message");

// Show the modal with the message
function showModal() {
    modal.style.display = "block";
}

// Close the modal
function closeModal() {
    modal.style.display = "none";
}

// Close the modal when clicking outside of it
window.onclick = function(event) {
    if (event.target === modal) {
        closeModal();
    }
};

// Show the modal when the page loads if there is a message
window.onload = function() {
    if (message.innerText.trim() !== "") {
        showModal();
        // Automatically close the modal after 5 seconds (adjust the duration as needed)
        setTimeout(closeModal, 4000); // 5000 milliseconds = 5 seconds
    }
};
</script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
