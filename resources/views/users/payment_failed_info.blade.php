<div class="card-body p-0">
    <div class="row g-0">
        <div class="col-sm-3 col-xl-12 col-xxl-3 text-center">
            <img src="{{ url('uploads/school') }}/{{ !empty($schools_payments_data->school_logo != '') ? $schools_payments_data->school_logo : 'no_image.png' }}"
                height="64" class="bg-light mt-2" alt="{{ $schools_payments_data->school_name_text }}">
        </div>
        <div class="col-sm-9 col-xl-12 col-xxl-9 text-center mt-2">
            <strong>{{ $schools_payments_data->school_name_text }}</strong>
            <p class="text-fade">{{ $schools_payments_data->school_name_text }}</p>
        </div>
    </div>

    <table class="table my-2" >
        <tbody>
            <tr>
                <th>Order ID</th>
                <td class="text-fade">
                <a href="#" class="fw-bold">
                    {{ isset($schools_payments_data->orderid) ? $schools_payments_data->orderid : '' }}</a></td>
            </tr>
            <tr>
                <th>Amount</th>
                <td class="text-fade"><a href="#" class="fw-bold">{{ isset($schools_payments_data->payment_amount) ? $schools_payments_data->payment_amount : '' }}</a></td>
            </tr>
            <tr>
                <th>Payment Link</th>
                <td class="text-fade">
                <a href="#" class="fw-bold" title="Payment url">
                    {{ isset($schools_payments_data->payment_url) ? $schools_payments_data->payment_url : '' }}</a></td>
            </tr>
            <tr>
                <th>Email Sent</th>
                <td class="text-fade">
                    @if($schools_payments_data->email_sent_at)
                    <a href="#" class="fw-bold">
                       {{ isset($schools_payments_data->email_sent_at) ? \Carbon\Carbon::parse($schools_payments_data->email_sent_at)->format('d/m/Y') : '' }} <strong>|</strong>
                       {{ isset($schools_payments_data->email_sent_at) ? \Carbon\Carbon::parse($schools_payments_data->email_sent_at)->format('H:i:s') : '' }} 
                    </a>
                    @endif
                </td>
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
                        {{ isset($schools_payments_data->payment_failed_info->payment_failed->description) ? $schools_payments_data->payment_failed_info->payment_failed->description : '' }}
                    </a>
                </td>
            </tr>
            <tr>
                <th>Reason</th>
                <td class="text-fade">
                    <a href="#">
                        {{ isset($schools_payments_data->payment_failed_info->payment_failed->reason) ? $schools_payments_data->payment_failed_info->payment_failed->reason : '' }}
                    </a>
                </td>
            </tr>
            <!-- <tr>
                <th>Pay Id</th>
                <td class="text-fade">
                    <a href="#">
                        {{ isset($schools_payments_data->payment_failed_info->payment_failed->metadata->payment_id) ? $schools_payments_data->payment_failed_info->payment_failed->metadata->payment_id : '' }}
                    </a>
                </td>
            </tr> -->
          
        </tbody>
    </table>
</div>
