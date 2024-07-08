<div class="card-body p-0">
    <div class="row g-0">
        <div class="col-sm-3 col-xl-12 col-xxl-3 text-center">
            <img src="{{ url('uploads/school') }}/{{ !empty($student_payments_data->school_logo != '') ? $student_payments_data->school_logo : 'no_image.png' }}"
                height="64" class="bg-light mt-2" alt="{{ $student_payments_data->school_name_text }}">
        </div>
        <div class="col-sm-9 col-xl-12 col-xxl-9 text-center mt-2">
            <strong>{{ $student_payments_data->school_name_text }}</strong>
            <p class="text-fade">{{ $student_payments_data->school_name_text }}</p>
        </div>
    </div>

    <table class="table my-2" >
        <tbody>
            <tr>
                <th>Order ID</th>
                <td class="text-fade">
                <a href="#" class="fw-bold">
                    {{ isset($student_payments_data->orderid) ? $student_payments_data->orderid : '' }}</a></td>
            </tr>
            <tr>
                <th>Amount</th>
                <td class="text-fade"><a href="#" class="fw-bold">{{ isset($student_payments_data->amount) ? number_format($student_payments_data->amount) : '' }}</a></td>
            </tr>
           
            
            <tr>
                <th>Payment Status</th>
                <td class="text-fade">
                    <a href="#"
                        class="text-white badge bg-danger"
                        >Failed</a>
                </td>
            </tr>
            
            <tr>
                <th>Description</th>
                <td class="text-fade">
                    <a href="#">
                        {{ isset($student_payments_data->payment_failed_info->payment_failed->description) ? $student_payments_data->payment_failed_info->payment_failed->description : '' }}
                    </a>
                </td>
            </tr>
            <tr>
                <th>Reason</th>
                <td class="text-fade">
                    <a href="#">
                        {{ isset($student_payments_data->payment_failed_info->payment_failed->reason) ? $student_payments_data->payment_failed_info->payment_failed->reason : '' }}
                    </a>
                </td>
            </tr>
            <tr>
                <th>Payment Date</th>
                <td class="text-fade">
                    @if($student_payments_data->payment_sucessfull_time)
                    <a href="#" class="fw-bold">
                       {{ isset($student_payments_data->payment_sucessfull_time) ? \Carbon\Carbon::parse($student_payments_data->payment_sucessfull_time)->format('d/m/Y') : '' }} <strong>|</strong>
                       {{ isset($student_payments_data->payment_sucessfull_time) ? \Carbon\Carbon::parse($student_payments_data->payment_sucessfull_time)->format('H:i:s') : '' }} 
                    </a>
                    @endif
                </td>
            </tr>
          
        </tbody>
    </table>
</div>
