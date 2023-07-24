Your account seen suspicious and deleted by {{ $blockedBy }}, Administration.
@if($blockType == 'forever')
    <p>
        Account is no longer available!
    </p>
@else
    <p>
        Account should be available in several months.
    </p>
@endif
