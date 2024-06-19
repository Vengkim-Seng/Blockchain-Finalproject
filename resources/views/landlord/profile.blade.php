@extends('layout.dashboard-parent-landlord')

@section('content')
    @parent <!-- Retain master layout content -->

    <style>
    .edit-button, .upload-button, .save-button, .cancel-button, .close-button {
        padding: 5px 20px;
        font-weight: bold;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: background-color 0.3s, color 0.3s, border-color 0.3s;
        outline: none;
    }

    .edit-button {
        background-color: white;
        color: rgb(34, 139, 34);
        border: 2px solid rgb(34, 139, 34);
    }

    .edit-button:hover, .upload-button:hover, .save-button:hover, .cancel-button:hover, .close-button:hover {
        color: white;
    }

    .edit-button:hover {
        background-color: rgb(34, 139, 34);
    }

    .upload-button {
        background-color: #007BFF; /* Bootstrap primary blue */
        color: white;
        border: 2px solid #0056b3;
        margin-top: 10px;
    }

    .upload-button:hover {
        background-color: #0056b3; /* Darker blue for hover state */
    }

    .save-button, .cancel-button {
        display: none; /* Initially hidden */
        background-color: #007BFF; /* Save button blue */
        border: 2px solid #0056b3;
        color: white;
    }

    .save-button:hover {
        background-color: #0056b3; /* Darker blue for save button hover state */
    }

    .cancel-button {
        background-color: #dc3545; /* Bootstrap danger red */
        border: 2px solid #b02a37;
    }

    .cancel-button:hover {
        background-color: #b02a37; /* Darker red for cancel button hover state */
    }

    .close-button {
        background-color: #dc3545; /* Bootstrap danger red */
        color: white;
        border: 2px solid #b02a37;
        margin-top: 10px;
    }

    .close-button:hover {
        background-color: #b02a37; /* Darker red for hover state */
    }

    .profile-card {
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        max-width: 960px;
        margin: auto;
    }

    .profile-picture-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
    }

    .profile-field {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        margin-top: 30px;
    }

    .profile-label {
        font-weight: bold;
        color: #666;
        flex-grow: 1;
        text-align: left;
        font-size: 20px;
    }

    .profile-value {
    width: 512px; /* Sets a fixed width */
    text-align: center;
    font-size: 18px;
    display: inline-block; /* Ensures inline behavior with block properties */
}

.profile-input {
    width: 325px; /* Adjusted width */
    text-align: center;
    font-size: 18px;
    display: none; /* Remains hidden initially */
    border: 2px solid black; /* Border color changed to black */
    padding: 5px 10px; /* Padding for better visibility of text */
    margin-top: 5px; /* Space above the input field */
    box-sizing: border-box; /* Includes padding and border in the width */
    border-radius: 5px; /* Rounded edges */
}



    img.profile-img {
        width: 512px;
        height: 512px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 30px;
    }

    .button-container {
        display: flex; /* This will align the buttons next to each other */
        flex: 2;
        width: 100%; /* Ensures the container fills the modal width */
        justify-content: space-evenly; /* Evenly spaces the buttons within the container */
        margin-top: 20px; /* Space above the button container */
    }

    .upload-button, .close-button {
        flex: 1; /* Allows the buttons to grow and fill the flex container */
        margin: 0 20px; /* Adds spacing between the buttons */
    }

    
</style>

    <!-- Profile Card and Modal within Main Section -->
    <main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200">
        <div class="profile-card">
           <!-- Profile Picture Column -->
           <div class="profile-picture-container">
                @php
$profileImagePath = $landlord->profile_picture ? 'storage/' . $landlord->profile_picture : 'assets/img/Default-icon.png';
                @endphp
                <img class="profile-img" src="{{ asset($profileImagePath) }}" alt="Profile Picture">
                <button onclick="openModal()" class="upload-button">Upload</button>
            </div>
            <!-- Information Column -->
            <div class="info-section" >

            <div class="profile-field" id="field-landlord_name">
    <span class="profile-label">Name:</span>
    <span class="profile-value" id="value-landlord_name">{{ $landlord->landlord_name }}</span>
    <input type="text" class="profile-input" id="input-landlord_name" value="{{ $landlord->landlord_name }}" style="display:none;">
    <div class="button-container">
        {{-- <button onclick="enableEdit('landlord_name')" class="edit-button" id="edit-button-landlord_name">Edit</button> --}}
        <button onclick="save('landlord_name')" class="save-button" style="display:none;">Save</button>
        <button onclick="cancel('landlord_name')" class="cancel-button" style="display:none;">Cancel</button>
    </div>
