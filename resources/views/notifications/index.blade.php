@extends('layouts.app')

@section('content')

<style>
.notification-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
    gap:18px;
}

.notification-header h2{
    font-weight:700;
    margin:0;
    letter-spacing:-.02em;
}

.stats-card{
    border-radius:15px;
    background:var(--bg-card);
    border:1px solid var(--border-card);
    padding:18px;
    transition:.25s;
    box-shadow:0 8px 20px rgba(15,23,42,.05);
}

.stats-card:hover{
    transform:translateY(-3px);
}

.stats-card h5{
    color:var(--text-muted);
    font-size:13px;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:.06em;
    margin-bottom:8px;
}

.stats-card h2{
    margin:0;
    font-weight:800;
    letter-spacing:-.03em;
}

.notification-card{
    border:none;
    border-left:5px solid transparent;
    border-radius:14px;
    background:var(--bg-card);
    margin-bottom:18px;
    transition:.25s;
    box-shadow:0 6px 18px rgba(15,23,42,.06);
}

.notification-card:hover{
    transform:translateY(-3px);
    box-shadow:0 10px 20px rgba(0,0,0,.08);
}

.notification-unread{
    border-left-color:#ef1c25;
}

.notification-read{
    opacity:.82;
}

.notification-icon{
    width:55px;
    height:55px;
    border-radius:12px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:22px;
    color:#fff;
}

.icon-reminder{
    background:#ef1c25;
}

.icon-completed{
    background:#198754;
}

.icon-overdue{
    background:#fd7e14;
}

.icon-info{
    background:#0d6efd;
}

.search-box{
    border-radius:12px;
}

.filter-btn{
    border-radius:30px;
}

.notification-message{
    color:var(--text-muted);
}

.small-time{
    font-size:13px;
    color:gray;
}

.notification-toolbar{
    background:var(--bg-card);
    border:1px solid var(--border-card);
    border-radius:16px;
    padding:16px;
    box-shadow:0 8px 20px rgba(15,23,42,.05);
}

.notification-chip{
    border-radius:999px;
    font-weight:700;
    font-size:12px;
    padding:7px 12px;
}

.notification-actions .btn{
    border-radius:10px;
    font-weight:700;
}

.notification-empty-state{
    border:1px dashed var(--border-card);
    border-radius:16px;
    background:var(--bg-card);
}

.notification-hidden{
    display:none !important;
}

@media (max-width: 768px){
    .notification-header{
        align-items:stretch;
        flex-direction:column;
    }

    .notification-header form,
    .notification-header button{
        width:100%;
    }
}

@media print{
    .notification-header form,
    .notification-toolbar,
    .notification-actions,
    .pagination{
        display:none !important;
    }

    .notification-card{
        box-shadow:none !important;
        break-inside:avoid;
    }
}
</style>

<div class="container-fluid">

<div class="notification-header">

<div>

<h2>
<i class="bi bi-bell-fill text-danger"></i>
Notification Center
</h2>

<p class="text-muted">
All Firebase alerts and TaskPulse notifications.
</p>

</div>

<div>

<form method="POST" action="/notifications/read-all">

@csrf

<button class="btn btn-danger">

<i class="bi bi-check2-all"></i>

Mark All Read

</button>

</form>

</div>

</div>

<div class="row mb-4">

<div class="col-md-4">

<div class="stats-card">

<h5>Total</h5>

<h2>{{ $totalCount }}</h2>

</div>

</div>

<div class="col-md-4">

<div class="stats-card">

<h5>Unread</h5>

<h2 class="text-danger">

{{ $unreadCount }}

</h2>

</div>

</div>

<div class="col-md-4">

<div class="stats-card">

<h5>Read</h5>

<h2 class="text-success">

{{ $readCount }}

</h2>

</div>

</div>

</div>

<form method="GET">

<div class="row mb-4">

<div class="col-md-6">

<input

type="text"

name="search"

value="{{ request('search') }}"

placeholder="Search notifications..."

class="form-control search-box">

</div>

<div class="col-md-3">

<select
name="filter"
class="form-select">

<option value="">All</option>

<option
value="unread"
{{ request('filter')=='unread'?'selected':'' }}>

Unread

</option>

<option
value="read"
{{ request('filter')=='read'?'selected':'' }}>

Read

</option>

</select>

</div>

<div class="col-md-3">

<button class="btn btn-primary w-100">

<i class="bi bi-search"></i>

Search

</button>

</div>

</div>

</form>

<div class="notification-toolbar mb-4">

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

<div class="d-flex flex-wrap gap-2">

<button type="button" class="btn btn-outline-secondary btn-sm notification-chip" data-client-filter="all">
All Visible
</button>

<button type="button" class="btn btn-outline-danger btn-sm notification-chip" data-client-filter="unread">
Unread Only
</button>

<button type="button" class="btn btn-outline-success btn-sm notification-chip" data-client-filter="read">
Read Only
</button>

<button type="button" class="btn btn-outline-primary btn-sm notification-chip" data-client-filter="reminder">
Reminders
</button>

</div>

<div class="notification-actions d-flex flex-wrap gap-2">

<button type="button" class="btn btn-light border btn-sm" onclick="clearNotificationSearch()">
<i class="bi bi-x-circle"></i>
Clear Search
</button>

