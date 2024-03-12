// id = "{{ $mangaId }}";
// console.log(id);
$(function () {
    $(".dropzone").sortable({
        items: ".dz-preview",
        cursor: "move",
        opacity: 0.5,
        containment: ".dropzone",
        distance: 20,
        tolerance: "pointer",
    });
});
Dropzone.autoDiscover = false;
// Initialize a new Dropzone instance
const removedFiles = [];
var myDropzone = new Dropzone(".dropzone", {
    uploadMultiple: true, // uplaod files in a single request
    parallelUploads: 100, // use it with uploadMultiple
    maxFilesize: 0.02, // size of the file M
    dictFileTooBig: "File is too big",
    url: "{{ route('admin.chapter.postAdd', $mangaId) }}",
    autoProcessQueue: false,
    addRemoveLinks: true,
    acceptedFiles: ".png,.jpg,.gif,.bmp,.jpeg",
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
    // delete request = 3
    removedfile: function (file) {
        // console.log("File removed:", file.name);
        // // Check if all files have passed validation
        // var allFilesValid = true;
        // myDropzone.getAcceptedFiles().forEach(function(file) {
        //     if (file.status !== "success" || file.xhr.status !== 200) {
        //         allFilesValid = false;
        //     }
        // });
        // console.log(allFilesValid);
        // if (allFilesValid) {
        //     // Enable submit button if all files have passed validation
        //     document.querySelector("#submit-form").disabled = false;
        // }
        var allFilesValid = true;
        myDropzone.files.forEach(function (file) {
            if (file.status === "error") {
                allFilesValid = false;
            }
        });
        console.log(allFilesValid);
        // Check if all files are valid
        // var allFilesValid = myDropzone.files.every(function(file) {
        //     return file.isValid;
        // });
        // Disable submit button if any files are invalid
        if (allFilesValid == true) {
            document.querySelector("#submit-form").disabled = false;
        }
        var name = file.name;
        removedFiles.push(name);
        const dzMessage = document.getElementsByClassName("dz-message ");
        // const dzPreview = document.getElementsByClassName('dz-preview');
        var _ref;
        const dzPreview = document.getElementsByClassName(
            file.previewElement.className
        );
        if (dzPreview.length - 1 > 0) {
            dzMessage[0].style.display = "none";
        } else {
            dzMessage[0].removeAttribute("style");
        }
        // ! from
        // var allFilesValid = true;
        // // console.log(myDropzone.acceptedFiles);
        // myDropzone.acceptedFiles.forEach(function(file) {
        //     if (file.status !== "success" || file.xhr.status !== 200) {
        //         allFilesValid = false;
        //     }
        // });
        // // Update submit button status
        // document.querySelector("#submit-form").disabled = !allFilesValid;
        // ! end
        return (_ref = file.previewElement) != null
            ? _ref.parentNode.removeChild(file.previewElement)
            : void 0;
    },
});
const button = document.getElementById("submit-form");
button.addEventListener("click", function () {
    event.preventDefault();
    // console.log(removedFiles);
    // console.log(myDropzone.removedFiles);
    // Get the queued files
    var files = myDropzone.getQueuedFiles();
    console.log(files);
    if (files.length == 0) {
        alert("empty files");
    } else {
        // var files = myDropzone.files;
        let dzElements = document.querySelectorAll(".dz-filename");
        let targetNames = [];
        // console.log(files);
        dzElements.forEach((dzElement) => {
            let targetElement = dzElement.querySelector("span");
            let targetName = targetElement.textContent;
            targetNames.push(targetName);
        });
        files.sort(function (a, b) {
            return $(a.previewElement).index() > $(b.previewElement).index()
                ? 1
                : -1;
        });
        // Sort theme based on the DOM element index
        // Clear the dropzone queue
        myDropzone.removeAllFiles();
        // Add the reordered files to the queue
        myDropzone.handleFiles(files);
        const acceptedFiles = myDropzone.getAcceptedFiles();
        // console.log(acceptedFiles);
        myDropzone.processQueue();
        // location.reload(true);
        // document.getElementById('chapterForm').submit();
        // for (let i = 0; i < acceptedFiles.length; i++) {
        //     setTimeout(function() {
        //         myDropzone.processFile(acceptedFiles[i])
        //     }, i * 150)
        // }
    }
});