</div>


<div class="profile-field" id="field-email">
    <span class="profile-label">Email:</span>
    <span class="profile-value" id="value-email">{{ $landlord->email }}</span>
    <input type="text" class="profile-input" id="input-email" value="{{ $landlord->email }}" style="display:none;">
    <div class="button-container">
        {{-- <button onclick="enableEdit('email')" class="edit-button" id="edit-button-email">Edit</button> --}}
        <button onclick="save('email')" class="save-button" style="display:none;">Save</button>
        <button onclick="cancel('email')" class="cancel-button" style="display:none;">Cancel</button>
    </div>
</div>
<div class="profile-field" id="field-contact_info">
    <span class="profile-label">Contact:</span>
    <span class="profile-value" id="value-contact_info">{{ $landlord->contact_info }}</span>
    <input type="text" class="profile-input" id="input-contact_info" value="{{ $landlord->contact_info }}" style="display:none;">
    <div class="button-container">
        <button onclick="enableEdit('contact_info')" class="edit-button" id="edit-button-contact_info">Edit</button>
        <button onclick="save('contact_info')" class="save-button" style="display:none;">Save</button>
        <button onclick="cancel('contact_info')" class="cancel-button" style="display:none;">Cancel</button>
    </div>
</div>
        </div>

        
<!-- Modal for Uploading Profile Picture -->
<div id="uploadModal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color: rgba(0,0,0,0.5); align-items: center; justify-content: center;">
        <div style="background: white; padding: 20px; border-radius: 10px; width: 50%; display: flex; flex-direction: column; align-items: center;">
            <h2>Upload Profile Picture</h2>
            <img id="imagePreview" src="{{ asset('assets/img/Default-icon.png') }}" alt="Profile Preview" style="width: 512px; height: 512px; border-radius: 50%; object-fit: cover; margin-bottom: 20px;">
            <form action="{{ route('profile.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="profile_picture" id="profile_picture" onchange="previewImage();" required style="margin-bottom: 20px;">
                <div class="button-container">
                    <button type="submit" class="upload-button .upload-Profile-button">Upload</button>
                    <button type="button" onclick="closeModal()" class="close-button">Close</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal End -->
</main>

<script>
    function openModal() {
        document.getElementById('uploadModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('uploadModal').style.display = 'none';
    }

    function previewImage() {
        var file = document.getElementById('profile_picture').files;
        if (file.length > 0) {
            var fileReader = new FileReader();

            fileReader.onload = function(event) {
                document.getElementById('imagePreview').setAttribute('src', event.target.result);
            };

            fileReader.readAsDataURL(file[0]);
        } else {
            // If no file is selected, revert to the default image
            document.getElementById('imagePreview').setAttribute('src', '{{ asset('assets/img/Default-icon.png') }}');
        }
    }

    function enableEdit(field) {
    document.getElementById('value-' + field).style.display = 'none';
    document.getElementById('input-' + field).style.display = 'inline-block';
    document.getElementById('edit-button-' + field).style.display = 'none';
    showButtons(field, true);
}

function save(field) {
    var inputValue = document.getElementById('input-' + field).value;
    console.log("Saving", field, inputValue); // Add this to check what is being sent

    $.ajax({
        url: '/profile/update',
        type: 'POST',
        data: {
            field: field,
            value: inputValue,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            console.log("Response", response); // Check server response
            // Update the UI
            document.getElementById('value-' + field).textContent = inputValue;
            document.getElementById('value-' + field).style.display = 'inline';
            document.getElementById('input-' + field).style.display = 'none';
            document.getElementById('edit-button-' + field).style.display = 'inline';
            showButtons(field, false);
        },
        error: function(xhr, status, error) {
            console.error("Save error", status, error);
            alert('Error saving changes.');
        }
    });
}




function cancel(field) {
    document.getElementById('input-' + field).style.display = 'none';
    document.getElementById('value-' + field).style.display = 'inline'; // Adjusted to match your setup
    document.getElementById('edit-button-' + field).style.display = 'inline';
    showButtons(field, false);
}

function showButtons(field, show) {
    var container = document.getElementById('field-' + field).getElementsByClassName('button-container')[0];
    var buttons = container.querySelectorAll('button');
    buttons.forEach(button => {
        if (button.id !== 'edit-button-' + field) {
            button.style.display = show ? 'inline' : 'none';
        }
    });
}
</script>

@endsection