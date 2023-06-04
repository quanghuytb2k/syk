<div>
    <p>Hello {{ $user->name_kana }} ({{ $user->name }}),</p>
    <p></p>
    <p></p>
    <p>
        Thanks for your interest in creating an account. To create your account, please verify your email address by clicking below.
        <br>
        <a href="{{ route('auth.activate', [ 'code' => $user->activation_code ]) }}">Verify email</a>
    </p>
    <p></p>
    <p></p>
    <p>Thanks.</p>
</div>
