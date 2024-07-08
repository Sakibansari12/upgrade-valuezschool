<div class="card-body p-0">
    <div class="row g-0">
        <div class="col-sm-3 col-xl-12 col-xxl-3 text-center">
            <img src="{{ url('uploads/school') }}/{{ !empty($school_data->school_logo != '') ? $school_data->school_logo : 'no_image.png' }}"
                height="64" class="bg-light mt-2" alt="{{ $school_data->school_name }}">
        </div>
        <div class="col-sm-9 col-xl-12 col-xxl-9 text-center mt-2">
            <strong>{{ $school_data->school_name }}</strong>
        </div>
    </div>

    <table class="table my-2" >
        <tbody>
            <tr>
                <th>Order ID</th>
                <td class="text-fade">
                <a href="#" class="fw-bold">
                    {{ isset($payment_data->orderid) ? $payment_data->orderid : '' }}</a></td>
            </tr>
            <tr>
                <th>Amount</th>
                <td class="text-fade"><a href="#" class="fw-bold">{{ isset($payment_data->amount) ? number_format($payment_data->amount) : '' }}</a></td>
            </tr>
            <tr>
                <th>Payment mode</th>
                <td class="text-fade"><a href="#" class="fw-bold">{{ isset($payment_data->upi_id) ? $payment_data->upi_id : '' }}</a></td>
            </tr>
            <tr>
                <th>Payment Status</th>

                <td class="text-fade">
                    <!-- <a href="#"
                        class="text-white badge bg-{{ $payment_data->payment_status == 1 ? 'success' : 'danger' }}"
                        >{{ $payment_data->payment_status == 1 ? 'successful' : 'Pending' }}</a> -->
                        @if($payment_data->payment_failed_info)
                        <a href="#"
                                class="text-white badge bg-danger"
                                >Failed</a>
                        @else
                        <a href="#"
                                class="text-white badge bg-{{ $payment_data->payment_status == 1 ? 'success' : 'danger' }}"
                                >{{ $payment_data->payment_status == 1 ? 'Done' : 'Pending' }}</a>

                    @endif
                
                    </td>
            </tr>
            <tr>
                <th>Payment Date</th>
                <td class="text-fade">
                @if($payment_data->payment_sucessfull_time)
                    <a href="#" class="fw-bold">
                       {{ isset($payment_data->payment_sucessfull_time) ? \Carbon\Carbon::parse($payment_data->payment_sucessfull_time)->format('d/m/Y') : '' }}
                       
                    </a>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Duration Package</th>
                <td class="text-fade"><a href="#" class="fw-bold">{{ isset($payment_data->duration_package) ? $payment_data->duration_package : '' }}</a></td>
            </tr>
            @if($payment_data->start_date_sub)
            <tr>
                <th>Subscription Start Date</th>
                <td class="text-fade"><a href="#" class="fw-bold">{{ isset($payment_data->start_date_sub) ? \Carbon\Carbon::parse($payment_data->start_date_sub)->format('d/m/Y') : '' }}</a></td>
            </tr>
            <tr>
                <th>Subscription End Date</th>
                <td class="text-fade"><a href="#" class="fw-bold">{{ isset($payment_data->start_end_sub) ? \Carbon\Carbon::parse($payment_data->start_end_sub)->format('d/m/Y') : '' }}</a></td>
            </tr>
            @endif
            <!-- <tr>
                <th>Payment Due</th>
                <td class="text-fade">
                    @if($payment_data->payment_due>0)
                        <button type="button" class="waves-effect waves-circle btn btn-circle btn-primary-light">
                        <i class="mdi mdi-arrow-top-right">{{ $data->payment_due }}D</i>
                        </button>
                    @endif  
                </td>
            </tr> -->
        </tbody>
    </table>
</div>
