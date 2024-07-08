@extends('layout.main')
@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0">Reminder Details</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
               
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<div class="container-fluid">
    <div class="row">
        <!-- column -->
        <div class="col-sm-12">
            <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Reminder Information</h5>
                        <div class="card-actions float-end">
                        </div>
                    </div>
                <div class="card-body">
                    <form name="studentcreate" id="studentcreate" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Reminder Title :</label>
                                    {{ isset($data->title) ? $data->title : '' }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Reminder Description :</label>
                                    {!! isset($data->description) ? $data->description : '' !!}
                                </div>
                            </div>
                        </div>
                </div>
                </form>
            </div>
                </div>
</div>
</div>
</div>
@endsection
@section('script-section')
@endsection
