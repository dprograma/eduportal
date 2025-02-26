function confirmPublish(questionId, url, buttonElement) {
    const currentStatus = parseInt(buttonElement.getAttribute('data-status'), 10);
    // console.log(questionId, url, currentStatus);
    const newStatus = currentStatus === 1 ? 0 : 1;
    const actionText = newStatus === 1 ? 'publish' : 'unpublish';

    Swal.fire({
        title: `${actionText} Confirmation`,
        text: `Do you want to ${actionText} this question?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `Yes, ${actionText} it!`
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                method: 'POST',
                data: { questionId, newStatus, publish: 'publish' },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        // Toggle the button's appearance and text
                        buttonElement.classList.toggle('bg-success', newStatus === 1);
                        buttonElement.classList.toggle('bg-secondary', newStatus === 0);
                        buttonElement.textContent = newStatus === 1 ? 'Published' : 'Unpublished';
                        // Update the data-status attribute
                        buttonElement.setAttribute('data-status', newStatus);

                        Swal.fire('Updated!', `The question has been ${actionText}ed.`, 'success');
                    } else {
                        Swal.fire('Error', `Failed to ${actionText} the question.`, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', `Failed to ${actionText} the question.`, 'error');
                }
            });
        }
    });

    return false;
}



function confirmPostPublish(postId, currentStatus, buttonElement) {
    const newStatus = currentStatus === 1 ? 0 : 1;
    const actionText = newStatus === 1 ? 'publish' : 'unpublish';
    console.log(newStatus, actionText);
    Swal.fire({
        title: `${actionText} Confirmation`,
        text: `Do you want to ${actionText} this post?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `Yes, ${actionText} it!`
    }).then((result) => {
        if (result.isConfirmed) {
            publish = 'publish'; // You can change this variable if needed

            $.ajax({
                url: 'viewpost', // Update the URL to the correct one for your posts
                method: 'POST',
                data: { postId, newStatus, publish }, // Modify this as needed
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        // Toggle the button's appearance and text
                        buttonElement.classList.toggle('bg-success', newStatus === 1);
                        buttonElement.classList.toggle('bg-secondary', newStatus === 0);
                        buttonElement.textContent = newStatus === 1 ? 'Published' : 'Unpublished';
                        Swal.fire('Updated!', `The post has been ${actionText}ed.`, 'success');
                        // You can update the button text or UI as needed here
                    } else {
                        Swal.fire('Error', `Failed to ${actionText} the post.`, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', `Failed to ${actionText} the post.`, 'error');
                }
            });
        }
    });
    return false; // Prevent the default link behavior
}


























function confirmDelete(userId, redirectUrl) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This action cannot be undone!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            location.href = redirectUrl + `?delete=1&id=${userId}`;
        }
    });
}





function addOption(selectId, inputId) {
    const inputValue = document.getElementById(inputId).value;
    const selectElement = document.getElementById(selectId);
    const option = document.createElement("option");
    option.text = inputValue;
    selectElement.add(option);

    document.getElementById(inputId).value = "";

    option.value = inputValue;


    selectElement.add(option);

    document.getElementById(inputId).value = '';

    const inputName = selectElement.name;
    const selectedValue = inputValue;


}


function addOption(selectId, inputId) {
    const inputValue = document.getElementById(inputId).value;

    const selectElement = document.getElementById(selectId);

    const optionExists = Array.from(selectElement.options).some((option) => option.value === inputValue);

    if (optionExists) {
        Swal.fire({
            icon: 'error',
            title: 'Duplicate Entry',
            text: 'This entry already exists in the list.',
        });
    } else {
        const option = document.createElement("option");
        option.text = inputValue;

        selectElement.add(option);

        document.getElementById(inputId).value = '';

        saveOptionToServer(inputValue, selectId);
    }
}
function addOptionToSelect(selectId, inputId) {
    const inputValue = document.getElementById(inputId).value.trim(); // Trim whitespace

    if (inputValue === "") {
        // Don't add empty options
        return;
    }

    const selectElement = document.getElementById(selectId);

    // Check if the option already exists
    const existingOptions = selectElement.getElementsByTagName("option");
    for (const option of existingOptions) {
        if (option.value.trim() === inputValue) {
            // Option already exists, don't add it again
            return;
        }
    }

    const option = document.createElement("option");
    option.text = inputValue;
    option.value = inputValue; // Optionally set the value attribute

    selectElement.add(option);
}


console.log(window)

window.onload = function () {


    // const publishBtn = document.querySelector('.publish');
    const posts = document.querySelector('.post-body');
    var myModal = new bootstrap.Modal(document.getElementById('editform'), {
        keyboard: false
    })

    posts.addEventListener('click', function (e) {

        if (e.target.classList.contains('edit')) {
            const clicked = e.target;

            const id = e.target.parentElement.parentElement.getAttribute('data-id')

            console.log("Edit " + id)

            if (confirm("Do you really want to Edit this post?")) {
                $.ajax({
                    url: "create-past-question",
                    method: "POST",
                    data: { id: id, type: 'edit' },
                    dataType: "json",
                    success: function (response) {
                        if (response.status === 'success') {

                            console.log(response.data);
                            const { id, title, body, category } = response.data;
                            $('#title').val(title);
                            $('.id').val(id);



                            myModal.show();

                        }
                    }


                });
            }
        }
        if (e.target.classList.contains('publish')) {
            const clicked = e.target;

            const id = e.target.parentElement.parentElement.getAttribute('data-id')

            Swal.fire({
                title: 'Do you really want to publish/unpublish this',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'Yes',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        url: "create-past-question",
                        method: "POST",
                        data: { id: id, publish: 'publish' },
                        dataType: "json",
                        success: function (response) {
                            if (response.status === 'success') {
                                clicked.classList.toggle('bg-success');
                                setTimeout(() => {
                                    Swal.fire('Saved!', '', 'success')
                                }, 1500)
                            }
                        }


                    });

                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            })


        }
    })
}


//
function showConfirmationDialog(title, text, icon, confirmButtonText, callback) {
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonText: confirmButtonText,
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
}



// When the user clicks anywhere outside of a modal, close it
window.onclick = function (event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = "none";
    }
}







// Get all modal buttons
var viewButtons = document.querySelectorAll('.btn-view');

// Function to open the "View" modal
function openViewModal(modalId) {
    var modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
    }
}

viewButtons.forEach(function (button) {
    button.addEventListener('click', function () {
        // Get the data-modal-id attribute to find the corresponding modal ID
        var modalId = button.getAttribute('data-modal-id');
        console.log('Clicked on button with modalId:', modalId); // Add this line for debugging
        if (modalId) {
            openViewModal(modalId);
        }
    });
});



