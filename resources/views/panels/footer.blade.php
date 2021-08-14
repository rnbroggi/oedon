<!-- BEGIN: Footer-->
@if ($configData['mainLayoutType'] == 'horizontal' && isset($configData['mainLayoutType']))
    <footer
        class="footer {{ $configData['footerType'] }} {{ $configData['footerType'] === 'footer-hidden' ? 'd-none' : '' }} footer-light navbar-shadow">
    @else
        <footer
            class="footer {{ $configData['footerType'] }} {{ $configData['footerType'] === 'footer-hidden' ? 'd-none' : '' }} footer-light">
@endif
<p class="clearfix blue-grey lighten-2 mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT
        &copy; {{ date('Y') }}<a class="text-bold-800 grey darken-2" href="https://github.com/rnbroggi"
            target="_blank">Nicolás Broggi,</a>All rights Reserved</span>
</p>
</footer>
<!-- END: Footer-->
