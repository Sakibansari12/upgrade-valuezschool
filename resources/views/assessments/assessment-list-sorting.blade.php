@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- Instructional Module --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item alignment-text-new" aria-current="page"><a href="{{ route('ncf-assessment-list') }}"><i class="mdi mdi-home-outline"></i> - NCF Assessments</a></li>
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Sorting NCF Assessments</li>
                        </ol>
                    </nav>
                </div>
            </div>

        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-8 col-12">
                <div class="box" id="lesson-box-ncf">
                    <div class="box-header with-border">
                        <h4 class="box-title">Drag and drop for arrange sequence</h4>
                    </div>
                    <div class="box-body p-0">
                        <div class="media-list media-list-hover media-list-divided" id="lesson-list-ncf">
                            
                            @if($ncfAssessmentData->isNotEmpty())
                                            @foreach ($ncfAssessmentData as $key => $cdata)
                            <a class="media media-single" href="#" id="{{ $cdata['id'] }}">
                                <span class="title text-mute">{{ $cdata['title'] }}</span>
                                <span class="badge badge-pill badge-primary">{{ $key }}</span>
                            </a>
                            @endforeach
                        @endif
                        </div>
                    </div>

                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@section('script-section')
    
 <script>
       /*  $(document).ready(function() {
            $.ajax({
                url: "{{ route('assessment-list') }}",
                type: "GET",
                success: function(res) {
                    console.log(res, 'res');
                    $('#lesson-list').html(res);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }); */
        $(".media-list").sortable({
            delay: 100,
            stop: function() {
                var selectedData = new Array();
                
                $('.media-list>a').each(function() {
                    selectedData.push($(this).attr("id"));
                });
                updateOrder(selectedData);
            }
        });
        function updateOrder(data) {
            
            console.log(data,"data");
           // var gradeId = $('input[name="grade"]:checked').val();
          //  var courseId = $('#course_id').val();
            $.ajax({
                url: "{{ route('ncf.sorting.update') }}",
                type: 'post',
                data: {
                    position: data,
                  //  grade: gradeId,
                   // courseid: courseId
                },
                success: function(res) {
                    console.log(res);
                    $('.choose-grade').change();
                }
            })
        }
    </script>

    
@endsection
