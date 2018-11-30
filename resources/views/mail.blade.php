Prezado <i>{{$demo->receiver}}</i>,
<p></p>

<p><u>Você tem uma nova atualização no Portal Checklist!</u></p>

<div>
<p><b>{{$demo->name}} {{$demo->text}}</b></p>
</div>

<div>
<button value='Abrir Portal' onclick='location.href="http://localhost:8000/"' type='button'></button>
</div>

Best Regards,
<br/>
<i>{{ $demo->sender }}</i>