
    <script src="../../bower_components/jquery/dist/jquery.min.js"></script>
    <script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../../bower_components/metisMenu/dist/metisMenu.min.js"></script>
    <script src="../../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
    <script src="../../dist/js/sb-admin-2.js"></script>

    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
                responsive: true
        });
    });
    </script>

    <script>
    // tooltip demo
    $('.tooltip-demo').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    })

    // popover demo
    $("[data-toggle=popover]")
        .popover()
    </script>

    <script type="text/javascript">
        //modal autofocus
        $(document).on('shown.bs.modal', function() {
          $(this).find('[autofocus]').focus();
          $(this).find('[autofocus]').select();
        });
    </script>

    <script type="text/javascript">
        //disable f12
        $(document).keydown(function (event) {
            if (event.keyCode == 123) { // Prevent F12
                return false;
            } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) { // Prevent Ctrl+Shift+I        
                return false;
            }
        });

        //disable inspect element
        $(document).on("contextmenu", function (e) {        
            e.preventDefault();
        });

    </script>

    <script type="text/javascript">
        //disable back function
        function preventBack() { window.history.forward(); }
        setTimeout("preventBack()", 0);
        window.onunload = function () { null };
    </script>

    <!-- Open Drawer -->

    <script type="text/javascript">

        $('#my_unlock_btn').click(function() {

            var my_secure_pin = $("#my_secure_piner").val();

            $.ajax({
              type: "POST",
              dataType: "json",
              data: {action: my_secure_pin},
              url: "printer_function.php",
                  success:function(data){
                    if(data.status == 'ok'){
                        $('#unlock_note').html("Drawer Open!");
                        $("#unlock_note").css("color", "green");
                        $('#my_drawer_unlock').modal('toggle');
                        $('#my_secure_piner').val("");
                    }else{
                        $('#unlock_note').html("Wrong PIN!");
                        $("#unlock_note").css("color", "red");
                        $('#my_secure_piner').val("");
                    } 
                }
            });   

        });
        
    </script>

    <script type="text/javascript">
        $("#my_secure_piner").keyup(function(event) {
            if (event.keyCode === 13) {
                $("#my_unlock_btn").click();
            }
        });
    </script>