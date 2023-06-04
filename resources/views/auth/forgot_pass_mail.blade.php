<div>
    <p>Hello {{ $user->name_kana }} ({{ $user->name }}),</p>
    <p></p>
    <p></p>
    <p>
        To update your password, please clicking to link below.
        <br>
        <a href="{{ route('auth.resetpass', [ 'code' => $user->forgot_pass_code ]) }}">Reset password</a>
    </p>
    <p></p>
    <p></p>
    <p>Thanks.</p>
</div>
