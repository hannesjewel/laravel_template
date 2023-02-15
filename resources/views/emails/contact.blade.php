@component('mail::message')
# You have a new contact message from website

@component('mail::table')
|        |          |
| ------------- |:------------- | 
| Full Name | {{ $data['fullname'] }} |
| Email | {{ $data['email'] }} |
@endcomponent

# Message

{{ $data['message'] }}

@endcomponent
