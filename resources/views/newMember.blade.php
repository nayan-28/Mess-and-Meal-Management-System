<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <div>
                <a href="{{route('dashboard') }}" class="btn btn-warning"><svg xmlns="http://www.w3.org/2000/svg"
                        width="30" height="30" fill="currentColor" class="bi bi-arrow-left-square-fill"
                        viewBox="0 0 16 16">
                        <path
                            d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1z" />
                    </svg></a>
            </div>
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
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
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
                            $counter1 = 0;
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
                                        <button class="btn btn-success" type="submit" name="update">✓
                                        </button>
                                    </td>
                                </form>
                                <form action="rejectmember" method="POST"
                                    onsubmit="return confirm('আপনি নিশ্চিত এই মেম্বারকে রিমুভ করতে?');">
                                    @csrf
                                    <td class="text-center">
                                        <button class="btn btn-danger" type="submit" name="delete[{{$row->id}}]"
                                            value="{{$row->id}}" />✗</button>
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
    </x-slot>
</x-app-layout>
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
    width: 20% !important;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
    border-radius: 5px;
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
