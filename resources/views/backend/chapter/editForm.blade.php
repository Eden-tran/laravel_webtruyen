@extends('layouts.app')
@section('extraCss')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"
        integrity="sha256-0YPKAwZP7Mp3ALMRVB2i8GXeEndvCq3eSl/WsAl1Ryk=" crossorigin="anonymous"></script>
    <style type="text/css">
        .dz-preview .dz-image img {
            width: 100% !important;
            height: 100% !important;
            display: block;
        }

        .dropzone {
            border: 1px solid rgba(0, 0, 0, 0.3);
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('backend.block.navbar')
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{ $title }}</div>
                    <div class="card-body">
                        @if (session('msg'))
                            <div class="alert alert-success text-center">{{ session('msg') }}</div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger text-center">Dữ liệu không hợp lệ vui lòng nhập lại</div>
                        @endif
                        <form action="{{ route('admin.chapter.postEdit', $chapter->id) }}" id='chapterForm' method="POST"
                            enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="txtChapterName" class="form-label">Tên</label>
                                <input type="text" class="form-control" name="txtChapterName" id="txtChapterName"
                                    value='{{ old('txtChapterName', $chapter->name) }}'>
                                @error('txtChapterName')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="slChapterActive" class="form-label">Trạng Thái</label>
                                <select class="form-select" name="slChapterActive">
                                    <option disabled selected>Hãy chọn trạng thái</option>
                                    <option value=1
                                        {{ old('slChapterActive', $chapter->active) == 1 ? 'selected' : false }}>
                                        không kích
                                        hoạt
                                    </option>
                                    <option value=2
                                        {{ old('slChapterActive', $chapter->active) == 2 ? 'selected' : false }}>
                                        kích hoạt
                                    </option>
                                </select>
                                @error('slChapterActive')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <input type="hidden" id='chapterId' name='chapterId'
                                value="{{ old('chapterId', $chapter->id) }}">
                            <input type="hidden" id='validated' name='validated' value="{{ old('validated', 'fail') }}">
                            <input type="hidden" id='method' name='method' value="edit">

                            <div class="mb-3">
                                <label for="txtChapterDescrbie" class="form-label">Slug</label>
                                <input type="text" class="form-control" name="txtChapterSlug" id="txtChapterSlug"
                                    value='{{ old('txtChapterSlug', $chapter->slug) }}'>
                                @error('txtChapterSlug')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="inpPages" class="form-label">DS Trang</label>
                                <div class="dropzone form-control" id="myAwesomeDropzone">
                                    {{-- <input type="hidden" name="images" value="images" multiple id="images"> --}}
                                    {{-- <div class="dz-default dz-message">
                                        <span><i class="fa fa-cloud-upload"></i>
                                            <br>Drop files here to upload
                                        </span>
                                    </div> --}}
                                </div>

                                {{-- <input type="file" class="form-control dropzone" name="inpPages[]" id="inpPages"> --}}
                            </div>
                            <button type="submit" id='submit-form' class="btn btn-primary">Submit</button>
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('extraJs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.js"></script>
    <script>
        $(function() {
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
            maxFilesize: 1, // size of the file M
            dictFileTooBig: "File is too large",
            url: "{{ route('admin.chapter.postEdit', $chapter->id) }}",
            autoProcessQueue: false,
            addRemoveLinks: true,
            acceptedFiles: ".png,.jpg,.gif,.bmp,.jpeg,",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            // delete request = 3
            removedfile: function(file) {
                var allFilesValid = true;
                myDropzone.files.forEach(function(file) {
                    if (file.status === "error") {
                        allFilesValid = false;
                    }
                });
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
                return (_ref = file.previewElement) != null ?
                    _ref.parentNode.removeChild(file.previewElement) :
                    void 0;
            },
        });
        const button = document.getElementById("submit-form");
        button.addEventListener("click", function() {
            event.preventDefault();

            // Get the queued files
            var files = myDropzone.getQueuedFiles();
            // console.log(files);
            if (files.length == 0) {
                alert("empty files");
            } else {
                // var files = myDropzone.files;
                let dzElements = document.querySelectorAll(".dz-filename");
                let targetNames = [];
                dzElements.forEach((dzElement) => {
                    let targetElement = dzElement.querySelector("span");
                    let targetName = targetElement.textContent;
                    targetNames.push(targetName);
                });
                files.sort(function(a, b) {
                    return $(a.previewElement).index() > $(b.previewElement).index() ?
                        1 :
                        -1;
                });
                // Sort theme based on the DOM element index
                // Clear the dropzone queue
                myDropzone.removeAllFiles();
                // Add the reordered files to the queue
                myDropzone.handleFiles(files);
                const acceptedFiles = myDropzone.getAcceptedFiles();
                // console.log(acceptedFiles);

                myDropzone.processQueue();
                // document.getElementById('chapterForm').submit();

            }
        });

        //!! Xử lý validate front end
        document.addEventListener("DOMContentLoaded", function() {
            myDropzone.on("success", function(file, response) {
                // Display a success message to the user
                if (!document.querySelector("#chapterId").value) {
                    document.querySelector("#chapterId").value = response;
                }
                document.getElementById('chapterForm').submit();

            });
            myDropzone.on("sending", function(file, xhr, formData) {
                // Add a custom field to the request
                if (document.querySelector("#chapterId").value) {
                    formData.append('chapterId', document.querySelector("#chapterId").value);
                }
            });
            myDropzone.on("sendingmultiple", function(file, xhr, formData) {
                // Add a custom field to the request
                if (document.querySelector("#chapterId").value) {
                    formData.append('chapterId', document.querySelector("#chapterId").value);
                }
            });
            // Initialize Dropzone
            myDropzone.on("error", function(file, errorMessage, xhr) {
                console.log(file.status);
                if (file.status == 'error') {
                    // alert("Validation failed for file " + file.name);
                    alert(`Validation failed for file ${file.name} !`);
                    document.querySelector("#submit-form").disabled = true;
                }
            });
            // Prevent form submission if submit button is disabled
            document.querySelector("#chapterForm").addEventListener("submit", function(event) {
                if (document.querySelector("#submit-form").disabled) {
                    event.preventDefault();
                    alert(
                        "Please fix validation errors before submitting the form."
                    );
                }
            });
        });
        //!! xử lý validate front end

        $(document).ready(function() {
            var chapterId = document.querySelector("#chapterId").value;
            var validated = document.querySelector("#validated").value;
            var method = document.querySelector("#method").value;

            if (chapterId) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.chapter.getTempChapter') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "chapterId": chapterId,
                        "validate": validated,
                        "method": method,
                    },
                    dataType: "json",
                    success: function(response) {
                        // console.log(response);
                        var imageUrls = response;
                        async function addImagesInOrder(imageUrls) {
                            // Create an array to store the Promises
                            const promises = [];
                            // Loop through the image URLs and push a Promise to the array for each URL
                            for (const url of imageUrls) {
                                // Create a Promise that resolves when the image data is retrieved
                                const promise = new Promise((resolve, reject) => {
                                    // Create a new XMLHttpRequest object
                                    const xhr = new XMLHttpRequest();
                                    xhr.open('GET', url, true);
                                    xhr.responseType = 'blob';
                                    // When the request is complete, resolve the Promise with the image data
                                    xhr.onload = function(e) {
                                        if (this.status == 200) {
                                            resolve(this.response);
                                        } else {
                                            reject();
                                        }
                                    };
                                    // If the request fails, reject the Promise
                                    xhr.onerror = function() {
                                        reject();
                                    };
                                    // Send the request to retrieve the image data
                                    xhr.send();
                                });
                                // Push the Promise to the array
                                promises.push(promise);
                            }
                            // Wait for all the Promises to resolve using Promise.all()
                            const imageBlobs = await Promise.all(promises);
                            // Loop through the image blobs and add each file to the dropzone in the correct order
                            for (let i = 0; i < imageBlobs.length; i++) {
                                const blob = imageBlobs[i];
                                const url = imageUrls[i];
                                const filename = url.split('/').pop();
                                const file = new File([blob], filename, {
                                    type: blob.type
                                });
                                myDropzone.addFile(file);
                            }
                        }
                        // Call the function with the list of image URLs
                        addImagesInOrder(imageUrls);
                    }
                });
            }

        });
    </script>
@endsection