<button type="button" class="btn btn-light border btn-sm" onclick="window.print()">
<i class="bi bi-printer"></i>
Print
</button>

</div>

</div>

<small id="notificationVisibleCount" class="text-muted d-block mt-3">
Showing current notification page.
</small>

</div>

<hr>
@forelse($notifications as $notification)

@php

$iconClass='icon-info';
$icon='bi-info-circle-fill';

switch($notification->icon){

    case 'bell':
        $iconClass='icon-reminder';
        $icon='bi-bell-fill';
        break;

    case 'check':
        $iconClass='icon-completed';
        $icon='bi-check-circle-fill';
        break;

    case 'warning':
        $iconClass='icon-overdue';
        $icon='bi-exclamation-triangle-fill';
        break;
}

@endphp

<div class="card notification-card {{ $notification->is_read ? 'notification-read' : 'notification-unread' }}"
     data-read-state="{{ $notification->is_read ? 'read' : 'unread' }}"
     data-type="{{ strtolower($notification->type ?? 'info') }}">

<div class="card-body">

<div class="d-flex justify-content-between">

<div class="d-flex">

<div class="notification-icon {{ $iconClass }} me-3">

<i class="bi {{ $icon }}"></i>

</div>

<div>

<h5 class="mb-1">

{{ $notification->title }}

@if(!$notification->is_read)

<span class="badge bg-danger ms-2">

NEW

</span>

@endif

</h5>

<p class="notification-message mb-2">

{{ $notification->message }}

</p>

<div class="small-time">

<i class="bi bi-clock"></i>

{{ \Carbon\Carbon::parse($notification->notification_time)->diffForHumans() }}

</div>

</div>

</div>

<div class="text-end notification-actions">

@if($notification->type=="Reminder")

<span class="badge bg-danger">

Reminder

</span>

@elseif($notification->type=="Completed")

<span class="badge bg-success">

Completed

</span>

@elseif($notification->type=="Overdue")

<span class="badge bg-warning text-dark">

Overdue

</span>

@else

<span class="badge bg-primary">

{{ $notification->type }}

</span>

@endif

<br><br>
@if(!$notification->is_read)

<form
action="/notifications/read/{{ $notification->id }}"
method="POST"
class="mb-2">

@csrf

<button
class="btn btn-success btn-sm w-100">

<i class="bi bi-check2"></i>

Mark Read

</button>

</form>

@endif

<form
action="/notifications/delete/{{ $notification->id }}"
method="POST"
onsubmit="return confirm('Delete this notification?')">

@csrf
@method('DELETE')

<button
class="btn btn-outline-danger btn-sm w-100">

<i class="bi bi-trash"></i>

Delete

</button>

</form>

</div>

</div>

</div>

</div>

@empty

<div class="card notification-empty-state">

<div class="card-body text-center py-5">

<i class="bi bi-bell-slash display-3 text-secondary"></i>

<h4 class="mt-3">

No Notifications Found

</h4>

<p class="text-muted">

Your notification center is empty.

</p>

</div>

</div>

@endforelse

<div class="mt-4">

{{ $notifications->links() }}

</div>
<script>

setInterval(function(){

    fetch(window.location.href,{
        headers:{
            "X-Requested-With":"XMLHttpRequest"
        }
    })
    .then(response=>response.text())
    .then(html=>{

        const parser=new DOMParser();

        const doc=parser.parseFromString(html,"text/html");

        const newContent=doc.querySelector(".container-fluid");

        if(newContent){

            document.querySelector(".container-fluid").innerHTML=newContent.innerHTML;
            bindNotificationCenterTools();
            animateNotificationCards();

        }

    });

},10000);

function animateNotificationCards(){


    const cards=document.querySelectorAll(".notification-card");

    cards.forEach(function(card,index){

        card.style.opacity=0;

        card.style.transform="translateY(20px)";

        setTimeout(function(){

            card.style.transition=".4s";

            card.style.opacity=1;

            card.style.transform="translateY(0px)";

        },index*100);

    });

}

function applyNotificationFilter(filter){

    const cards=document.querySelectorAll(".notification-card");

    let visible=0;

    cards.forEach(function(card){

        const readState=card.dataset.readState;

        const type=card.dataset.type;

        const shouldShow=
            filter==="all" ||
            filter===readState ||
            filter===type;

        card.classList.toggle("notification-hidden",!shouldShow);

        if(shouldShow){
            visible++;
        }

    });

    const counter=document.getElementById("notificationVisibleCount");

    if(counter){
        counter.innerText="Showing "+visible+" notification"+(visible===1?"":"s")+" on this page.";
    }

}

function clearNotificationSearch(){

    const search=document.querySelector('input[name="search"]');

    const filter=document.querySelector('select[name="filter"]');

    if(search){
        search.value="";
    }

    if(filter){
        filter.value="";
    }

    applyNotificationFilter("all");

}

function bindNotificationCenterTools(){

    document.querySelectorAll("[data-client-filter]").forEach(function(button){

        button.addEventListener("click",function(){

            applyNotificationFilter(this.dataset.clientFilter);

        });

    });

    applyNotificationFilter("all");

}

document.addEventListener("DOMContentLoaded",function(){

    bindNotificationCenterTools();
    animateNotificationCards();

});

</script>

@endsection
