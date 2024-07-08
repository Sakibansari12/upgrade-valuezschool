@extends('layout.main')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Classroom module completion status</h5>
                        <form action="{{ route('teacher.class.history') }}" method="GET">
                                <div class="card-actions float-end p-5">
                                    <div class="dropdown show custom-dropdown">
                                    <!-- <p class="card-title mb-0">Select class &#9660;</p> -->
                                        <select name="grade" id="grade-filter" onchange="this.form.submit()" class="form-control wide form-control-sm filter-button fixed-select">
                                            <option value="">Select class </option>
                                            <option value="">All Classes </option>
                                            @if($class_list->isNotEmpty())
                                                @foreach ($class_list as $gdata)
                                                    <option value="{{ $gdata->id }}" @if($grade == $gdata->id) selected @endif >{{ $gdata->class_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="card-actions float-end p-5">
                                    <div class="dropdown show custom-dropdown">
                                    <!-- <p class="card-title mb-0">Select course</p> -->
                                        <select name="course" id="grade-filter" onchange="this.form.submit()" class="form-control wide form-control-sm filter-button fixed-select">
                                            <option clas value="">Select course  </option>
                                            <option value="">All Courses  </option>
                                            @if($course_list->isNotEmpty())
                                                @foreach ($course_list as $cdata)
                                                    <option value="{{ $cdata->id }}" @if($course == $cdata->id) selected @endif >{{ $cdata->course_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <!-- <div class="dropdown-toggle" id="dropdownButton">
                                        <span class="icon-dropdown">&#9660;</span>
                                        </div> -->
                                    </div>
                                </div>
                            </form>
                        <div class="card-actions float-end">
                            <div class="dropdown show">
                                {{-- <a href="#"
                                        class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Add School Admin</a> --}}
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table id="yajra-table-login" class="table b-1" style="width:100%">
                            <thead style="background-color: #00205c; color: #fff; text-align: center;">
                                <tr>
                                    <th>#</th>
                                    <th>Grade</th>
                                    <th>Course</th>
                                    <th>Instructional Module</th>
                                    <th>Date & Time of completion</th>
                                </tr>
                            </thead>
                            <tbody class="text-dark">
                            @if(isset($studentreportsdata['student']) && is_array($studentreportsdata['student']))
                                @foreach ($studentreportsdata['student'] as $key => $data)
                                    <tr role="row" class="odd">
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td class="text-center">{{ isset($data->grade_class_name) ? $data->grade_class_name : '' }}</td>
                                        <td class="text-center">{{ isset($data->master_course_course_name) ? $data->master_course_course_name : '' }}</td>
                                        <td class="text-center">{{ isset($data->lesson_plan_title) ? $data->lesson_plan_title : '' }}</td>
                                        <td class="text-center">
                                            {{ isset($data->created_at) ? \Carbon\Carbon::parse($data->created_at)->format('d/m/Y') : '' }}  &nbsp;&nbsp;<strong>|</strong>&nbsp;&nbsp; 
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
    <!-- /.content -->
   
<!-- start  -->
@if(in_array($user->grade, [1, 2, 3]))
<section class="content">
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
      <div class="card-header header_milestone" style="text-align: center; border-radius: 35px; height: 80px;">
          <h1 class="headding-milestone img-fluid">21st Century Milestone Chart : Grade 1 to Grade 3</h1>
      </div>
        <div class="card-body">
          <div class="table-responsive">
            <main class="main-milestone">
              <section id="milestone" class="milestone section-milestone">
                <div class="container" data-aos="fade-up">
                  <div class="row section-milestone-row">
                    <div class="col-md-1 d-flex align-items-center">
                      
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                      <img src="{{ asset('assets_milestone/img/milestone/m_chart_1.png') }}" class="img-fluid me-2" style="width: 50px; height: 105px; object-fit: cover;" alt="">
                      <span class="fw-bolder">Self-awareness</span>
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                      <img src="{{ asset('assets_milestone/img/milestone/m_chart_2.png') }}" class="img-fluid me-2" style="width: 100px; height: 85px; object-fit: cover;" alt="">
                      <span class="fw-bolder">Self-management</span>
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                      <img src="{{ asset('assets_milestone/img/milestone/m_chart_3.png') }}" class="img-fluid me-2" style="width: 100px; height: 80px; object-fit: cover;" alt="">
                      <span class="fw-bolder">Social awareness</span>
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                      <img src="{{ asset('assets_milestone/img/milestone/m_chart_4.png') }}" class="img-fluid me-2" style="width: 100px; height: 115px; object-fit: cover;" alt="">
                      <span class="fw-bolder">Relationship skills</span>
                    </div>
                    <div class="col-md-3 d-flex align-items-center">
                      <img src="{{ asset('assets_milestone/img/milestone/m_chart_5.png') }}" class="img-fluid me-2" style="width: 100px; height: 100px; object-fit: cover;" alt="">
                      <span class="fw-bolder">Responsible decision-making</span>
                    </div>
                  </div>
                </div>
              </section>
              <section id="il-card" class="il-card section-milestone">
                <div class="container" data-aos="fade-up">
                  <div class="parent-milestone">
                    <div class="div2">
                      <img src="{{ asset('assets_milestone/img/milestone/milestone.png') }}" class="img-fluid" style="width: 100%; " />
                    </div>
                     @foreach ($filtered_lesson_plan as $key => $data)
                          @if($data['id'] == 230) 
                              <div class="member-img">
                                <!-- <img src="{{ asset('assets_milestone/img/milestone/start.png') }}"
                                class="img-fluid" /> -->
                              </div>
                          @endif
                        <div class="il-card-member" style="background-color: {{ $data['complesion_status'] ? '#4CAF50' : '#ebebe9' }}">
                        <div class="member-img">
                          <img src="{{ 'https://learn.valuezschool.com/uploads/lessonplan/' . ($data['lesson_image'] ? $data['lesson_image'] : 'no_image.png') }}" class="img-fluid" alt="">
                        </div>
                        @if(in_array($data['id'], [86, 99, 89, 117, 90, 238, 122, 84]))
                        <img src="{{ asset('assets_milestone/img/Flag_folder/flag_low_grade05.png') }}" style="max-width: 60%;  margin-left: 17px;" class="img-fluid-5" alt="">
                          @endif
                        @if(in_array($data['id'], [81, 94, 100, 79, 5, 82]))
                        <img src="{{ asset('assets_milestone/img/Flag_folder/flag_low_grade04.png') }} " style="max-width: 60%;  margin-left: 17px;" class="img-fluid-4" alt="">
                          @endif
                          @if(in_array($data['id'], [96, 97, 3, 83, 8, 91, 87]))
                          <img src="{{ asset('assets_milestone/img/Flag_folder/flag_low_grade03.png') }}" style="max-width: 60%;  margin-left: 17px;" class="img-fluid-3" alt="">
                          @endif
                           @if(in_array($data['id'], [80, 88, 234, 4, 98, 231, 17, 12]))
                            <img src="{{ asset('assets_milestone/img/Flag_folder/flag_low_grade02.png') }}" style="max-width: 60%;  margin-left: 17px;" class="img-fluid-2" alt="">
                          @endif
                          @if(in_array($data['id'], [230, 1, 113, 236, 85]))
                              <img src="{{ asset('assets_milestone/img/Flag_folder/flag_low_grade01.png') }}" class="img-fluid-1" alt="">
                          @endif
                      </div>
                    @endforeach 
                  </div>
                </div>
              </section>
            </main>
            <div class="container-fluid copyright text-center" style="
                          /* background-color: #00892c; */
                          color: #f6fafd;
                          margin-top: -258px;
                          background-image: url({{ asset('assets_milestone/img/footer_grass_new.png') }});
                          background-position: center center;
                          background-repeat: repeat;
                          background-size: contain;
                          padding: 88px;
                          ">
              </div>
          </div>
        </div>
        <footer class="footer-milestone">
            </footer>
      </div>
    </div>
  </div>
</section>
@endif
@if(in_array($user->grade, [4, 5]))
 <section class="content">
  <div class="row">
    <div class="col-xl-12">
      <div class="card" style="background-color: #00205c; color: #ffd15f;  border-radius: 39px;">
      <div class="card-header header_milestone" style="text-align: center; border-radius: 35px; height: 80px;">
          <h1 class="headding-milestone img-fluid" style="color: #00205c">21st Century Milestone Chart : Grade 4 to Grade 5</h1>
      </div>
        <div class="card-body">
          <div class="table-responsive">
            <main class="main-milestone">
              <section id="milestone" class="milestone section-milestone">
                <div class="container" data-aos="fade-up">
                  <div class="row section-milestone-row">
                  <div class="col-md-1 d-flex align-items-center">
                    </div>
                    <div class="col-md-2 d-flex align-items-center" >
                      <img src="{{ asset('assets_milestone/img/Flag_folder/flag_11_2.png') }}" class="img-fluid me-2" style="width: 100px; height: 105px; object-fit: cover; margin-bottom: 40px;" alt="">
                      <span class="fw-bolder">Self-awareness</span>
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                      <img src="{{ asset('assets_milestone/img/Flag_folder/flag_22_2.png') }}" class="img-fluid me-2" style="width: 100px; height: 85px; object-fit: cover;" alt="">
                      <span class="fw-bolder">Self-management</span>
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                      <img src="{{ asset('assets_milestone/img/Flag_folder/flag_33_2.png') }}" class="img-fluid me-2" style="width: 100px; height: 80px; object-fit: cover; margin-bottom: 20px;" alt="">
                      <span class="fw-bolder">Social awareness</span>
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                      <img src="{{ asset('assets_milestone/img/Flag_folder/flag_44_2.png') }}" class="img-fluid me-2" style="width: 100px; height: 100px; object-fit: cover;" alt="">
                      <span class="fw-bolder">Relationship skills</span>
                    </div>
                    <div class="col-md-3 d-flex align-items-center">
                      <img src="{{ asset('assets_milestone/img/Flag_folder/flag_55_2.png') }}" class="img-fluid me-2" style="width: 100px; height: 100px; object-fit: cover;" alt="">
                      <span class="fw-bolder">Responsible decision-making</span>
                    </div>
                  </div>
                </div>
              </section>
              <section id="il-card" class="il-card il-card-member-second">
                <div class="container" data-aos="fade-up">
                  <div class="parent-milestone-second">
                    
                    <div>
                      
                    </div>
                     @foreach ($filtered_lesson_plan_second as $key => $data)
                        @if($data['id'] == 105)
                        <div >
                        <img src="{{ asset('assets_milestone/img/milestone/milestone_new.png') }}" style="height: 45%; max-width: 114%;" />
                        </div>
                        @endif 
                        @if($data['id'] == 108)
                        <div style="max-width: 100px; height: auto;">
                        </div>
                        @endif
                        @if($data['id'] == 108) 
                          <div >
                            <img src="{{ asset('assets_milestone/img/Flag_folder/rockit.png') }}" class="img-fluid" style="margin-top: 70px;" alt="">
                          </div>
                        @endif
                        
                        <div class="il-card-member-second" style="background-color: {{ $data['complesion_status'] ? '#4CAF50' : '#3e4686' }}">
                        
                       <!--  {{ $data['id'] }} -->
                        <div class="member-img" >
                           <img src="{{ 'https://learn.valuezschool.com/uploads/lessonplan/' . ($data['lesson_image'] ? $data['lesson_image'] : 'no_image.png') }}" class="img-fluid" alt="" style="width: 100%; border-radius: 13px; height: auto; max-height: 180px; object-fit: cover;">
                        </div>
                        @if(in_array($data['id'], [105, 107, 124, 115]))
                        <img src="{{ asset('assets_milestone/img/Flag_folder/flag_55_2.png') }}" style="margin-top: -16px; margin-left: -3px;" class="flag-img" alt="">
                          @endif
                          @if(in_array($data['id'], [103, 106, 18]))
                          <img src="{{ asset('assets_milestone/img/Flag_folder/flag_44_2.png') }}"style="margin-bottom: 100px; margin-top: -12px;" class="flag-img" alt="">
                          @endif
                          @if(in_array($data['id'], [110, 123, 116, 104, 119, 121, 120, 112, 111]))
                          <img src="{{ asset('assets_milestone/img/Flag_folder/flag_33_2.png') }}" style="margin-top: -26px; margin-left: 10px; margin-bottom: 100px;" class="flag-img" class="flag-img" alt="">
                          @endif
                          @if(in_array($data['id'], [109, 235, 101, 102]))
                          <img src="{{ asset('assets_milestone/img/Flag_folder/flag_22_2.png') }}" style="margin-bottom: 100px; margin-top: -5px; margin-left: 9px;" class="flag-img" class="flag-img" alt="">
                          @endif
                          @if(in_array($data['id'], [108, 113, 237, 85]))
                          <img src="{{ asset('assets_milestone/img/Flag_folder/flag_11_2.png') }}" style="margin-bottom: 150px; margin-top: -22px;" class="flag-img" class="flag-img" alt="">
                          @endif
                      </div>
                    @endforeach 
                    
                  </div>
                  
                </div>
              </section>
            </main>
             
          </div>
        </div>
        <footer class="footer-milestone">
            </footer>
      </div>
    </div>
  </div>
</section>

@endif
<!-- end -->


@endsection


@section('script-section')
    <!-- <script type="text/javascript">
        $(function() {

            var table = $('#yajra-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                order: [],
                ajax: "{{ route('teacher.class.history') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'grade',
                        name: 'lessonplan.class_id'
                    },
                    {
                        data: 'course',
                        name: 'lessonplan.course_id'
                    },
                    {
                        data: 'lessonplan.title',
                        name: 'lessonplan.title'
                    },
                    {
                        data: 'created_at',
                        name: 'reports.created_at',
                    },

                ]
            });

        });
    </script> -->
@endsection
