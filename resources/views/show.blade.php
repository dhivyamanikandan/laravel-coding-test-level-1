@extends('layout')

@section('content')

<div class="card">
    <div class="card-header"><i class="fa fa-fw fa-plus-circle"></i> <strong>Show individual event</strong> <a href="{{ route('events.index') }}" class="float-right btn btn-dark btn-sm"><i class="fa fa-fw fa-globe"></i> Browse Events</a></div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-2">
                    <label>Event Name</label>
                </div>
                <div class="col-sm-10">:
                    {{ $event->name }}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <label>Event Slug</label>
                </div>
                <div class="col-sm-10">:
                    {{ $event->slug }}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <label>Event Start At</label>
                </div>
                <div class="col-sm-10">:
                    {{ $event->startAt }}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <label>Event End At</label>
                </div>
                <div class="col-sm-10">:
                    {{ $event->endAt }}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <label>Event Created At</label>
                </div>
                <div class="col-sm-10">:
                    {{ $event->createdAt }}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <label>Event Updated At</label>
                </div>
                <div class="col-sm-10">:
                    {{ $event->updatedAt }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
