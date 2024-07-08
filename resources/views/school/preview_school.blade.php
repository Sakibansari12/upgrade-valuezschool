<div class="card-body p-0">
    <div class="row g-0">
        <div class="col-sm-3 col-xl-12 col-xxl-3 text-center">
            <img src="{{ url('uploads/school') }}/{{ !empty($school_data->school_logo != '') ? $school_data->school_logo : 'no_image.png' }}"
                height="64" class="bg-light mt-2" alt="{{ $school_data->school_name }}">
        </div>
        <div class="col-sm-9 col-xl-12 col-xxl-9 text-center mt-2">
            <strong>{{ $school_data->school_name }}</strong>
            <p class="text-fade">{{ $school_data->school_desc }}</p>
        </div>
    </div>

    <table class="table my-2">
        <tbody>
            <tr>
                <th>Name</th>
                <td class="text-fade">{{ $school_data->primary_person }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td class="text-fade">{{ $school_data->primary_email }}</td>
            </tr>
            <tr>
                <th>Phone</th>
                <td class="text-fade">{{ $school_data->primary_mobile }}</td>
            </tr>
            <tr>
                <th>Access Code</th>
                <td class="text-fade">{{ $school_data->access_code }}</td>
            </tr>
            <tr>
                <th>Address</th>
                <td class="text-fade">
                    {{ $school_data->address }}
                    {{ $city_state ? $city_state->city . ', ' . $city_state->state->name : '' }},
                    {{ $school_data->pincode }}
                </td>
            </tr>
            <tr>
                <th>Subscription </th>
                <td class="text-fade">{{ date('d-M-Y', strtotime($school_data->package_start)) }} -
                    {{ date('d-M-Y', strtotime($school_data->package_end)) }}</td>
            </tr>
            <tr>
                <th>Grade </th>
                <td class="text-fade">{{ $grades }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td><span
                        class="badge bg-{{ $school_data->status == 1 ? 'success' : 'danger' }}-light">{{ $school_data->status == 1 ? 'Active' : 'Inactive' }}</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>
