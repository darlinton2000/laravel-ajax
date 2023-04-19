<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Create Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<!-- Modal -->

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form id="ajaxForm">
        <div class="modal-dialog" id="ajax-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal-title"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Name input -->
                    <div class="form-group mb-3">
                        <label for="">Name</label>
                        <input type="text" name="name" class="form-control">
                        <span id="nameError" class="text-danger error-messages"></span>
                    </div>
                    <!-- Tyoe input -->
                    <div class="form-group mb-1">
                        <label for="">Type</label>
                        <select name="type" class="form-control">
                            <option disabled selected>Choose Option</option>
                            <option value="electronic">Eletronic</option>
                        </select>
                        <span id="typeError" class="text-danger error-messages"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveBtn"></button>
                </div>
            </div>
        </div>
    </form>
</div>


<!-- Button trigger modal -->
<div class="row">
    <div class="col-md-6 offset-3" style="margin-top: 100px">
        <a class="btn btn-info" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Category</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#modal-title').html('Create Category');
        $('#saveBtn').html('Save Category');
        var form = $('#ajaxForm')[0];

        $('#saveBtn').click(function () {

            $('.error-messages').html('');

            var formData = new FormData(form);

            $.ajax({
                url: '{{ route("categories.store") }}',
                method: 'POST',
                processData: false,
                contentType: false,
                data: formData,

                success: function (response) {
                    if (response) {
                        $('#ajax-modal').modal('hide');
                        Swal.fire('Success!', response.success, 'success');
                    }
                },
                error: function (error) {
                    if (error) {
                        console.log(error.responseJSON.errors.name)
                        $('#nameError').html(error.responseJSON.errors.name);
                        $('#typeError').html(error.responseJSON.errors.type);
                    }
                }
            });
        })
    });
</script>

</body>
</html>
