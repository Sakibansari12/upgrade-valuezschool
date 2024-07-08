@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- Student --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <!-- <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Manage Upload -  -->
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page"><a href="{{ route('school.list') }}"><i class="mdi mdi-home-outline"></i> - Manage School</a></li>
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page"><a href="{{ route('student.list', ['school_id' => $school_id]) }}"><i class="mdi mdi-home-outline"></i> - Manage Student</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Bulk Upload Students</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

<body>


    <div class="container">
        <div class="card-body">
        <h2>Bulk Upload Students </h2>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        
    @endif
                    <div class="card-header" id="schoolidnotexists">
                        
                        <div class="card-actions float-end" >
                            <div class="dropdown show">
                                <a href="{{route('download')}}"
                                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Download Excel</a>
                            </div>
                        </div>
                    </div>
                    <p></p>
        <form action="" name="studentcreateimport" id="studentcreateimport" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group" id="student_licence">
                <label for="file">Select CSV File:<span class="text-danger">*</span></label>
                <!-- <input type="file" class="form-control" id="file" name="csv_file"  required> -->

                <input type="file" class="form-control" id="file" name="csv_file" accept=".xlsx, .xls" required>
                 <span id="file-validation-message"></span>


                <p></p>
                @error('csv_file')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            </div>
            <p></p>
            <button type="submit" class="btn" style="background-color: #00205c; color: #fff;">Upload</button>
        </form>
        
        <table width="100%">
        <thead>
        </thead>
        <tbody>
          <tr id="tabledata">
          </tr>
        </tbody>
      </table>
    </div>
    </div>
    <section class="content">
                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <h5 class="card-title mb-0">Bulk Upload File</h5>
                                                                <div class="card-actions float-end">
                                                                </div>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="table-responsive">
                                                                    <table id="yajra-table" class="table" style="width:100%">
                                                                        <thead  style="background-color: #00205c; color: #fff;">
                                                                            <tr>
                                                                                <th>#</th>
                                                                                <th>School Name</th>
                                                                                <th>Download Bulk Upload</th>
                                                                                <th>Date &amp; Time</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody class="text-dark">
                                                                            @if(!empty($BulkUpload_data))
                                                                                @foreach ($BulkUpload_data as $key => $data)
                                                                                        <tr>
                                                                                            <td>
                                                                                                {{ $key + 1 }}
                                                                                            </td>
                                                                                            <td>
                                                                                                {{ $schooldata->school_name }}
                                                                                            </td>
                                                                                            
                                                                                            <td>
                                                                                              <a href="{{ url('uploads/bulk_upload_file/') }}/{{ $data->bulk_upload_file }}" class="btn btn-sm" style="background-color: #00205c; color: #fff;" download>
                                                                                                <i class="fas fa-download"></i></a>
                                                                                            </td>
                                                                                            <td>
                                                                                                {{ isset($data->created_at) ? \Carbon\Carbon::parse($data->created_at)->format('d/m/Y') : '' }} <strong>|</strong>
                                                                                                {{ isset($data->created_at) ? \Carbon\Carbon::parse($data->created_at)->format('H:i') : '' }}
                                                                                            </td>
                                                                                        </tr>
                                                                                @endforeach
                                                                            @endif 
                                                                        </tbody>

                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
</body>
@endsection
@section('script-section')
<script>
    $('#studentcreateimport').submit(function(){
            event.preventDefault();
            var formData = new FormData();
            
            formData.append('csv_file', $('#file')[0].files[0]);
           $.ajax({
             url: '{{ route("student.import", ['school_id' => $school_id]) }}',
             type: 'post',
             data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
             success: function(response){
                if(response['status'] == true){
                    //console.log(response,"response");
                    $("#schoolidnotexists").removeClass('text-danger').siblings('p').addClass('text-success').html(response['message']);
                    window.location.href="{{  route('student.list',['school_id' => $school_id]) }}";
                }else{
                    var errors = response['errors'];
                    var school_id_not_exists = response['school_id_not_exists'];

                    var student_licence = response['student_licence'];
                    if(student_licence == 'error'){
                        $("#student_licence").siblings('p').addClass('text-danger').html('Maximum Student licences limit reached.');
                    }
                    
                    $("#schoolidnotexists").siblings('p').addClass('text-danger').html(school_id_not_exists);
                    $('#tabledata').empty();
                        $.each(errors, function (key, value) {
                            var errorRow = $('<tr>');
                            errorRow.append(
                                `<td>
                                    <span class="font-size-16 font-weight-500 text-danger">
                                        ${value[0]}
                                    </span>
                                </td>`
                            );
                            $('#tabledata').append(errorRow);
                        });
                    }
                },
                error: function(){
                    console.log("Some things went wrong");
                }
            });
            });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file');
    const fileValidationMessage = document.getElementById('file-validation-message');

    fileInput.addEventListener('change', function() {
        const files = fileInput.files;
        if (files.length > 0) {
            const fileExtension = files[0].name.split('.').pop().toLowerCase();

            // Check file extension
            if (fileExtension === 'xlsx' || fileExtension === 'xls') {
                fileValidationMessage.textContent = ''; // Clear any previous error message
            } else {
                fileValidationMessage.textContent = 'Invalid file type, allowed: XLSX, XLS';
                fileValidationMessage.style.color = 'red';
                fileInput.value = ''; // Clear the file input
            }
        }
    });
});
</script>

@endsection