<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <div>
        Collection date : {{ $collection->scheduled_time }}
    </div>
    <div>
        Number of volunteers present : {{ $nb_volunteers }}
    </div>
    Products collected :
    @for($i=0; $i <  count($products); $i++)
    <div>
       &nbsp;&nbsp;&nbsp;&nbsp;{{ $products[$i] . ' : ' . $quantities[$i]; }}
    </div>
    @endfor
</body>
</html>