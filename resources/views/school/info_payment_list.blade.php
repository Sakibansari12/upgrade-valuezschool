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
                <td class="text-fade"><a href="#" class="fw-bold">{{ isset($payment_data->payment_amount) ? $payment_data->payment_amount : '' }}</a></td>
            </tr>
            <tr>
                <th>Payment Link</th>
                <td class="text-fade">
                <a href="#" class="fw-bold" title="Payment url">
                    {{ isset($payment_data->payment_url) ? $payment_data->payment_url : '' }}</a></td>
            </tr>
            <tr>
                <th>Email Sent</th>
                <td class="text-fade">
                    @if($payment_data->email_sent_at)
                    <a href="#" class="fw-bold">
                       {{ isset($payment_data->email_sent_at) ? \Carbon\Carbon::parse($payment_data->email_sent_at)->format('d/m/Y') : '' }} <strong>|</strong>
                       {{ isset($payment_data->email_sent_at) ? \Carbon\Carbon::parse($payment_data->email_sent_at)->format('H:i:s') : '' }} 
                    </a>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Payment mode</th>
                <td class="text-fade"><a href="#" class="fw-bold">{{ isset($payment_data->upi_id) ? $payment_data->upi_id : '' }}</a></td>
            </tr>
            <tr>
                <th>Payment Status</th>

                <td class="text-fade">
                    <a href="#"
                        class="text-white badge bg-{{ $payment_data->payment_status == 1 ? 'success' : 'danger' }}"
                        >{{ $payment_data->payment_status == 1 ? 'successful' : 'Pending' }}</a>
                </td>
            </tr>
            <tr>
                <th>Payment successful</th>
                <td class="text-fade">
                @if($payment_data->payment_sucessfull_time)
                    <a href="#" class="fw-bold">
                       {{ isset($payment_data->payment_sucessfull_time) ? \Carbon\Carbon::parse($payment_data->payment_sucessfull_time)->format('d/m/Y') : '' }} <strong>|</strong>
                       {{ isset($payment_data->payment_sucessfull_time) ? \Carbon\Carbon::parse($payment_data->payment_sucessfull_time)->format('H:i:s') : '' }} 
                    </a>
                    @endif
                </td>
            </tr>
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
