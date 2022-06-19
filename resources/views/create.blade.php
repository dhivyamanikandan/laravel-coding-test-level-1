@extends('layout')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">
<div class="card">
    <div class="card-header"><i class="fa fa-fw fa-plus-circle"></i> <strong>Create an event</strong> <a href="{{ route('events.index') }}" class="float-right btn btn-dark btn-sm"><i class="fa fa-fw fa-globe"></i> Browse Events</a></div>
    <div class="card-body">
        <div class="col-sm-12">
            @if($errors->any())
                <div class="alert alert-danger">
                    <p><strong>Opps Something went wrong</strong></p>
                    <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">{{session('success')}}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{session('error')}}</div>
            @endif
            <h5 class="card-title">Fields with <span class="text-danger">*</span> are mandatory!</h5>
            <form name="add-event-form" id="add-event-form" method="post" action="{{ route('events.store') }}">
                @csrf
                <div class="form-group">
                    <label>Event Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="eventname" class="form-control" value="{{ old('name') }}" placeholder="Enter event name">
                </div>
                <div class="form-group">
                    <label>Event Slug </label>
                    <input type="text" name="slug" id="eventslug" class="form-control" value="{{ old('slug') }}" placeholder="Enter event slug (will auto generate based on name if empty)">
                </div>
                <div class="form-group">
                    <label>Event Start At </label>
                    <div class='input-group date' id='eventstartat'>
                        <input type='text' name="startAt" class="form-control" value="{{ old('startAt') }}" placeholder="Enter event start at (will auto select - 1 week if empty)"/>
                        <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                     </div>
                </div>
                <div class="form-group">
                    <label>Event End At </label>
                    <div class='input-group date' id='eventendat'>
                        <input type='text' name="endAt" class="form-control" value="{{ old('endAt') }}" placeholder="Enter event end at (will auto select + 1 week if empty)"/>
                        <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                     </div>
                </div>
                <div class="form-group text-right">
                    <button type="submit" name="submit" value="submit" id="submit" class="btn btn-success"><i class="fa fa-fw fa-plus-square"></i> Create Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#eventstartat').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss'
        });
        $('#eventendat').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss'
        });
    });
</script>
@endsection
