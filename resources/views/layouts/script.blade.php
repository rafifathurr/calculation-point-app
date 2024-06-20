<script src="{{ asset('lib/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('lib/ionicons/ionicons.js') }}"></script>
<script src="{{ asset('lib/jquery.flot/jquery.flot.js') }}"></script>
<script src="{{ asset('lib/jquery.flot/jquery.flot.resize.js') }}"></script>
<script src="{{ asset('lib/chart.js/Chart.bundle.min.js') }}"></script>
<script src="{{ asset('lib/peity/jquery.peity.min.js') }}"></script>
<script src="{{ asset('lib/sweetalert2/sweetalert2.min.js') }}"></script>

<script src="{{ asset('js/azia.js') }}"></script>
<script src="{{ asset('js/chart.flot.sampledata.js') }}"></script>
<script src="{{ asset('js/dashboard.sampledata.js') }}"></script>

<script src="{{ asset('lib/datatables.net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('lib/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>

@include('js.alert')
@stack('js-bottom')
