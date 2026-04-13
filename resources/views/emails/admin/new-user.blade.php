<x-mail::message>
# New User Registration

A new user has registered on **{{ config('app.name') }}**.

## User Details:

| Field | Value |
|-------|-------|
| **Name** | {{ $user->name }} |
| **Email** | {{ $user->email }} |
| **Registered** | {{ $registeredAt }} |
| **Referral Code** | {{ $user->referral_code ?? 'N/A' }} |
| **Referred By** | {{ $user->referrer?->name ?? 'Organic' }} |

<x-mail::button :url="route('admin.users.index')">
View All Users
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
