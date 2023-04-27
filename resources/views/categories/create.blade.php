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
    <link href="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.10.5/css/jquery.dataTables.css" rel="stylesheet">
</head>
<body>

<!-- Modal -->
<div class="modal fade ajax-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form id="ajaxForm">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal-title"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Category ID input -->
                    <input type="hidden" name="category_id" id="category_id">
                    <!-- Name input -->
                    <div class="form-group mb-3">
                        <label for="">Name</label>
                        <input type="text" name="name" id="name" class="form-control">
                        <span id="nameError" class="text-danger error-messages"></span>
                    </div>
                    <!-- Tyoe input -->
                    <div class="form-group mb-1">
                        <label for="">Type</label>
                        <select name="type" id="type" class="form-control">
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
        <a class="btn btn-info mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal" id="add_category">Add Category</a>

        <!-- Table -->
        <table id="category-table" class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Type</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.10.5/jquery.dataTables.js"></script>

<script>
    $(document).ready(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('#category-table').DataTable({
            processing: true,
            serverSide: true,

            ajax: "{{  route('categories.index') }}",
            columns: [
                { data : 'id' },
                { data : 'name' },
                { data : 'type' },
                { data : 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $('#modal-title').html('Create Category');
        $('#saveBtn').html('Save Category');
        var form = $('#ajaxForm')[0];

        $('#saveBtn').click(function () {

            $('#saveBtn').html('Saving...');
            $('#saveBtn').attr('disabled', true);
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
                        table.draw();

                        $('#saveBtn').attr('disabled', false);
                        $('#saveBtn').html('Save Category');

                        $('#name').val('');
                        $('#type').val('');
                        $('#category_id').val('');

                        $('.ajax-modal').modal('hide');
                        Swal.fire('Success!', response.success, 'success');
                    }
                },
                error: function (error) {
                    $('#saveBtn').attr('disabled', false);
                    $('#saveBtn').html('Save Category');
                    if (error) {
                        console.log(error.responseJSON.errors.name)
                        $('#nameError').html(error.responseJSON.errors.name);
                        $('#typeError').html(error.responseJSON.errors.type);
                    }
                }
            });
        });

        // edit button code
        $('body').on('click', '.editButton', function(){
            var id = $(this).data('id');

            $.ajax({
                url: '{{ url("categories", '') }}' + '/' + id + '/edit',
                method: 'GET',
                success: function (response) {
                    $('.ajax-modal').modal('show');
                    $('#modal-title').html('Edit Category');
                    $('#saveBtn').html('Update Category');

                    $('#category_id').val(response.id);
                    $('#name').val(response.name);
                    var type = capitalizeFirstLetter(response.type);
                    $('#type').empty().append('<option selected value="' + response.type + '">' + type + '</option>');
                },
                error: function (error) {
                    console.log(error)
                }
            });
        });

        $('body').on('click', '.delButton', function (){
            var id = $(this).data('id');

            if (confirm('Are you sure want to delete it ?')) {
                $.ajax({
                    url: '{{ url("categories/destroy", '') }}' + '/' + id,
                    method: 'DELETE',
                    success: function (response) {
                        table.draw();
                        Swal.fire('Success!', response.success, 'success');
                    },
                    error: function (error) {
                        console.log(error)
                    }
                });
            }
        });

        $('#add_category').click(function (){
           $('#modal-title').html('Create Category');
           $('#saveBtn').html('Save Category');
        });

        function capitalizeFirstLetter(string){
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        // $('#ajaxForm').on('hidden.bs.modal', function (){
        //     $('.error-messages').html('');
        // });

    });
</script>

</body>
</html>
