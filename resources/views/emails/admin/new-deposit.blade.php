<x-mail::message>
# New Deposit Request

**{{ $user->name }}** has submitted a deposit request.

## Deposit Details:

| Field | Value |
|-------|-------|
| **User** | {{ $user->name }} ({{ $user->email }}) |
| **Amount** | ₦{{ number_format($purchase->amount_naira) }} |
| **Coins** | {{ number_format($purchase->amount_coins) }} coins |
| **Bank** | {{ $purchase->bank_name }} |
| **Account** | {{ $purchase->account_number }} |
| **Submitted** | {{ $purchase->created_at->format('M d, Y h:i A') }} |

> **Verification:** Check the transaction remark/reference for the user's email address: `{{ $user->email }}`

<x-mail::button :url="$adminUrl">
Review Deposit
</x-mail::button>

Please verify the payment and approve/reject this request.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
