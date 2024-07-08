<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student</title>
    <style>
        .last_section {
            border-left: hidden;
            border-bottom: hidden;
        }
        table {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
        }

        .border {
        	border: solid 1px #000 !important;
        }

        .border-right {
        	border-right: solid 1px #000 !important;
        }

        table.border-none > tr > td {
            border: none;
        }

        table.border, th.border, td.border {
            border: solid 1px #000 !important;
        }

        table {
        	font-size: 18px;
        }
        .header {
        max-width: 100%;
        margin: 10px auto;
        background: #FFA07A; 
        padding: 10px 10px; 
        height: 0.5%; 
    }
    </style>
</head>

<body>
    <div class="wrapper">
        <table width="100%" class="header">
            <tr >
                <td align="center" colspan="2">
                    <strong style="font-size: 18px;"> TAX INVOICE</strong>
                </td>
                <td align="right">
                   <strong style="font-size: 18px;"> INVOICE NO: {{ $invoice_number }}</strong><br>
                   <strong style="font-size: 18px;"> DATE:      {{ isset($invoice_date) ? \Carbon\Carbon::parse($invoice_date)->format('d-m-Y') : '' }}</strong>
                </td>
            </tr>
        </table>
        <table width="100%"  cellspacing="1" cellpadding="3">
            <tr>
                <td align="center" style="font-size: 22px;"><strong>BREEZY HUT IDEAS PVT LTD</strong></td>
            </tr>
            <tr>
               <td align="center">GSTIN: 09AAICB0491R1Z1</td>
            </tr>
            <tr>
                <td align="center">{!! $address !!} </td>
            </tr>
            <!-- <tr>
              <td align="center" >PAN NO. AAICB0491R</td>
            </tr> -->
        </table>
        <br>
        <br>
        <br>
<table class="border" autosize="1" width="100%" border="1px" cellspacing="0" cellpadding="10">
	<tr>
		<th width="10%" align="left"  class="border" colspan="7">
            <b><!-- SUBSCRIBER'S NAME: - --> Bill to: -</b><br>
            <b>Name:</b>  {{ $name }}<br>
            <b>Username:</b>  {{ $username }}<br>
            <b>Mobile Number: </b>  {{ $phone_number }}<br>
            <b>Email:</b>  {{ $email }}
        </th>
	</tr>

	<tr>
		<th width="80%" height="5%"  class="border"   align="left" colspan="6">Description</th>
		<th width="20%" height="5%" class="border"   align="center">Amount</th>
	
	</tr>
	
	<tr>
        <td  height="15%" align="left" colspan="6">{!! $description !!} {{ $hsn_code }}</td>
        <td   height="15%" align="center">{{ $amount }}</td>
	</tr>
    <tr>
        <td class="last_section" height="10%" align="left" colspan="4"></td>
        <td  height="10%">
              <span><b>Promo Code</b></span>
              <span><b>Add : CGST@</b></span>
              <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>SGST@</b></span><br>
              <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>IGST @</b></span>
        </td>
        <td  height="10%" align="right" >
            <span><b>{{ $cgst }}</b></span><br>
            <span><b>{{ $sgst }}</b></span><br>
            <span><b>{{ $igst }}</b></span>
            
        </td>
        <td  height="10%" align="center" >
            <span><b>INR  {{ $gstAmount }} /-</b></span>
        </td>
	</tr>
	<tr>
        <td class="last_section" height="5%" align="left" colspan="4"></td>
        <td style="border-right: hidden;" height="5%" align="left" ><span><b>Grand Total</b></span></td>
        <td style="border-right: hidden;" height="5%" align="left" ></td>
        <td  height="5%" align="left" ><span><b>INR {{ $totalAmount }}/-</b></span></td>
	</tr>
	
</table>
<br>
<table class="border" autosize="1" width="100%" border="1px" cellspacing="0" cellpadding="6">
    <tr>
         <th height="6%" align="left"> <span style="border-bottom: 1px solid; font-style: italic;">Total Amount (₹ - In Words): </span>{{ $amount_in_words }} &nbsp; Rupees </th>
    </tr>
</table>
<br>
     <table  width="100%" cellspacing="0" cellpadding="6">
        <tr>
            <th align="left" style="font-style: italic;">
              For: Breezy Hut Ideas Pvt Ltd 
            </th>
        </tr>
        <br>
        <br>
        <tr>
            <th align="left" style="font-style: italic;">
             Authorized Signatory 
            </th>
        </tr>
     </table>
    </div>
</body>

</html>