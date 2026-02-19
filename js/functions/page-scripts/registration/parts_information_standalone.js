$(function () {
    // Fixed: Use the correct ID selector for the button
    $("#uploadButton").on("click", function () {
        // Fixed: Actually get the value from the Step 1 input!
        let userId = $("#userId").val().trim();
        // Fixed: Use the correct ID selector for the file input
        let fileInput = $("#fileUpload")[0].files[0];

        // Basic validation so you don't send garbage to your ASP.NET Core backend
        if (!userId) {
            Swal.fire('Error', 'You need to enter a BIPH ID first!', 'error');
            return;
        }
        if (!fileInput) {
            Swal.fire('Error', 'You forgot to choose a file!', 'error');
            return;
        }

        Swal.fire({
            title: 'Are you sure you want to upload this file?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, upload it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                let formData = new FormData();
                formData.append('file', fileInput);

                Swal.fire({
                    title: 'Uploading...',
                    html: 'Please wait while the file is being uploaded.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Using your custom apiCall wrapper
                apiCall(`http://apbiphiqcwb01:1116/api/PartsInformations/upload?username=${userId}`, 'POST', formData, true)
                    .then(response => {
                        Swal.close();
                        Swal.fire({
                            title: 'Success',
                            text: 'File uploaded successfully.',
                            icon: 'success'
                        });

                        // Fixed: Output to your result div instead of a ghost table
                        $("#result").html(`<span class="text-success">Upload successful for user: ${userId}</span>`);
                    })
                    .catch(error => {
                        Swal.close();
                        console.error(error);
                        Swal.fire({
                            title: 'Error',
                            text: 'An error occurred while uploading the file.',
                            icon: 'error'
                        });

                        // Output error to the result div
                        $("#result").html(`<span class="text-danger">Upload failed. Check console for details.</span>`);
                    });
            }
        });
    });
});