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

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p id="modal-message">{{ session('message') }}</p>
            <div>
                <button class="custom-close-button" onclick="closeModal()">Close</button>
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
