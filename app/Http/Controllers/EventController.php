<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Mail\NewEventNotification;
use Illuminate\Support\Facades\Mail;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = Event::latest();

        if($request->has('name') && $request->name !== null){

            $search->where('name','LIKE','%'.$request->name.'%');

        }

        if($request->has('slug') && $request->slug !== null){

            $search->where('slug','LIKE','%'.$request->slug.'%');

        }

        if($request->has('startAt') && $request->startAt !== null){

            $start = explode('to',$request->startAt);

            $search->whereBetween('startAt',[$start[0],$start[1]]);

        }

        if($request->has('endAt') && $request->endAt !== null){

            $end = explode('to',$request->endAt);

            $search->whereBetween('endAt',[$end[0],$end[1]]);

        }

        if($request->has('createdAt') && $request->createdAt !== null){

            $created = explode('to',$request->createdAt);

            $search->whereBetween('createdAt',[$created[0],$created[1]]);

        }

        if($request->has('updatedAt') && $request->updatedAt !== null){

            $updated = explode('to',$request->updatedAt);

            $search->whereBetween('updatedAt',[$updated[0],$updated[1]]);

        }

        $events = $search->paginate(10);

        return view('index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $storeData = $request->validate([
            'name' => 'required|max:255',
            'slug' => 'unique:event|nullable|max:255',
            'startAt' => 'nullable|date',
            'endAt' => 'nullable|date',
        ]);

        $event = Event::create($storeData);

        $details = [
            'title' => 'Event successfully created!',
            'body' => 'Please find below for the details event -'
        ];

        $user = auth()->user();

        Mail::to($user->email)->send(new NewEventNotification(array_merge($details,json_decode($event, true))));

        return redirect('/events')->with('success', 'Event created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = Event::findOrFail($id);

        return view('show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);

        return view('update', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'slug' => 'unique:event,slug,'.$id.'|nullable|max:255',
            'startAt' => 'nullable|date',
            'endAt' => 'nullable|date',
        ]);

        Event::whereId($id)->update($data);

        return redirect('/events')->with('success', 'Event updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        $event->delete();

        return redirect('/events')->with('completed', 'Event deleted');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function events(Request $request)
    {

        if($request->has('pageIndex') || $request->has('pageSize')){

            $pageSize = $request->pageSize ?? 5;
            $pageIndex = $request->pageIndex ?? 0;

            $events = Event::paginate($pageSize, ['*'], 'page', $pageIndex+1);
        } else {
            $events = Event::all();
        }

        $response = $events->isEmpty() ? ['message' => 'not found'] : $events;

        return json_encode($response);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function active_events()
    {
        $events = Event::where('startAt', '<=',date("Y-m-d H:i:s"))->where('endAt', '>=',date("Y-m-d H:i:s"))->get();

        $response = $events->isEmpty() ? ['message' => 'not found'] : $events;

        return json_encode($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function events_create(Request $request)
    {
        $response = ['message' => 'fail'];

        if(!empty($request) && $request->has('name')){

            $start = $request->has('startAt') ? $request->startAt : date('Y-m-d H:i:s', strtotime('-1 week', strtotime(date("Y-m-d H:i:s"))));
            $end =  $request->has('endAt') ? $request->endAt : date('Y-m-d H:i:s', strtotime('+1 week', strtotime(date("Y-m-d H:i:s"))));

            $event = Event::create(['name'=>$request->name,'startAt'=>$start,'endAt'=>$end]);

            if(isset($event->id)) {
                $response['message'] = 'success';
                $response['id'] = $event->id;
                $response['slug'] = $event->slug;
                $response['startAt'] = $event->startAt;
                $response['endAt'] = $event->endAt;
            }
        }

        return json_encode($response);

        // Event::all();
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function event_get($id)
    {
        $event = Event::where('id',$id)->first();

        $response = $event === null ? ['message' => 'not found'] : $event;

        return json_encode($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function events_put(Request $request, $id)
    {
        $response = ['message' => 'fail'];

        if(!empty($request) && $id != ""){

            $update = [];

            if($request->has('name')){
                $update['name'] = $request->name;
            }

            if($request->has('startAt')){
                $update['startAt'] = $request->startAt;
            }

            if($request->has('endAt')){
                $update['endAt'] = $request->endAt;
            }

            if(!empty($update)){

                $event = Event::updateOrCreate(['id'=>$id],$update);

                if(isset($event->id)) {
                    $response['message'] = 'success';
                    $response['id'] = $event->id;
                    $response['slug'] = $event->slug;
                    $response['startAt'] = $event->startAt;
                    $response['endAt'] = $event->endAt;
                }
            }
        }

        return json_encode($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function events_patch(Request $request, $id)
    {
        $response = ['message' => 'fail'];

        $event = Event::where('id',$id)->first();

        if ($event !== null && ($request->has('name') || $request->has('startAt') || $request->has('endAt'))) {

            if($request->has('name')){
                $event->name = $request->name;
            }

            if($request->has('startAt')){
                $event->startAt = $request->startAt;
            }

            if($request->has('endAt')){
                $event->endAt = $request->endAt;
            }

            $event->save();

            if(isset($event->id)) {
                $response['message'] = 'success';
                $response['id'] = $event->id;
                $response['slug'] = $event->slug;
                $response['startAt'] = $event->startAt;
                $response['endAt'] = $event->endAt;
            }
        }

        return json_encode($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function events_delele($id)
    {

        $response = ['message' => 'fail'];

        $event = Event::where('id',$id)->first();

        if($event !== null){

            $event->delete();
            $response['message'] = 'success';
            $response['id'] = $event->id;

        }

        return json_encode($response);
    }
}
