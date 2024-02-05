
    <script src="../../bower_components/jquery/dist/jquery.min.js"></script>
    <script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../../bower_components/metisMenu/dist/metisMenu.min.js"></script>
    <script src="../../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
    <script src="../../bower_components/chart.js/chart.min.js"></script>
    <script src="../../dist/js/sb-admin-2.js"></script>

    <script>

    function btnLoader(formObj){

        formObj.disabled = true;
        formObj.innerHTML = "processing ...";
        return true;  

    }

    $(document).ready(function() {
        $('#dataTables-example').DataTable({
                responsive: true
        });
    });

    // tooltip demo
    $('.tooltip-demo').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    })

    // popover demo
    $("[data-toggle=popover]")
    .popover()

    //modal autofocus
    $(document).on('shown.bs.modal', function() {
        $(this).find('[autofocus]').focus();
        $(this).find('[autofocus]').select();
    });

    //disable f12
    $(document).keydown(function (event) {
        if (event.keyCode == 123) { // Prevent F12
            return false;
        } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) { // Prevent Ctrl+Shift+I        
            return false;
        }
    });

    // disable inspect element
    // $(document).on("contextmenu", function (e) {        
    //     e.preventDefault();
    // });

    //disable back function
    // function preventBack() { window.history.forward(); }
    // setTimeout("preventBack()", 0);
    // window.onunload = function () { null };

    
    var alphanumericField = document.getElementById('alphanumericField');

    alphanumericField.addEventListener('input', function() {
        var fieldValue = alphanumericField.value;
        var alphanumericValue = fieldValue.replace(/[^a-zA-Z0-9-]/g, '');
        alphanumericField.value = alphanumericValue;
    });

    </script>