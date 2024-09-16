@php
    use App\Models\Beneficiary;
@endphp
<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <div>
        Distribution date : {{ $distribution->scheduled_time }}
    </div>
    Products distributed :
    @for($i=0; $i <  count($products); $i++)
    <div>
       &nbsp;&nbsp;&nbsp;&nbsp;{{ $products[$i] . ' : ' . $quantities[$i]; }}
    </div>
    @endfor

    Beneficiaries :
    @foreach($beneficiaries_ids as $id)
    <div>
       &nbsp;&nbsp;&nbsp;&nbsp;{{ Beneficiary::find($id)->name }}
    </div>
    @endforeach
</body>
</html>