//!! Xử lý validate front end
document.addEventListener("DOMContentLoaded", function () {
    // Initialize Dropzone
    // Disable submit button by default
    // document.querySelector("#submit-form").disabled = true;

    myDropzone.on("success", function (file) {
        // Update submit button status based on remaining accepted files
        var allFilesValid = myDropzone
            .getAcceptedFiles()
            .every(function (file) {
                return file.isValid;
            });
        document.querySelector("#submit-form").disabled = !allFilesValid;
    });
    // myDropzone.on("removedfile", function(file) {
    //     // Update isValid property of all files
    //     var allFilesValid = true;
    //     myDropzone.files.forEach(function(file) {
    //         if (file.status === "error") {
    //             allFilesValid = false;
    //         }
    //     });
    //     console.log(allFilesValid);
    //     // Check if all files are valid
    //     // var allFilesValid = myDropzone.files.every(function(file) {
    //     //     return file.isValid;
    //     // });
    //     // Disable submit button if any files are invalid
    //     if (allFilesValid == true) {
    //         document.querySelector("#submit-form").disabled = false;
    //     }
    // });
    // Prevent form submission if submit button is disabled
    document
        .querySelector("#chapterForm")
        .addEventListener("submit", function (event) {
            if (document.querySelector("#submit-form").disabled) {
                event.preventDefault();
                alert(
                    "Please fix validation errors before submitting the form."
                );
            }
        });
});
//!! xử lý validate front end
// var imageUrls = ["http://localhost/dropzoneHTML/upload/pages1.jpg",
//     "http://localhost/dropzoneHTML/upload/pages2.jpeg"
// ];
// $(document).ready(function() {
//     $.ajax({
//         type: "GET",
//         url: "get_images.php",
//         data: "data",
//         dataType: "json",
//         success: function(response) {
//             // console.log(response);
//             var imageUrls = response;
//             async function addImagesInOrder(imageUrls) {
//                 // Create an array to store the Promises
//                 const promises = [];
//                 // Loop through the image URLs and push a Promise to the array for each URL
//                 for (const url of imageUrls) {
//                     // Create a Promise that resolves when the image data is retrieved
//                     const promise = new Promise((resolve, reject) => {
//                         // Create a new XMLHttpRequest object
//                         const xhr = new XMLHttpRequest();
//                         xhr.open('GET', url, true);
//                         xhr.responseType = 'blob';
//                         // When the request is complete, resolve the Promise with the image data
//                         xhr.onload = function(e) {
//                             if (this.status == 200) {
//                                 resolve(this.response);
//                             } else {
//                                 reject();
//                             }
//                         };
//                         // If the request fails, reject the Promise
//                         xhr.onerror = function() {
//                             reject();
//                         };
//                         // Send the request to retrieve the image data
//                         xhr.send();
//                     });
//                     // Push the Promise to the array
//                     promises.push(promise);
//                 }
//                 // Wait for all the Promises to resolve using Promise.all()
//                 const imageBlobs = await Promise.all(promises);
//                 // Loop through the image blobs and add each file to the dropzone in the correct order
//                 for (let i = 0; i < imageBlobs.length; i++) {
//                     const blob = imageBlobs[i];
//                     const url = imageUrls[i];
//                     const filename = url.split('/').pop();
//                     const file = new File([blob], filename, {
//                         type: blob.type
//                     });
//                     myDropzone.addFile(file);
//                 }
//             }
//             // Call the function with the list of image URLs
//             addImagesInOrder(imageUrls);
//         }
//     });
// });
