@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if(Session::has('message'))
<div class="alert alert-{{Session::get('message_type')}} alert-dismissible">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <h5><i class="icon fas @if(Session::get('message_type')=='success') fa-check @else fa-ban @endif"></i> {{ucfirst(Session::get('message_type'))}}!</h5>
  {{Session::get('message')}}
</div>
	{{Session::forget(['message', 'message_type'])}}
@endif